// assets/js/tacmap.plugin.js
(function($) {
  $.fn.tacMap = function(options) {

    let scale = 1.0;
    const step = 0.1;

    const wrapper = $('#map-wrapper');

    let origin = { x: 0, y: 0 };
    let translate = { x: 0, y: 0 };
    let dragging = false;
    let dragStart = { x: 0, y: 0 };
    const maxScale = 3;
    const minScale = 1;

    const $map = $('#map-background');
    const $container = $map.parent();

    const settings = $.extend({
      mapContainer: $map,
      apiEndpoint: '/tacmap/data',
      markerClass: 'tacmap-marker'
    }, options);

    // Применение трансформации с анимацией
    function applyScale(animated = true) {
      if (animated) {
        $map.css('transition', 'transform 0.2s ease');
      } else {
        $map.css('transition', 'none');
      }
      $map.css('transform', `scale(${scale})`);
    }

    function clamp(val, min, max) {
      return Math.min(Math.max(val, min), max);
    }
    function clampTranslate() {
      const containerW = $container.width();
      const containerH = $container.height();
      const mapW = $map.width() * scale;
      const mapH = $map.height() * scale;

      const maxX = 0;
      const maxY = 0;
      const minX = containerW - mapW;
      const minY = containerH - mapH;

      // console.log(translate.x)
      // console.log(translate.y)
      translate.x = clamp(translate.x, minX, maxX);
      translate.y = clamp(translate.y, minY, maxY);
    }

    function clamp(value, min, max) {
      return Math.min(Math.max(value, min), max);
    }

    function updateTransform(animated = true) {
      if (animated) {
        $map.css('transition', 'transform 0.2s ease');
      } else {
        $map.css('transition', 'none');
      }
      clampTranslate();
      $map.css('transform', `translate(${translate.x}px, ${translate.y}px) scale(${scale})`);
    }

    $('#zoom-in').on('click', function () {
      scale = clamp(scale + 0.1, minScale, maxScale);
      updateTransform();
    });

    $('#zoom-out').on('click', function () {
      scale = clamp(scale - 0.1, minScale, maxScale);
      updateTransform();
    });

    // Масштабирование колесом под курсором
    $(wrapper).on('mousewheel', function (e) {
      
      e.preventDefault();
      
      const delta = e.originalEvent.deltaY < 0 ? 0.1 : -0.1;
      const newScale = clamp(scale + delta, minScale, maxScale);

      const rect = $map[0].getBoundingClientRect();
      const offsetX = e.originalEvent.clientX - rect.left;
      const offsetY = e.originalEvent.clientY - rect.top;

      const ratio = newScale / scale;

      translate.x = translate.x - (offsetX * (ratio - 1));
      translate.y = translate.y - (offsetY * (ratio - 1));
      scale = newScale;

      $(this).MapNav('fixGrid',translate.x,translate.y,scale,'mousewheel')

      updateTransform();
    });

    function init() {

      console.log('TACMap initialized');
      fetchData();
    }

    function fetchData() {
      $.ajax({
        url: settings.apiEndpoint,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          renderMarkers(response);
        },
        error: function(err) {
          console.error('TACMap data fetch error:', err);
        }
      });
    }

    function renderMarkers(markers) {
      markers.forEach(function(marker) {
        const $el = $('<div></div>')
          .addClass(settings.markerClass)
          .css({
            position: 'absolute',
            top: marker.y + 'px',
            left: marker.x + 'px'
          })
          .attr('title', marker.label)
          .append('<i class="fas fa-map-marker-alt"></i>');

        $map.append($el);
      });
    }

    const api = {
      highlightSector: function(id) {
        $('.' + settings.markerClass).removeClass('highlight');
        $('#' + id).addClass('highlight');
      },
      addMarker: function(data) {
        console.log('Adding marker:', data);
      },
      reload: fetchData
    };

    init();
    return api;
  };
})(jQuery);
