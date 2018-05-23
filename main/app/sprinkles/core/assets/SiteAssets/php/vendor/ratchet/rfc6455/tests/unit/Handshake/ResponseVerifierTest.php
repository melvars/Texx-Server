<?php

namespace Ratchet\RFC6455\Test\Unit\Handshake;

use Ratchet\RFC6455\Handshake\ResponseVerifier;

/**
 * @covers Ratchet\RFC6455\Handshake\ResponseVerifier
 */
class ResponseVerifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ResponseVerifier
     */
    protected $_v;

    public function setUp() {
        $this->_v = new ResponseVerifier;
    }

    public static function subProtocolsProvider() {
        return [
            [TRUE, ['a'], ['a']]
            , [TRUE, ['b', 'a'], ['c', 'd', 'a']]
            , [FALSE, ['a', 'b', 'c'], ['d']]
            , [TRUE, [], []]
            , [TRUE, ['a', 'b'], []]
        ];
    }

    /**
     * @dataProvider subProtocolsProvider
     */
    public function testVerifySubProtocol($expected, $response, $request) {
        $this->assertEquals($expected, $this->_v->verifySubProtocol($response, $request));
    }
}
