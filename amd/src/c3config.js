define(['core/config'], function(config) {
    // We have no amd dependencies - but we setup the config for our (badly coded)
    // pseudo amd modules here.
    var d3url = config.wwwroot + '/report/graphic/lib/d3/d3.min';
    var c3url = config.wwwroot + '/report/graphic/lib/c3/c3.min';

    requirejs.config({
        paths: {
            'd3': d3url,
            'c3': c3url
        },
        shim: {
            'd3': {
                exports: 'd3'
            },
            'c3': {
                deps: ['d3'],
                exports: 'c3'
            }
        }
    });

    // It's possible to inject the CSS here too.

    return 'Do not depend on this module directly - use report/graphic/c3loader instead.';
});