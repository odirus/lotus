define([
    'underscore',
    'backbone',
    'async',

    'utilities/auth',
    'utilities/common',

    'v/page/home',
    'v/page/seller_signup',
    'v/page/seller_login',
    'v/page/shop',
    'v/page/product_detail',
    'v/page/search_result',
    'v/page/product_list_by_class',
    'v/page/not_found'

] , function(
    _,
    Backbone,
    async,

    auth,
    common,

    HomePageView,
    SellerSignupView,
    SellerLoginView,
    ShopPageView,
    ProductDetailPageView,
    SearchResultPageView,
    ProductListByClassView,
    NotFoundPageView
) {
    'use strict';

    var Routes = Backbone.Router.extend({

        routes: {
            '': '_showMainPage',
            'main': '_showMainPage',
            'seller_signup': '_showSellerSignupPage',
            'seller_login': '_showSellerLoginPage',
            'seller_logout': '_sellerLogout',
            'product_detail/:productId': '_showProductDetailPage',

            'shop/:shopId(/:currentPage)': '_showShop',

            'search_result(/:q/:p)(/)': '_showSearchResult',

            'product_list_by_class/:classA/:classB(/)': '_showProductListByClass',
            'page_not_found': '_showNotFoundPage',

            //default page
            '*path': '_showNotFoundPage'
        },

        initialize: function() {
        //{{{
            _.bindAll(
                this,

                '_showMainPage',
                '_showSellerSignupPage',
                '_showSellerLoginPage',
                '_sellerLogout',
                '_showProductDetailPage',
                '_showShop',
                '_showSearchResult'
            );
        },//}}}

        _showMainPage: function() {
        //{{{
            new HomePageView();
        },//}}}

        _showSellerSignupPage: function() {
        //{{{
            new SellerSignupView();
        },//}}}

        _showSellerLoginPage: function() {
        //{{{
            new SellerLoginView();
        },//}}}

        _sellerLogout: function() {
        //{{{
            auth.doLogout( function() {
                window.routes.navigate( 'main' , {trigger: true} );
            });
        },//}}}

        _showShop: function( shopId , currentPage ) {
        //{{{
            new ShopPageView({ shop_id: shopId , current_page: currentPage});
        },//}}}

        _showSearchResult: function( q ) {
        //{{{
            new SearchResultPageView({q: q});
        },//}}}

        _showProductDetailPage: function( productId ) {
        //{{{
            new ProductDetailPageView({ product_id: productId });
        },//}}}

        _showProductListByClass: function( classA , classB ) {
        //{{{
            new ProductListByClassView({
                class_a: classA,
                class_b: classB
            });
        },//}}}

        _showNotFoundPage: function() {
        //{{{
            new NotFoundPageView();
        }//}}}
    });

    return Routes;
});

