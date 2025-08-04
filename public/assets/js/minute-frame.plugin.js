(function($) {
  // Класс MinuteGrid
  class MinuteGrid {
    constructor($map, options) {
      this.$map = $map;
      this.settings = $.extend({
        stepPx: 100, // 1 минута = 100px
        showLabels: true,
        color: 'rgba(0,0,0,0.5)',
        labelColor: '#fff',
        zIndex: 5,
        scale: 1
      }, options);

      this.$grid = $('<div class="minute-grid"></div>').css({
        position: 'absolute',
        top: 0,
        left: 0,
        width: $('#map-wrapper').width()+'px',
        height: $('#map-wrapper').height()+'px',
        pointerEvents: 'none',
        zIndex: this.settings.zIndex
      });

      this.$map.css('position', 'relative'); // Чтобы grid позиционировался правильно
      this.$map.append(this.$grid);

      this.renderGrid();
    }

    renderGrid() {
      const { stepPx, showLabels, color, labelColor, scale } = this.settings;
      const width = $('#map-wrapper').width() * scale;
      const height = $('#map-wrapper').height() * scale;
      const step = stepPx * scale;

      this.$grid.empty();

      // Горизонтальные линии и подписи
      for (let y = 0; y <= height; y += step) {
        this.$grid.append($('<div class="minute-line"></div>').css({
          position: 'absolute',
          top: y + 'px',
          left: 0,
          width: '100%',
          height: '1px',
          backgroundColor: color
        }));

        if (showLabels) {
          this.$grid.append($('<div class="minute-label"></div>').text(`${Math.round(y / step)}′`).css({
            position: 'absolute',
            top: y + 2 + 'px',
            left: '4px',
            fontSize: '14px',
            color: labelColor,
            backgroundColor: 'rgba(0,0,0,0.4)',
            padding: '1px 3px',
            borderRadius: '3px'
          }));
          var yPosRuler = y + 10;
          $('.vertical_ruler').find('.line_marker').append($('<div class="minute-label"></div>').text(`${Math.round(y / step)}′`).css({
            position: 'absolute',
            top: yPosRuler + 'px',
            left: 0,
        }))
        }
      }

      // Вертикальные линии и подписи
      for (let x = 0; x <= width; x += step) {
        this.$grid.append($('<div class="minute-line"></div>').css({
          position: 'absolute',
          top: 0,
          left: x + 'px',
          width: '1px',
          height: '100%',
          backgroundColor: color
        }));

        if (showLabels) {
          this.$grid.append($('<div class="minute-label"></div>').text(`${Math.round(x / step)}′`).css({
            position: 'absolute',
            top: '2px',
            left: x + 2 + 'px',
            fontSize: '14px',
            color: labelColor,
            backgroundColor: 'rgba(0,0,0)',
            padding: '1px 3px',
            borderRadius: '3px'
          }));
          var xPosRuler = x + 10;
          $('.horizontal_ruler').find('.line_marker').append($('<div class="minute-label"></div>').text(`${Math.round(x / step)}′`).css({
            position: 'absolute',
            top: 0,
            left: xPosRuler + 'px',
        }))
        }
      }
    }

    update(newScale = 1) {
      this.settings.scale = newScale;
      this.renderGrid();
    }
  }

  // jQuery-плагин
  $.fn.minuteGrid = function(methodOrOptions, ...args) {
    return this.each(function() {
      const $this = $(this);
      let instance = $this.data('minuteGrid');

      if (!instance) {
        // инициализация
        if (typeof methodOrOptions === 'object' || !methodOrOptions) {
          instance = new MinuteGrid($this, methodOrOptions);
          $this.data('minuteGrid', instance);
        }
      } else {
        // вызов метода
        if (typeof methodOrOptions === 'string' && typeof instance[methodOrOptions] === 'function') {
          instance[methodOrOptions](...args);
        }
      }
    });
  };
})(jQuery);
