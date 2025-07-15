/*!
 * jQuery TopPanel (v1.0)
 * Автор: GPT-4
 */
(function($){
  $.fn.topControlPanel = function(options){
    // default settings
    const settings = $.extend({
      // иконки Bootstrap и/или FontAwesome, можно изменить на SVG/YandexUI если нужно
      buttons: [
        { id:'tasks',    text: 'Задачи',   icon: 'fas fa-check-square' },
        { id:'maps',     text: 'Карты',    icon: 'fas fa-map' },
        { id:'notes',    text: 'Заметки',  icon: 'fas fa-sticky-note' },
        { id:'debug',    text: 'Отладчик', icon: 'fas fa-bug' },
        { id:'wicker',   text: 'Викер',    icon: 'fas fa-shield-alt' },
        { id:'parser',   text: 'Парсер',   icon: 'fas fa-code' },
        { id:'disk',     text: 'Диск',     icon: 'fas fa-hdd' }
      ],
      classPanel:   'topcp-navbar', // кастомный класс панели
      classButton:  'topcp-btn',    // кастомный класс для кнопок
      onButtonClick: null           // function(id, e)
    }, options);

    // dark theme css если нужно быстро -- P.S. можете стилизовать глобально под YandexUI
    if(!$('#topcp-panel-style').length) {
      $('<style>')
        .attr('id','topcp-panel-style')
        .prop('type','text/css')
        .text(`
        .topcp-navbar {
          background: #222C39 !important;
          box-shadow: 0 2px 12px rgba(10,17,27,.19);
          color: #eee !important;
          position: fixed !important;
          t1op: 0; left: 0; right: 0; bottom: 0px;
          z-index: 1045;
          padding: 0.25rem .5rem;
          user-select: none;
        }
        .topcp-navbar .topcp-btn {
          background: transparent !important;
          color: #eee !important;
          border: none;
          margin: 0 .3em;
          min-width: 42px;
          transition: background .15s,color .15s;
          font-size: 1.1em;
          border-radius: .4rem;
          padding: .45em .7em;
          outline: none;
        }
        .topcp-navbar .topcp-btn:active, 
        .topcp-navbar .topcp-btn:focus, 
        .topcp-navbar .topcp-btn:hover {
          background: #364154 !important;
          color: #fff !important;
        }
        .topcp-navbar .topcp-btn .topcp-label { 
          display: none; 
        }
        @media (min-width: 576px) {
          .topcp-navbar .topcp-btn .topcp-label {
            display: inline;
            margin-left: .4em;
          }
        }
        .topcp-navbar .topcp-btn:focus { box-shadow: 0 0 0 .15rem #007aff6a; }
        .topcp-navbar::-webkit-scrollbar { display:none; }
        body.topcp-has-topbar { padding-top:56px !important;}
      `).appendTo('head');
    }

    // Для каждого элемента инициализируем панель
    return this.each(function(){

      let $container = $(this);

      // создаём элемент панели через createElement
      let nav = document.createElement('nav');
      nav.className = `topcp-navbar navbar navbar-expand d-flex justify-content-start align-items-center flex-row flex-nowrap`;
      nav.setAttribute('role','navigation');

      // внутренний контейнер
      let inner = document.createElement('div');
      inner.className = "d-flex flex-row flex-nowrap w-100 align-items-center overflow-auto";
      
      // клонируем структуру кнопок
      settings.buttons.forEach(btn=>{
        let button = document.createElement('button');
        button.type = 'button';
        button.className = 'topcp-btn btn btn-dark d-flex align-items-center flex-shrink-0';
        button.dataset.id = btn.id;

        // Иконка (можно заменить на img/svg или ikons Yandex)
        if(btn.icon){
          let ico = document.createElement('i');
          btn.icon.split(' ').forEach(c=>ico.classList.add(c));
          button.appendChild(ico);
        }

        // Текст
        let span = document.createElement('span');
        span.className = 'topcp-label ml-2';
        span.textContent = btn.text;
        button.appendChild(span);

        // Навешиваем событие
        button.addEventListener('click', function(e){
          if(typeof settings.onButtonClick === 'function'){
            settings.onButtonClick(btn.id, e, button);
          }else{
            // По-умолчанию подсветка
            $(this).siblings().removeClass('active');
            this.classList.add('active');
          }
        });
        inner.appendChild(button);
      });
      nav.appendChild(inner);

      // Добавляем панель в <body>
      $('body').prepend(nav);

      // Добавляем внешний отступ body (чтобы контент не прятался)
      $('body').addClass('topcp-has-topbar');

      // Оформление контейнера с помощью wrapInner (оборачиваем содержимое пользователя)
      $container.wrapInner('<div class="topcp-content-wrap"></div>');

      // Адаптация панели при изменении размеров (на мобильных скроллируется)
      function adaptPanelHeight(){
        let h = $(nav).outerHeight();
        // задаём отступ для body, чтобы не перекрывало контент
        $('body').css('padding-top', h + 'px');
      }
      adaptPanelHeight();
      $(window).on('resize.topcp', adaptPanelHeight);
    });
  };
})(jQuery);
