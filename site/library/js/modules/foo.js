define(
    [
        'jquery'
    ],
    function(
        $
    ){

        function say(msg){

            if (window.console && window.console.log){

                console.log(msg);
            }
        }

        return {

            say: say
        };
    }
);