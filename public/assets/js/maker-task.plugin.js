(function ($) {
  $.fn.makerTask = function (options) {
    const settings = $.extend({
      storageKey: 'makerTasks'
    }, options);

    const $container = $(this);
    const $list = $('.task-list');
    let tasks = [];
    let sync_tasks = [];

    function saveTasks() {
      localStorage.setItem(settings.storageKey, JSON.stringify(tasks));
    }

    function loadTasks() {
      const stored = localStorage.getItem(settings.storageKey);
      tasks = stored ? JSON.parse(stored) : [];
    }

    function syncToServer( sync = false ) {
      var status = (tasks.length > 0 && sync === false)?0:1;
      const unsyncedTasks = tasks.filter(task => !task._synced);

      if (unsyncedTasks.length === 0 && status === 0) return;
      
      // console.log(JSON.stringify(unsyncedTasks))

      $.ajax({
        url: '/tasks/sync/'+status,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(unsyncedTasks),
        success: function (response) {
          // Помечаем как синхронизированные
          unsyncedTasks.forEach(t => t._synced = true);
          saveTasks();
          if(status === 0){
            console.log(`[Sync] Отправлено: ${unsyncedTasks.length} задач`);
          }else{
            if(response.upData.length > 0){
              tasks = []
              localStorage.setItem(settings.storageKey, JSON.stringify(tasks))
            }
            $.each(response.upData, function(_,task) {
              var inTask = JSON.parse(task)
              const title = inTask.title;
              const date = inTask.date;
              const priority = inTask.priority;
              const syntask = {
                id: inTask.id,
                title,
                date,
                priority,
                description: inTask.description,
                link: inTask.link,
                tag: inTask.tag,
                coords: inTask.coords,
                files: inTask.files,
                completed: inTask.completed,
                synced: inTask.synced,
                _synced: inTask._synced,
              };
              tasks.unshift(syntask);
              saveTasks();
            });
          }
        },
        error: function (xhr) {
          console.error('[Sync] Ошибка при синхронизации:', xhr.responseText);
        }
      });
      renderTasks();
      $('#makerTaskApp').fadeOut('slow')
        $('.today_btn').click();
      $('#makerTaskApp').fadeIn('slow')
    }

    function getCurrentTask( element ){
      return $(element).closest('.cardTask')
    }

    function TomorowToTask( element ){
      var res = isWeekendDate($(element).data('datetask'));
      var time = $(element).data('datetask').split('T')[1];
      var itemTaskDate = new Date($(element).data('datetask'))
      if(res.dayOfWeek + 1 > 5){
         itemTaskDate.setTime(itemTaskDate.getTime() + 24 * 3 * 60 * 60 * 1000);
      }else{
        itemTaskDate.setTime(itemTaskDate.getTime() + 24 * 60 * 60 * 1000);
      }
      var setDate = itemTaskDate.toISOString().slice(0, 10);
      var setFullDate = setDate+'T'+time;
      editTask(
          getCurrentTask($(element)),
          setFullDate,
          'date'
      );
    }

    function editTask( taskElement = false , value = false, field = false ){
      if(taskElement != false && value != false && field != false){
        let idEdit = $(taskElement).data('id');
        $.each(tasks, function(_, task) {
          if(task.id === idEdit){
            task[field] = value
          }
        });
      }
      saveTasks()
      renderTasks()
    }

    function normalizeDate(d){
      var nd = new Date(d);
      nd.setHours(0,0,0,0);
      return nd;
    }

    function filteredDate( item ){
      var itemDate = new Date(normalizeDate(item.date)).toDateString()
      var toDate = new Date(normalizeDate($('#toDate').html())).toDateString();
      if(itemDate === toDate){
        return item;
      }
    }

    function renderTasks(filter = 'all') {
      
      const list = $('.task-list');
      $list.empty();

      const now = new Date();
      const todayStr = now.toISOString().split('T')[0];



      let filtered = tasks.filter(t => {
        if (filter === 'today') return t.date.startsWith(todayStr) && !t.completed;
        if (filter === 'completed') return t.completed;
        if (filter === 'date') return filteredDate(t);
        return true;
      });

      console.log(filtered)

      if (!filtered.length) {
        $('.task-list').append('<p class="text-muted">Задач нет.</p>');
        return;
      }

      filtered.sort((a, b) => new Date(a.date) - new Date(b.date));

      $.each(filtered, function(_, task) {
        const priorityLabel = task.priority === 'high' ? 'danger' : task.priority === 'medium' ? 'warning' : 'success';
        const checkedAttr = task.completed ? 'checked' : '';
        const textDecoration = task.completed ? 'text-decoration-line-through opacity-50' : '';

        if(typeof(task.priority) === 'undefined'){task.priority = 'def';}
        if(task.files.length < 1){
          task.files = [];
        }

        const $card = $(`
          <div class="card cardTask mb-2 bg-dark text-white border-secondary w-100" data-id="${task.id}">
            <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center flex-nowrap">

              <!-- Левая часть: чекбокс + дата + заголовок -->
              <div class="d-flex align-items-center flex-nowrap overflow-hidden text-truncate">

                <!-- Чекбокс -->
                <div class="custom-control custom-checkbox mr-3">
                  <input type="checkbox" class="toggle-completed custom-control-input complete-checkbox" id="check-${task.id}" data-id="${task.id}" ${checkedAttr}>
                  <label class="custom-control-label" for="check-${task.id}"></label>
                </div>

                <!-- Дата -->
                <small class="text-muted mr-3 text-nowrap dateTask" data-datetask="${task.date}">
                  📅 ${new Date(task.date).toLocaleString()}
                </small>
                <small class="text-muted mr-3 text-nowrap tomorow_to">
                  >>
                </small>

                <!-- Заголовок -->
                <h6 class="mb-0 text-truncate text-transition task-title ${textDecoration}" style="max-width: 700px; cursor: pointer;">
                  📝 ${task.title}
                  <span class="arrow ml-2" style="transition: 0.3s;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2"/></svg>
                    </span>
                </h6>
              </div>

              <!-- Приоритет справа -->
              <div class="ml-auto mr-3 text-nowrap">
                <span class="text-${priorityLabel} font-weight-bold priority_box">
                  ${task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}
                </span> - приоритет 🔥
              </div>

              <!-- Меню -->
              <div class="dropdown flex-shrink-0">
                <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-toggle="dropdown">
                  ⋮
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item edit-task" href="#" data-id="${task.id}">Редактировать</a>
                  <a class="dropdown-item delete-task text-danger" href="#" data-id="${task.id}">Удалить</a>
                </div>
              </div>

            </div>
            
            <div class="editor_form edit-form-wrapper collapse mt-2">
              <form class="edit-inline-form text-white">
                <div class="form-row mb-2">
                  <div class="col-sm-4">
                    <input type="text" class="form-control form-control-sm edit-title" placeholder="Заголовок" value="${task.title}">
                  </div>
                  <div class="col-sm-4">
                    <input type="datetime-local" class="form-control form-control-sm edit-date" value="${task.date}">
                  </div>
                  <div class="col-sm-4">
                    <select class="form-control form-control-sm edit-priority">
                      <option value="low" ${task.priority === 'low' ? 'selected' : ''}>Низкий</option>
                      <option value="medium" ${task.priority === 'medium' ? 'selected' : ''}>Средний</option>
                      <option value="high" ${task.priority === 'high' ? 'selected' : ''}>Высокий</option>
                    </select>
                  </div>
                </div>
                
                <div class="form-row mb-2">
                  <div class="col-sm-6">
                    <textarea class="form-control form-control-sm edit-description" placeholder="Описание">${task.description || ''}</textarea>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control form-control-sm edit-link" placeholder="Ссылка" value="${task.link || ''}">
                  </div>
                </div>

                <div class="form-row mb-2">
                  <div class="col-sm-4">
                    <input type="text" class="form-control form-control-sm edit-tag" placeholder="Метка" value="${task.tag || ''}">
                  </div>
                  <div class="col-sm-4">
                    <input type="text" class="form-control form-control-sm edit-coords" placeholder="Координаты (lat,lng)" value="${task.coords || ''}">
                  </div>
                  <div class="col-sm-4">
                    <input type="file" class="form-control-file edit-files" multiple>
                    ${task.files?.map(name => `<small class="d-block text-muted">${name}</small>`).join('') || ''}
                  </div>
                </div>

                <button type="submit" class="btn btn-success btn-sm">💾 Сохранить</button>
              </form>
            </div>

            <div class="task-details collapse px-3 pb-3 text-white small">
              ${task.description ? `<p><strong>Описание:</strong> ${task.description}</p>` : ''}
              ${task.link ? `<p><strong>Ссылка:</strong> <a href="${task.link}" target="_blank">${task.link}</a></p>` : ''}
              ${task.tag ? `<p><strong>Метка:</strong> ${task.tag}</p>` : ''}
              ${task.coords ? `<p><strong>Координаты:</strong> ${task.coords}</p>` : ''}
              ${task.files?.length ? `<p><strong>Файлы:</strong><br>${task.files.map(name => `<span class="badge badge-info mr-1">${name}</span>`).join('')}</p>` : ''}
            </div>


          </div>
        `);



        if (task.completed) $card.addClass('completed');
          $list.append($card);

        var alert_class = '';
        if(new Date(task.date) < new Date() && !$card.hasClass('completed')){
          $card.addClass('alert_task')
          $card.find('.priority_box').html('OVERDUE').removeClass('text-success').addClass('text-danger')
        }
      });

    }

    function bindEvents() {
      // Tabs
      $('.ndoundtabs').on('click', function () {
        $('.ndoundtabs').removeClass('active');
        $(this).addClass('active');
        const filter = $(this).data('filter');

        renderTasks(filter);
      });

      // Toggle description
      $(document).on('click', '.task-title', function () {
        const $card = $(this).closest('.card');
        const $details = $card.find('.task-details');
        const $arrow = $(this).find('.arrow');

        $('.task-details').not($details).slideUp();
        $('.arrow').not($arrow).removeClass('rotated');

        $details.slideToggle(200);
        $arrow.toggleClass('rotated');
      });

      // Delete task
      $(document).on('click', '.delete-task', function () {
        const id = $(this).closest('.card').data('id');
        tasks = tasks.filter(t => t.id !== id);
        saveTasks();
        renderTasks($('.ndoundtabs.active').data('filter'));
      });

      // Toggle completed
      $(document).on('click', '.tomorow_to', function () {
        TomorowToTask($(this).siblings('.dateTask'));
      });

      $(document).on('click', '.toggle-completed', function () {
        const $card = $(this).closest('.card');
        const id = $card.data('id');
        const task = tasks.find(t => t.id === id);
        if (task) {
          task.completed = !task.completed;
          saveTasks();
          renderTasks($('.ndoundtabs.active').data('filter'));
        }
      });

      // Edit form
      $(document).on('click', '.edit-task', function (e) {
        e.preventDefault();
        const $card = $(this).closest('.card');
        const $form = $card.find('.edit-form-wrapper');
        $('.edit-form-wrapper').not($form).hide('fast');
        $form.toggle();
      });

      // Save edit
      $(document).on('click', '.save-edit', function () {
        const $card = $(this).closest('.card');
        const id = $card.data('id');
        const task = tasks.find(t => t.id === id);
        if (task) {
          task.title = $card.find('.edit-title').val();
          task.description = $card.find('.edit-description').val();
          task.link = $card.find('.edit-link').val();
          task.tag = $card.find('.edit-tag').val();
          task.updated_at = new Date().toISOString();
          saveTasks();
          renderTasks($('.ndoundtabs.active').data('filter'));
        }
      });
    }

    function setupForm() {
      $(document).find('#taskForm').on('submit', function(e) {
        e.preventDefault();
        const title = $('#taskTitle').val();
        const date = $('#taskDate').val();
        const priority = $('#taskPriority').val();
        const task = {
          id: Date.now(),
          title,
          date,
          priority,
          description: $('#taskDescription').val(),
          link: $('#taskLink').val(),
          tag: $('#taskTag').val(),
          coords: $('#taskCoords').val(),
          files: $('#taskFiles')[0].files.length
            ? Array.from($('#taskFiles')[0].files).map(f => f.name)
            : [],
          completed: false,
          synced: false,
          _synced: false,
        };
        tasks.unshift(task);
        saveTasks();
        this.reset();
        renderTasks();
      });
    }

    $(document).on('submit', '.edit-inline-form', function(e) {
      e.preventDefault();
      const $form = $(this);
      const $card = $form.closest('.card');
      const id = parseInt($card.data('id'));
      const task = tasks.find(t => t.id === id);

      if (task) {
        task.title = $form.find('.edit-title').val();
        task.date = $form.find('.edit-date').val();
        task.priority = $form.find('.edit-priority').val();
        task.description = $form.find('.edit-description').val();
        task.link = $form.find('.edit-link').val();
        task.tag = $form.find('.edit-tag').val();
        task.coords = $form.find('.edit-coords').val();
        task.synced = false;
        task._synced = false;
        const filesInput = $form.find('.edit-files')[0];
        task.files = filesInput?.files.length
          ? Array.from(filesInput.files).map(f => f.name)
          : task.files;

        saveTasks();
        renderTasks();
      }
    });

    function isWeekendDate(inputDateStr, inputFormat) {
      // Приведение к UTC-подобному парсеру через собственную логику или через Date конструктор
      // Здесь реализую простой разбор для форматов "YYYY-MM-DD" и "YYYY-MM-DD HH:mm:ss"
      var y, m, d, hh = 0, mm = 0, ss = 0;

      // Простой разбор без зависимостей
      var datePart = inputDateStr.trim();
      var timePart = "";
      if (datePart.indexOf(" ") >= 0) {
        var parts = datePart.split(/\s+/);
        datePart = parts[0];
        timePart = parts[1];
      }

      // Разбираем дату
      var dParts = datePart.split("-");
      if (dParts.length < 3) {
        // Неподдерживаемый формат
        return { error: "Неподдерживаемый формат даты" };
      }
      y = parseInt(dParts[0], 10);
      m = parseInt(dParts[1], 10) - 1; // месяцы в JS с нуля
      d = parseInt(dParts[2], 10);

      // Разбираем время, если есть
      if (timePart) {
        var tParts = timePart.split(":");
        if (tParts.length >= 2) {
          hh = parseInt(tParts[0], 10);
          mm = parseInt(tParts[1], 10);
          if (tParts.length >= 3) {
            ss = parseInt(tParts[2], 10);
          }
        }
      }

      // Создаём объект Date (локальное время)
      var dt = new Date(y, m, d, hh, mm, ss);
      // Можно использовать UTC, если надо:
      // var dt = new Date(Date.UTC(y, m, d, hh, mm, ss));

      if (isNaN(dt.getTime())) {
        return { error: "Не удалось распознать дату" };
      }

      var day = dt.getDay(); // 0 - воскресенье, 6 - суббота
      var isWeekend = (day === 0 || day === 6);

      // Пример вывода: можно вернуть объект с датой и признаком выходного
      return {
        date: dt,
        isWeekend: isWeekend,
        dayOfWeek: day // 0-6
      };
    }

    function init() {
      loadTasks();
      renderTasks('all');
      bindEvents();
      setupForm();
      setInterval(syncToServer, 180000);
      // setInterval(syncToServer, 1000);
      syncToServer(true);
    }

    init();
    return this;
  };
})(jQuery);