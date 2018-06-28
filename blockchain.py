import hashlib
import json
import re
import plyvel
from time import time
from urllib.parse import urlparse
from uuid import uuid4

import requests
from flask import Flask, jsonify, request


class Blockchain:
    def __init__(self):
        self.database = plyvel.DB("database/", create_if_missing=True)
        self.current_message = []
        self.chain = []
        self.nodes = set()

        # create first block (genesis)
        self.new_block(previous_hash='1', proof=100)

    def register_node(self, address):
        """
        registers a new node
        :param address: IP of node
        """

        if re.match(r'[0-9]+(?:\.[0-9]+){3}:[0-9]+', address):
            parsed_url = urlparse(address)
            if parsed_url.netloc:
                self.nodes.add(parsed_url.netloc)
            elif parsed_url.path:
                self.nodes.add(parsed_url.path)
            else:
                raise ValueError('Invalid URL')
        else:
            raise ValueError('Invalid URL')

    def valid_chain(self, chain):
        """
        check if a block is valid
        :param chain: A blockchain
        :return: True if valid, False if not
        """

        last_block = chain[0]
        current_index = 1

        while current_index < len(chain):
            block = chain[current_index]
            print(f'{last_block}')
            print(f'{block}')
            print("\n-----------\n")
            # check hash
            last_block_hash = self.hash(last_block)
            if block['previous_hash'] != last_block_hash:
                return False

            # check proof
            if not self.valid_proof(last_block['proof'], block['proof'], last_block_hash):
                return False

            last_block = block
            current_index += 1

        return True

    def resolve_conflicts(self):
        """
        Resolves conflict by replacing if other chain is longer
        :return: true if replaced
        """

        neighbours = self.nodes
        new_chain = None

        # only get the ones which are longer than own
        max_length = len(self.chain)

        # get all chains from nodes and verify
        for node in neighbours:
            response = requests.get(f'http://{node}/chain')

            if response.status_code == 200:
                length = response.json()['length']
                chain = response.json()['chain']

                # check if longer and valid
                if length > max_length and self.valid_chain(chain):
                    max_length = length
                    new_chain = chain

        # replace if other is longer
        if new_chain:
            self.chain = new_chain
            return True

        return False

    def new_block(self, proof, previous_hash):
        """
        create new block in blockchain
        :param proof: The proof given by the Proof of Work algorithm
        :param previous_hash: Hash of previous Block
        :return: New Block
        """

        block = {
            'index': len(self.chain) + 1,
            'timestamp': time(),
            'message': self.current_message,
            'proof': proof,
            'previous_hash': previous_hash or self.hash(self.chain[-1]),
        }

        # reset the list of messages
        self.current_message = []

        self.chain.append(block)
        self.database.put(b'key', b'value')
        return block

    def new_message(self, sender, recipient, message):
        """
        creates a new message (gets into next mined block)
        :param sender: Address of the Sender
        :param recipient: Address of the Recipient
        :param message: Message
        :return: The index of the Block that will hold this message
        """
        self.current_message.append({
            'sender': sender,
            'recipient': recipient,
            'message': message,
        })

        return self.last_block['index'] + 1

    @property
    def last_block(self):
        return self.chain[-1]

    @staticmethod
    def hash(block):
        """
        hash the block (sha256)
        :param block: Block
        """

        # sort dict
        block_string = json.dumps(block, sort_keys=True).encode()
        return hashlib.sha256(block_string).hexdigest()

    def proof_of_work(self, last_block):
        """
        create proof
        :param last_block: <dict> last Block
        :return: <int>
        """

        last_proof = last_block['proof']
        last_hash = self.hash(last_block)

        proof = 0
        while self.valid_proof(last_proof, proof, last_hash) is False:
            proof += 1

        return proof

    @staticmethod
    def valid_proof(last_proof, proof, last_hash):
        """
        Validates the Proof
        :param last_proof: <int> Previous Proof
        :param proof: <int> Current Proof
        :param last_hash: <str> The hash of the Previous Block
        :return: <bool> True if correct, False if not.
        """

        guess = f'{last_proof}{proof}{last_hash}'.encode()
        guess_hash = hashlib.sha256(guess).hexdigest()
        return guess_hash[:4] == "0000"


# start node
application = Flask(__name__)

# generate node ID
node_identifier = str(uuid4()).replace('-', '')

# create blockchain
blockchain = Blockchain()


@application.route('/mine', methods=['GET'])
def mine():
    # run the proof of work algorithm to get the next proof
    last_block = blockchain.last_block
    proof = blockchain.proof_of_work(last_block)
    blockchain.new_message(
        sender="0",
        recipient=node_identifier,
        message="",
    )

    # forge by adding block to chain
    previous_hash = blockchain.hash(last_block)
    block = blockchain.new_block(proof, previous_hash)

    response = {
        'answer': "New Block Forged",
        'index': block['index'],
        'message': block['message'],
        'proof': block['proof'],
        'previous_hash': block['previous_hash'],
    }
    return jsonify(response), 200


@application.route('/message/new', methods=['POST'])
def new_message():
    values = request.get_json()

    # check POST fields
    required = ['sender', 'recipient', 'message']
    if not all(k in values for k in required):
        return 'Missing values', 400

    # new message to blockchain
    index = blockchain.new_message(
        values['sender'], values['recipient'], values['message'])

    response = {'answer': f'Message will be added to Block {index}'}
    return jsonify(response), 201


@application.route('/chain', methods=['GET'])
def full_chain():
    response = {
        'chain': blockchain.chain,
        'length': len(blockchain.chain),
    }
    return jsonify(response), 200


@application.route('/nodes/list', methods=['GET'])
def getNodeList():
    response = {
        'total_nodes': list(blockchain.nodes)
    }
    return jsonify(response), 200


@application.route('/nodes/register', methods=['POST'])
def register_nodes():
    values = request.get_json()

    nodes = values.get('nodes')
    if nodes is None:
        return "Error: Please supply a valid list of nodes", 400

    for node in nodes:
        blockchain.register_node(node)

    response = {
        'answer': 'New nodes have been added',
        'total_nodes': list(blockchain.nodes),
    }
    return jsonify(response), 201


@application.route('/nodes/resolve', methods=['GET'])
def consensus():
    replaced = blockchain.resolve_conflicts()

    if replaced:
        response = {
            'answer': 'Our chain was replaced',
            'new_chain': blockchain.chain
        }
    else:
        response = {
            'answer': 'Our chain is authoritative',
            'chain': blockchain.chain
        }

    return jsonify(response), 200
