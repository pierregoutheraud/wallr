requirejs.config({
    baseUrl: './js',
    paths: {
        jquery : 'libs/jquery/jquery-2.0.3.min',
        
        imagesLoaded  : 'libs/imagesLoaded/imagesLoaded-require',
        
        masonry  : 'libs/masonry/masonry.pkgd-hackRequire',
//        wall : './wall/wall',

        underscore  : 'libs/underscore/underscore-min',
        backbone : 'libs/backbone/backbone',
        
        nprogress : 'libs/nprogress/nprogress'
    },
    shim: {
        
        jquery : {
            exports : '$'
        },
        
        imagesLoaded : {
            deps : ['jquery']
        },
        
        masonry : ['jquery'],
//        wall : ['masonry', 'imagesLoaded'],
        
        backbone : {
            deps : ['jquery', 'underscore'],
            exports : 'Backbone'
        },
        
        underscore : {
            exports : '_'
        },
        
        nprogress : {
            deps : ['jquery'],
            exports : 'NProgress'
        }
        
    }
});