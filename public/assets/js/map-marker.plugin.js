(function ($) {


  const map = $(this);
  const marker_lr = $('#layers_of_marker');
  const partion_lr = $('#layers_of_map_part');
  const settings = {storageKey: 'markerMap'};

  let type_init = false;
  let marker = [];
  let rect = 0;
  let sync_marker = [];
  // const offset = 0;
  let x = 0;
  let y = 0;
  let init_marker = {
    new:{
      x:0,
      y:0,
      input_class:'name_new_marker',
      value: 'Новая метка',  
    },
    load: {
      x:0,
      y:0,
      input_class:'name_load_marker',
      value: '',
    }
  };

  var posCur = [];

  var methods = {

    init: function(options) {
      $(this).markerMap('loadStore')
      $(this).markerMap('syncToServer',false)
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
      var owner = arguments;
      // var inObj = this;
      $.each(marker, function(keys, current_mark) {
        init_marker.load.x = current_mark.posX;
        init_marker.load.y = current_mark.posY;
        init_marker.load.value = current_mark.marker;
        $(this).markerMap(
            'cenvasMarker',
            owner,
            'render',
            current_mark
            )
      });
      $(this).markerMap('unLock')
    },

    cenvasMarker: function( event, func, current_mark = false ){
      
      if(typeof(event.callee) === 'function'){
        type_init = 'load';
      }else{
        type_init = 'new';
      }
      
      if(($(this).markerMap('isLock') === false && init_marker[type_init].x > 0 && init_marker[type_init].y > 0)){
        
        transform_map = $('#map-wrapper').find('#map-background').css('transform');
        toScale = 0;
        var matrix = transform_map.replace(/[^0-9\-.,]/g, '').split(',');
          if(matrix[0] > 0 && matrix[3] > 0 && (matrix[3] === matrix[0]))
          {
            toScale = parseFloat(matrix[0]);
          }

        const new_marker = document.createElement("div");
        new_marker.style.position = "absolute";

        var hide = false;
        if(current_mark != false){
          if(current_mark.scale > 0){
            $(new_marker).attr('scale',current_mark.scale)
            new_marker.style.opacity = 0;
            hide = true;
          }else{
            // $(new_marker).attr('scale',false)
          }
        }else{
          // $(new_marker).attr('scale',false)
        }

        new_marker.style.left = init_marker[type_init].x+'px';
        new_marker.style.top = init_marker[type_init].y+'px';
        
        new_marker.style.opacity = 0;
        new_marker.classList.add('item_marker');
        if(type_init === 'load'){
          new_marker.classList.add('item_load');
        }
        if(hide === false){
          var HInner = '🔴';
        }else{
          var HInner = '🔵';
        }
        if(type_init === 'new'){
          var HInner = '🎯';
        }
        new_marker.innerHTML = HInner

        
        
        if(hide === false){
          $(new_marker).fadeTo({'opacity':'1'},0);
        }

        $(marker_lr).prepend(new_marker);

        if(hide === false){
          $(new_marker).fadeTo({'opacity':'1'},2000);
        }
        
        $(this).markerMap('createFormMarker',new_marker)

        if(type_init === 'new'){
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
            input.setAttribute('class', init_marker[type_init].input_class)
            // input.setAttribute('onblur','$(this).markerMap("fucusOutMark")')
            input.setAttribute('name',  init_marker[type_init].input_class)
            input.setAttribute('id',    init_marker[type_init].input_class)
            input.setAttribute('value', init_marker[type_init].value)

      const save_mark = document.createElement("div");
            save_mark.setAttribute('class','save_mark')
            save_mark.setAttribute('id','save_mark')
            save_mark.setAttribute('onclick','$(this).markerMap("saveMerker")')
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
      // $.confirm({
      //     title: 'Внимание!',
      //     theme: 'supervan',
      //     animation: 'zoom',
      //     closeAnimation: 'scale',
      //     animationBounce: 1.5,
      //     animationSpeed: 2000,
      //     content: 'Сохранить маркер?',
      //     buttons: {
      //         Да: function () {
      //             var save = $(obj).markerMap('saveMerker')
      //             if(save.result === true){
      //               $.alert('Сохранён');
      //             }else{
      //               $.alert({
      //                   theme: 'dark',
      //                   title: 'Внимание!',
      //                   content: save.text,
      //               });
      //               // $(obj).addClass('error')
      //             }
      //         },
      //         Нет: function () {
      //             $(obj).parent('#new_marker').parent('.item_marker').remove();
      //             $(obj).markerMap('unLock');
      //             $.alert({title: 'Внимание!',theme: 'supervan',content:'Маркер будет удален'});
      //         },
      //     }
      // });
    },

    saveMerker: function ()
    {
      console.log('Save Marker')
      var res = [];
      res.result = false;
      res.text = '';

      if($(this).siblings('.name_new_marker').val().length > 1){

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
          date: Date.now(),
          marker: document.getElementById("name_new_marker").value,
          posX: parseFloat($(this).parent('form').parent('.item_marker').css('left')),
          posY: parseFloat($(this).parent('form').parent('.item_marker').css('top')),
          scale: toScale,
          _synced: false,
        }

        marker.unshift(new_mark);
        $(this).markerMap('saveStore');
        res.text = 'Метка сохранена.';
      }else{ 
        res.text = 'Имя метки не заданно.';
      }
      
      return res;
    },

    reloadMerker: function() {  
      $('#layers_of_marker').empty()
      $(this).markerMap('unLock')
      $(this).markerMap('loadStore')
    },

    saveStore: function() {
      console.log(marker)
      localStorage.setItem(settings.storageKey, JSON.stringify(marker));
      $(this).markerMap('reloadMerker')
    },

    syncToServer: function( sync = false ) {
      var status = (marker.length > 0 && sync === false)?0:1;
      const unsyncedMarker = marker.filter(marker => !marker._synced);

      if (unsyncedMarker.length === 0 && status === 0) return;

      $.ajax({
        url: '/marker/sync/'+status,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(unsyncedMarker),
        success: function (response) {
          // Помечаем как синхронизированные
          unsyncedMarker.forEach(t => t._synced = true);
          $(this).markerMap('saveStore')
          if(status === 0){
            console.log(`[Sync] Отправлено: ${unsyncedMarker.length} задач`);
          }else{
            if(response.upData.length > 0){
              marker = []
              localStorage.setItem(settings.storageKey, JSON.stringify(marker))
            }
            
            $.each(response.upData, function(_,resMarker) {
              var inMark = JSON.parse(resMarker)
              const synMark = {
                date: inMark.date,
                id: inMark.id,
                map: inMark.map,
                marker: inMark.marker,
                posX: inMark.posX,
                posY: inMark.posY,
                scale: inMark.scale,
                _synced: true,
              };
              marker.unshift(synMark);
              $(this).markerMap('saveStore')
            });

          }
        },
        error: function (xhr) {
          console.error('[Sync] Ошибка при синхронизации:', xhr.responseText);
        }
      });

    },

    loadStore: function() {
      const stored = localStorage.getItem(settings.storageKey);
      marker = stored ? JSON.parse(stored) : [];
      console.log('init loadStore')
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
          init_marker.new.x = posCur.x = event_prt.clientX - posCur.rect.left - 20;
          init_marker.new.y = posCur.y = event_prt.clientY - posCur.rect.top - 20;
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