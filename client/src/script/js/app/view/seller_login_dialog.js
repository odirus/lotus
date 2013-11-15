define([

    "zepto",
    "backbone",

    "text!tpl/dialog/seller_login.mustache"

] , function(

    $,
    Backbone , 

    sellerLoginDialogTpl
) {
    "use strict";

    var SellerLoginDialogView = Backbone.View.extend({

        tpl: sellerLoginDialogTpl,

        events: {
            "click .close": "_closeDialog",
            "click .submit": "_doLogin"
        },

        initialize: function() {
            _.bindAll( this , "_closeDialog" , "showDialog" , "render" );

            this.render();
        },

        _doLogin: function() {
            alert( "login now" )
        },

        _closeDialog: function() {
            this.$el.hide();
        },

        showDialog: function() {
            this.$el.show();
        },

        render: function() {
            this.$el.html( this.tpl );

            $( "body" ).append( this.$el );
        }
    });

    return SellerLoginDialogView;
});