(function ($) {


  const map = $(this);
  const marker_lr = $('#layers_of_marker');
  const partion_lr = $('#layers_of_map_part');
  
  let marker = [];
  let rect = 0;
  let sync_marker = [];
  // const offset = 0;
  let x = 0;
  let y = 0;

  var posCur = [];

  var methods = {

    init: function(options) {

      const settings = $.extend({
        storageKey: 'markerMap'
      }, options);

      $(this).markerMap('loadMerker');

    },

    loadMerker: function(){

    },

    ping: function (){
      alert('pong')
    },

    unLock: function(){
      $('#layers_of_marker').css('pointer-events','none')
      $(marker_lr).attr('data-lock','0')
    },

    Lock: function(){
      $('#layers_of_marker').css('pointer-events','auto')
      $(marker_lr).attr('data-lock','1')
    },

    isLock: function(){
      lock_attr = $(marker_lr).attr('data-lock')
      return (typeof(lock_attr) != 'undefined' || lock_attr === '0')?true:false;
    },

    newMarker: function( event ){
      console.log($(this).markerMap('isLock'))
      if($(this).markerMap('isLock') === false && x > 0 && y > 0){
        const new_marker = document.createElement("div");
        new_marker.style.position = "absolute";
        new_marker.style.left = x+'px';
        new_marker.style.top = y+'px';
        new_marker.style.opacity = 0;
        new_marker.classList.add('item_marker');
        new_marker.innerHTML = '🔵'
        $(new_marker).fadeTo({'opacity':'1'},0);
        $(marker_lr).prepend(new_marker);
        $(new_marker).fadeTo({'opacity':'1'},2000);
        $(this).markerMap('createFormMarker',new_marker)
        $(this).markerMap('Lock')
      }
    },

    createFormMarker: function( inc_box ){

      
      const form = document.createElement("form");
      form.setAttribute('class','new_marker prevEvent')
      form.setAttribute('name','new_marker')
      form.setAttribute('id','new_marker')

      const input = document.createElement("input");
      input.setAttribute('type','text')
      input.setAttribute('class','name_new_marker')
      input.setAttribute('onblur','$(this).markerMap("fucusOutMark")')
      input.setAttribute('name','name_new_marker')
      input.setAttribute('id','name_new_marker')

      const save_mark = document.createElement("div");
      save_mark.setAttribute('class','save_mark')
      save_mark.setAttribute('id','save_mark')
      save_mark.innerHTML = '✅'

      const remove_mark = document.createElement("div");
      remove_mark.setAttribute('class','remove_mark')
      remove_mark.setAttribute('id','remove_mark')
      remove_mark.innerHTML = '❌'


        form.prepend(input);
        form.prepend(save_mark);
        form.prepend(remove_mark);

      $(inc_box).prepend(form);
      $('#name_new_marker').focus()
    },

    fucusOutMark: function ( mark ){
      // console.log(this)
      if(confirm('Сохранить метку?')){

      }else{
        this.parent('#new_marker').parent('.item_marker').remove()
        $(this).markerMap('unLock')
      }
    },

    setPositionCursor: function(pos) {
      console.log(pos)
    },
    
    mousemove: function(event_prt) {
      const layersDiv = document.getElementById('layers_of_map_part');
      layersDiv.addEventListener('mousemove', function(event_map) {
        const rects = this.getClientRects();
        if (rects.length > 0) {
          rect = posCur.rect = rects[0];
          x = posCur.x = event_prt.clientX - posCur.rect.left - 20;
          y = posCur.y = event_prt.clientY - posCur.rect.top - 20;
        }
      });
    }

  };

  $.fn.markerMap = function( method ) {
    
    // логика вызова метода
    if ( methods[method] ) {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Метод с именем ' +  method + ' не существует для jQuery.markerMap' );
    } 
  };

})(jQuery);