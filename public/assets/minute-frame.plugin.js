(function($) {
  $.fn.minuteGrid = function(options) {
    const settings = $.extend({
      stepPx: 100, // шаг сетки (соответствует 1')
      showLabels: true,
      color: 'rgba(0,0,0,0.5)',
      labelColor: '#fff',
      zIndex: 5,
      scale: 1 // внешний масштаб (scale), если нужно
    }, options);

    const $map = $(this);
    let $grid = $('<div class="minute-grid"></div>').css({
      position: 'absolute',
      top: 0,
      left: 0,
      width: '100%',
      height: '100%',
      pointerEvents: 'none',
      zIndex: settings.zIndex
    });

    $map.append($grid);

    function renderGrid() {
      $grid.empty();

      const width = $map.width() * settings.scale;
      const height = $map.height() * settings.scale;
      const step = settings.stepPx * settings.scale;

      // Горизонтальные линии
      for (let y = 0; y <= height; y += step) {
        const $line = $('<div class="minute-line"></div>').css({
          position: 'absolute',
          top: y + 'px',
          left: 0,
          width: '100%',
          height: '1px',
          backgroundColor: settings.color
        });
        $grid.append($line);

        if (settings.showLabels) {
          const $label = $('<div class="minute-label"></div>').text(`${Math.round(y / step)}′`).css({
            position: 'absolute',
            top: y + 2 + 'px',
            left: '4px',
            fontSize: '14px',
            color: settings.labelColor,
            backgroundColor: 'rgba(0,0,0,0.4)',
            padding: '1px 3px',
            borderRadius: '3px'
          });
          $grid.append($label);
        }
      }

      // Вертикальные линии
      for (let x = 0; x <= width; x += step) {
        const $line = $('<div class="minute-line"></div>').css({
          position: 'absolute',
          top: 0,
          left: x + 'px',
          width: '1px',
          height: '100%',
          backgroundColor: settings.color
        });
        $grid.append($line);

        if (settings.showLabels) {
          const $label = $('<div class="minute-label"></div>').text(`${Math.round(x / step)}′`).css({
            position: 'absolute',
            top: '2px',
            left: x + 2 + 'px',
            fontSize: '14px',
            color: settings.labelColor,
            backgroundColor: 'rgba(0,0,0,0.4)',
            padding: '1px 3px',
            borderRadius: '3px'
          });
          $grid.append($label);
        }
      }
    }

    // Метод для обновления сетки при зуме или ресайзе
    this.updateMinuteGrid = function(newScale = 1) {
      settings.scale = newScale;
      renderGrid();
      $('#map-background')[0].updateMinuteGrid(scale);
    };

    renderGrid();
    return this;
  };
})(jQuery);
