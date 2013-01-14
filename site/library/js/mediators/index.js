define(
    [
        'jquery',
        'modules/foo'
    ],  
    function( 
        $,
        foo
    ) {
            
        var Mediator = {

            init: function (){

                var self = this;

                $(function(){
                    self.onDomReady();
                });
            },

            onDomReady: function(){

                var self = this;

                foo.say('Hello world');
            }

        };

        Mediator.init();
    }
);
