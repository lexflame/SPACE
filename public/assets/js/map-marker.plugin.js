(function ($) {


  const map = $(this);
  const marker_lr = $('#layers_of_marker');
  const partion_lr = $('#layers_of_map_part');
  const settings = {storageKey: 'markerMap'};

  
  let marker = [];
  let rect = 0;
  let sync_marker = [];
  // const offset = 0;
  let x = 0;
  let y = 0;

  var posCur = [];

  var methods = {

    init: function(options) {
      $(this).markerMap('loadStore')
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
      lock_attr = parseInt($(marker_lr).attr('data-lock')) 
      return (lock_attr === 0)?false:true;
    },

    renderMark: function(){
      $.each(marker, function(keys, current_mark) {
        position_x = current_mark.posX;
        position_y = current_mark.posY;
        $(this).markerMap(
            'newMarker',
            false,
            'render',
            position_x,
            position_y
            )
      });
    },

    newMarker: function( event, owner = 'user', cur_x = false, cur_y = false ){

      if(
        ($(this).markerMap('isLock') === false && x > 0 && y > 0)
        || owner === 'render' 
        ){
        
        const new_marker = document.createElement("div");
        new_marker.style.position = "absolute";

        if(owner === 'render'){
          set_x = cur_x;
          set_y = cur_y;
        }else{
          set_x = x;
          set_y = y;
        }

        new_marker.style.left = set_x+'px';
        new_marker.style.top = set_y+'px';
        
        new_marker.style.opacity = 0;
        new_marker.classList.add('item_marker');
        new_marker.innerHTML = '🔵'
        
        $(new_marker).fadeTo({'opacity':'1'},0);
        $(marker_lr).prepend(new_marker);
        $(new_marker).fadeTo({'opacity':'1'},2000);
        
        $(this).markerMap('createFormMarker',new_marker,owner)

        if(owner === 'user'){
          $(this).markerMap('Lock')
        }

      }
    },

    createFormMarker: function( inc_box, owner ){

      const form = document.createElement("form");
      form.setAttribute('class','new_marker prevEvent')
      form.setAttribute('name','new_marker')
      form.setAttribute('id','new_marker')

      const input = document.createElement("input");
      input.setAttribute('type','text')
      input.setAttribute('class','name_new_marker')
      // input.setAttribute('onblur','$(this).markerMap("fucusOutMark")')
      input.setAttribute('name','name_new_marker')
      input.setAttribute('id','name_new_marker')

      const save_mark = document.createElement("div");
      save_mark.setAttribute('class','save_mark')
      save_mark.setAttribute('id','save_mark')
      save_mark.setAttribute('onclick','$(this).markerMap("saveMark")')
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
      var obj = this;
      $.confirm({
          title: 'Внимание!',
          theme: 'supervan',
          animation: 'zoom',
          closeAnimation: 'scale',
          animationBounce: 1.5,
          animationSpeed: 2000,
          content: 'Сохранить маркер?',
          buttons: {
              Да: function () {
                  var save = $(obj).markerMap('saveMerker')
                  if(save.result === true){
                    $.alert('Сохранён');
                  }else{
                    $.alert({
                        theme: 'dark',
                        title: 'Внимание!',
                        content: save.text,
                    });
                    // $(obj).addClass('error')
                  }
              },
              Нет: function () {
                  $(obj).parent('#new_marker').parent('.item_marker').remove();
                  $(obj).markerMap('unLock');
                  $.alert('Маркер будет удален');
              },
          }
      });
    },

    saveMerker: function ()
    {
      var res = [];
      res.result = false;
      res.text = '';

      if($(this).val().length > 1){

        transform_map = $('#map-wrapper').find('#map-background').css('transform');
        var matrix = transform_map.replace(/[^0-9\-.,]/g, '').split(',');
        var toScale = 0;
          if(matrix[0] > 0 && matrix[3] > 0 && (matrix[3] === matrix[0]))
          {
            toScale = parseFloat(matrix[0]);
          }else{

          }

        new_mark = {
          id: Date.now(), 
          map: $('#map-wrapper').data('id'),
          marker: $('#name_new_marker').val(),
          posX: parseFloat($(this).parent('form').parent('.item_marker').css('left')),
          posY: parseFloat($(this).parent('form').parent('.item_marker').css('top')),
          scale: toScale,
        }

        marker.unshift(new_mark);
        $(this).markerMap('saveStore');
        res.text = 'Метка сохранена.';
      }else{ 
        res.text = 'Имя метки не заданно.';
      }
      return res;
    },

    saveStore: function() {
      console.log(marker)
      localStorage.setItem(settings.storageKey, JSON.stringify(marker));
    },


    loadStore: function() {
      const stored = localStorage.getItem(settings.storageKey);
      marker = stored ? JSON.parse(stored) : [];
      $(this).markerMap('renderMark')
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