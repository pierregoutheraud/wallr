define([
  'jquery',
  '../wall/wall',
  '../home/facebook/facebookConnect'
], 
function($, wall, facebookConnect){
    
  $(function() 
  {
      wall.initHome();
  });

    return true;
});