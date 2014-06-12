define([
  'jquery',
  'imagesLoaded',
  'masonry',
  'nprogress'
], 
function($, imagesLoaded, masonry, NProgress)
{

    var photosWidth, columnWidth, nbPhotos, nbColumns, timeoutUpdateWall;
    var gutter = 20, minimumImageWidth = 500;
    
    var firstInit = true;
    var $wall = $('#wall');

    var init = function()
    {
        setColumnWidth();
        layout();
        
//        if( firstInit )
//        {
//            $wall.masonry( 'on', 'removeComplete', function( msnryInstance, removedItems )
//            {
//                console.log('REMOVE COMPLETE !!!');
//                $(removedItems[0]).remove();
//            });
//        }
        
        firstInit = false;
    };
    
    var initHome = function()
    {
        setColumnWidth();
        $wall.find('.image').width( columnWidth );
        
        $wall.imagesLoaded( function()
        {
            layout();
            $wall.addClass('visible');
            $('#logoSignin').fadeIn(1000);
        });
    };
    
    var setColumnWidth = function()
    {
        photosWidth = $wall.width();

        columnWidth = photosWidth;
        nbColumns = 1;
        while( columnWidth >= minimumImageWidth )
        {
            nbColumns++;

            // Enlever le padding
            photosWidth -= gutter;
            
            columnWidth = Math.floor(photosWidth/nbColumns); 
//            console.log( photosWidth + '/' + nbColumns ); console.log(columnWidth);
        }
        
        // Fix le bug quand les images ne prennent pas toutes les colonnes
        columnWidth--;
    };

    var update = function()
    {
        setColumnWidth();
        layout();

        //!\ IL FAUDRAIT CALL IMAGE LIST VIEW
        NProgress.done();
        $wall.addClass('visible');

//        $wall.masonry( 'option', { columnWidth: columnWidth });
//        $wall.masonry('reloadItems');
    };
    
    var reset = function()
    {
        if( typeof $wall === "undefined" )
            return false;
        else
        {
            $wall.masonry('destroy');
            return true; 
        }
            
    };
    
    $(window).resize(function() 
    {
        clearTimeout( timeoutUpdateWall );
        timeoutUpdateWall = setTimeout( function()
        {
            
            setColumnWidth();
            $wall.find('.image').width( columnWidth );
            layout();
            
        }, 100 ); 
    });
   
   var layout = function()
   {
        $wall.masonry({
            'isResizeBound' : false,
            'gutter' : gutter,
            itemSelector : '.image',
            columnWidth : columnWidth
        });
   };
   
    var add = function( $el )
    {
//        console.log('wall add');
        $el.addClass('hidden');
        $wall.append( $el );
        
        $el.imagesLoaded( function() 
        {
            $el.width( columnWidth );
            $wall.masonry('appended', $el );
            $el.removeClass('hidden');
        });
    };
   
    var remove = function( $el )
    {
//        console.log('wall removeItem');
//        console.log( $el );
//        $wall.masonry( 'remove', $el );
        $el.remove();
//        $wall.masonry('reloadItems');
        layout();
    };
        
    return {
        'init' : init,
        'initHome' : initHome,
        'update' : update,
        'reset' : reset,
        'setColumnWidth' : setColumnWidth,
        'add' : add,
        'remove' : remove
    };
    
});