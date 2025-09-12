(function ($) {


  const container = $('#taskContainer');

  var posCur = [];

  var methods = {

    slideGo: function( element ){
      $(".SlideView").click();
    },

    slideTo: function( element ){
      $(".SlideView").removeClass("SlideView");
      $(element).addClass('SlideView')
    }

  };

  $.fn.slideTask = function( method ) {
    
    // логика вызова метода
    if ( methods[method] ) {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Метод с именем ' +  method + ' не существует для jQuery.slideTask' );
    } 
  };

})(jQuery);