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

    function newMarker( event ){
      const new_marker = document.createElement("div");
      new_marker.style.position = "absolute";
      new_marker.style.left = x+'px';
      new_marker.style.top = y+'px';
      new_marker.style.opacity = 0;
      new_marker.classList.add('item_marker');
      $(new_marker).fadeTo({'opacity':'1'},0);
      $marker_lr.prepend(new_marker);
      $(new_marker).fadeTo({'opacity':'1'},2000);
    }

    function init() {
      loadMerker();
    }
    
    $marker_lr.on('mousemove', function(event) {
      const layersDiv = document.getElementById('layers_of_marker');
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