(function ($) {
  $.fn.markerMap = function (options) {
    const settings = $.extend({
      storageKey: 'markerMap'
    }, options);


    const $map = $(this);
    const $marker_lr = $('#layers_of_marker');
    
    let marker = [];
    let rect = 0;
    let sync_marker = [];
    // const offset = 0;
    let x = 0;
    let y = 0;

    function loadMerker(){

    }

    function unLock(){
      $('#layers_of_marker').css('pointer-events','none')
      $marker_lr.attr('data-lock','0')
    }


    function Lock(){
      $('#layers_of_marker').css('pointer-events','auto')
      $marker_lr.attr('data-lock','1')
    }

    function isLock(){
      lock_attr = $marker_lr.attr('data-lock')
      return (typeof(lock_attr) != 'undefined' || lock_attr === '0')?true:false;
    }

    function newMarker( event ){
      if(isLock() === false){
        const new_marker = document.createElement("div");
        new_marker.style.position = "absolute";
        new_marker.style.left = x+'px';
        new_marker.style.top = y+'px';
        new_marker.style.opacity = 0;
        new_marker.classList.add('item_marker');
        $(new_marker).fadeTo({'opacity':'1'},0);
        $marker_lr.prepend(new_marker);
        $(new_marker).fadeTo({'opacity':'1'},2000);
        createFormMarker(new_marker)
        Lock()
      }
    }

    function createFormMarker( inc_box ){
      var form = '<form name="new_marker" id="new_marker" class="new_marker"><input id="name_new_marker" name="name_new_marker" type="text"></form>';
      $(inc_box).prepend(form);
    }

    function init() {
      loadMerker();
    }
    
    $marker_lr.on('mousemove', function(event) {
      const layersDiv = document.getElementById('layers_of_map_part');
      layersDiv.addEventListener('mousemove', function(event) {
        const rects = this.getClientRects();
        if (rects.length > 0) {
          rect = rects[0];
          x = event.clientX - rect.left-20;
          y = event.clientY - rect.top-20;
        }
      });
    });

    $marker_lr.on('dblclick', function(event) {
      newMarker(event);
    });

    init();
    return this;
  };
})(jQuery);