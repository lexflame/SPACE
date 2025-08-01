(function( $ ){

  var methods = {
    init : function( options ) { 
      // А ВОТ ЭТОТ
    },
    setWrapper : function( options ) { 
      var winWidth = $( 'body' ).width();
      var winHeight = $( 'body' ).height();
      var mapWidth = $('.layers_of_map_part').width();
      var mapHeight = $('.layers_of_map_part').height();
      var sliceLeft = (mapWidth - winWidth) + mapWidth;
      var posLeft = $('#map-wrapper').width() - winWidth;
      var posTop = $('#map-wrapper').height() - winHeight;
      var newWidth = $('#map-wrapper').width() + 43;
      var newHeight = $('#map-wrapper').height() + 2;
      $('#map-wrapper').css(
          {
            'top'   : '-'+posTop+'px',
            'left'  : '-'+posLeft+'px',
            'width' : newWidth+'px',
            'height': newHeight+'px',
          }
      );
    },
    fixGrid : function(paramx,paramy,position = false,event) {
      var arrVal = [paramx,paramy,position];

      if(position === false){
        position = -1;
      }
      
      $('#posx').html(paramx)
      $('#posy').html(paramy)
      $('#scale_pos').html(position)
      $('#event_pos').html(event)
      
      if(arrVal[0] < 0 || arrVal[1] < 0){
        var $grid = $('.minute-grid');

        var isPositionFixed = ($grid.css('position') == 'fixed');

        if (!isPositionFixed){
          $grid.css({'position': 'fixed', 'top': '0px'});
        }
        
        if (isPositionFixed){
          $grid.css({'position': 'absolute', 'top': '0px'});
        }
      }
    },
  };

  $.fn.MapNav = function( method ) {
    
    // логика вызова метода
    if ( methods[method] ) {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Метод с именем ' +  method + ' не существует для jQuery.MapNav' );
    } 
  };

})( jQuery );