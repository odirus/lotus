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

        utilities: "js/utilities",
        routes: "js/routes",
        config: "js/config",
        global_events: "js/global_events"
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
    "underscore",

    "v/nav",
    "v/footer",

    "routes",

    "utilities/common",

    "global_events"
],
function(
    $,
    _,

    NavView,
    FooterView,

    Routes,

    common,

    GlobalEvents
) {
    "use strict";

    var navView = new NavView();
    var footerView = new FooterView();

    window.e = new GlobalEvents({ nav_view: navView }).e;

    //在用户刷新页面之后根据当前的状态初始化页面
    //is login ?
    if( common.getSessionId() !== null ) {
        // has logged in
        window.e.trigger( "login_ok" );
    }

    var routes = new Routes();
    window.routes = routes;
    Backbone.history.start();
});

