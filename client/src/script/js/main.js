require.config({

    baseUrl: "./src/script/" ,

    paths: {
        text: "third_party/text",

        zepto: "third_party/zepto" ,
        underscore: "third_party/underscore" ,
        backbone: "third_party/backbone",
        mustache: "third_party/mustache",
        async: "third_party/async",

        m: "js/app/model",
        v: "js/app/view",
        c: "js/app/collection",
        tpl: "js/app/tpl",

        routes: "js/routes",
        config: "js/app_config"
    },

    shim: {
        zepto: {
            exports: "$"
        } ,
        underscore: {
            exports: "_"
        } ,
        backbone: {
            deps: ["underscore", "zepto"],
            exports: "Backbone"
        }
    }
});

require(
[
    "zepto",

    "v/nav",

    "routes"
],
function(
    $,

    NavView,

    Routes
) {
    console.log( "init ok." );

    var navView = new NavView();

    var routes = new Routes();
    //window.routes = routes;
    Backbone.history.start({pushState: true});
});
