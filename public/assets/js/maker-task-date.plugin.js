(function($){
	//наястройка по умолчанию
	var defaults = {
		format: 'YYYY-MM-DD', // можно поменять формат вывода даты
		startDate: null, // начальная дата; если null, берет текущую
		bind: false,
		container: null // селектор элемента-контейнера, куда рендерить плагин. Если не указан, создаёт рядом с элементом
	};

	// вспомогательные функции
	function pad(n){ 
		return n < 10 ? '0' + n : '' + n; 
	}

	function formatDate(d, fmt){
		// простейший формат YYYY-MM-DD; можно расширить под moment-like
		var y = d.getFullYear();
		var m = pad(d.getMonth() + 1);
		var day = pad(d.getDate());
		// поддержка минимального формата
		if(fmt === 'YYYY-MM-DD') return y + '-' + m + '-' + day;
		// альтернативный вариант: DD.MM.YYYY
		if(fmt === 'DD.MM.YYYY') return day + '.' + m + '.' + y;
		// дефолт
		return y + '-' + m + '-' + day;
	}

	// плагин
	$.fn.inoutDatePicker = function(options){
			var $container = this;
			// если элемент не найден, попробуем создать
			if (!$container || $container.length === 0) {
				return this;
		}

		var opts = $.extend(true, {}, defaults, options);

		// определить контейнер для виджета
		var $wrap = $(opts.container || $container);

		// текущая дата как начальная
		var current = opts.startDate ? new Date(opts.startDate) : new Date();

		// нормализация даты без времени
		function normalizeDate(d){
		  var nd = new Date(d);
		  nd.setHours(0,0,0,0);
		  return nd;
		}

		doNormal = current;
		current = normalizeDate(current);

		// рендер визуального элемента
		function render(){
		  var display = formatDate(current, opts.format);

		  // уникальная структура плагина
		  var html = ''
		    + '<div class="inout-date-picker btn-group" role="group" aria-label="Inout date picker" style="display:inline-flex;align-items:center;gap:6px">'
		    + '<button type="button" class="btn btn-dark btn-sm inout-prev" aria-label="Предыдущая дата" title="Предыдущая"><</button>'
		    + '<span id="toDate" class="inout-date-display btn btn-outline-dark btn-sm white_strip" style="pointer-events:none;user-select:none;padding:6px 12px">' + display + '</span>'
		    + '<button type="button" class="btn btn-dark btn-sm inout-next" aria-label="Следующая дата" title="Следующая">></button>'
		    + '</div>';

		  $wrap.empty().append(html);

		  // обработчики
		  $wrap.find('.inout-prev').off('click').on('click', function(){
		    current.setDate(current.getDate() - 1);
		    render();
		  });
		  $wrap.find('.inout-next').off('click').on('click', function(){
		    current.setDate(current.getDate() + 1);
		    render();
		  });
		  var toDate = $('#toDate').html(); 
		  var tabBox = $wrap.parent();
		  // console.log(options)
		  // console.log($(tabBox).click())
		}
		// первый рендер
		render();

		// метод обновления внешнего доступа
		this.updateDate = function(newDate){
		  current = normalizeDate(newDate);
		  render();
		};

		// метод возвращает текущую дату
		this.getDate = function(){
		  return new Date(current);
		};

		// вернуть jQuery объект для цепочек
		return this;
	};
})(jQuery);