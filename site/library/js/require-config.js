/**
 * Config options at: http://requirejs.org/docs/api.html#config
 */
require.config({
    shim: {

        'bootstrap-affix': {
            deps: ['jquery']
        },
        'bootstrap-typeahead': {
            deps: ['jquery']
        }
    },

    paths: {

        // Plugins
        'text': 'plugins/text',
        'json': 'plugins/json',
        
        // jQuery
        'jquery': 'vendor/jquery',

        // Twitter Bootstrap
        'bootstrap-affix': 'vendor/bootstrap/bootstrap-affix',
        'bootstrap-typeahead': 'vendor/bootstrap/bootstrap-typeahead'
    },

    map: {
        
    }
});
