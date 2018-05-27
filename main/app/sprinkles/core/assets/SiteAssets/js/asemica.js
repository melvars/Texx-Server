function asemica(PlainText, CorpusUrl) {
    let CorpusString, Tokens, Transitions;

    fetch(CorpusUrl)
        .then(function (response) {
            response.text().then(function (response) {
                CorpusString = response;
                Tokens = tokenize_corpus(CorpusString);
                Transitions = generate_transitions(Tokens);
                console.log(Transitions);
            });
        });

    /*
    * Breaks the input corpus into a series of processable "tokens"
    *
    * Example output: ['The','Project','Gutenberg', ... ,'about','new','eBooks']
    */
    function tokenize_corpus(CorpusString) {
        // Clean up things
        const StrippedCorpus = CorpusString
            .replace(/\n/g, " ") // newlines
            .replace(/<\/?[^>]+(>|$)/g, "") // html
            .replace(/[^\w']/g, " ") // non-word characters
            .replace(/[0-9]/g, " ") // numbers
            .replace(/\s\s+/g, " ") // sequences of spaces
            .replace(/^\s+/, "") // leading whitespace
            .replace(/\s+$/, ""); // trailing whitespace

        Tokens = StrippedCorpus.split(/\s/);

        return Tokens;
    }

    /*
    * Creates the primary transition matrix
    *
    * Example output:
    *
   	* 'atlantic' => {                       // Lowercase form
    *           'seen' => 2,                // How many times seen in corpus
    *           'exits' => {                // Which words follow it?
    *                         'City' => 1,  // One instance of this
    *                         'and' => 1    // One instance of that
    *                      },               // Exits not guaranteed unique
    *           'door' => [                 // Doors are guaranteed unique
    *                        'City',        // Following door number 1
    *                        'and'          // Following door number 2
    *                     ],
    *           'doors' => 2,               // Cached count of doors
    *           'token' => 'Atlantic'       // Original form of the token
    * }
    * ...
    */
    function generate_transitions(Tokens) {
        Transitions = {};
        
        Tokens.forEach(function(Token, Index) {
            var ObjectKey = Token.toLowerCase();
            if (Transitions[ObjectKey] === undefined) { // Will run one time -> initialize
                Transitions[ObjectKey] = {};
                Transitions[ObjectKey]["seen"] = 1;
                Transitions[ObjectKey]["exits"] = {};
                Transitions[ObjectKey]["door"] = [];
                if (Tokens[Index + 1] !== undefined) {
                    Transitions[ObjectKey]["exits"][Tokens[Index + 1]] += 1;
                }
                Transitions[ObjectKey]["token"] = ObjectKey;
            } else { // Will run n times
                var CurrentSeenValue = Transitions[ObjectKey]["seen"];
                Transitions[ObjectKey]["seen"] = CurrentSeenValue + 1;
            }
        });

        Transitions.forEach(function(Transition) {
           var Exits =  Transitions[Transition]["exits"].sort();
           var Found = {};

           Exits.forEach(function(Exit) {

           });


        });

        return Transitions;
    }

}