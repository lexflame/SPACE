// jQuery MakerTask Plugin
(function($) {
  $.fn.makerTask = function(options) {
    const settings = $.extend({
      storageKey: 'makerTasks',
      defaultTheme: 'dark'
    }, options);

    const $container = this;
    let tasks = [];
    let currentFilter = 'all';
    let searchQuery = '';

    var currentDate = new Date();
    var CurDay = currentDate.getDate();
    var CurMonth = (currentDate.getMonth() < 10)?'0'+currentDate.getMonth():currentDate.getMonth();
    var CurYear = currentDate.getFullYear();
    var CurHours = currentDate.getHours();
    var CurMinutes = currentDate.getMinutes();
    var CurSeconds = currentDate.getSeconds();
    var formatTaskDate = CurDay+'.'+CurMonth+'.'+CurYear+', '+CurHours+':'+CurMinutes+':'+CurSeconds;

    function loadTasks() {
      const saved = localStorage.getItem(settings.storageKey);
      tasks = saved ? JSON.parse(saved) : [];
    }

    function saveTasks() {
      localStorage.setItem(settings.storageKey, JSON.stringify(tasks));
      syncWithServer();
    }

    function SortByPriority(a, b){ 
      var aName = a.priority.toLowerCase();
      var bName = b.priority.toLowerCase(); 
      return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
    }

    function renderTasksFromStorage() {
      const tasks = JSON.parse(localStorage.getItem(settings.storageKey) || '[]');
      const $list = $container.find('.task-list');
      $list.empty();

      tasks.forEach(task => {
        const card = renderTasks(task);
        $list.append(card);
      });
    }

    function syncWithServer() {
      const localTasks = JSON.parse(localStorage.getItem(settings.storageKey) || '[]');

      // Сначала отправим локальные изменения (например, новые или изменённые задачи)
      const syncPromises = localTasks.map(task => {
        if (task._synced === false) {
          // Новая или обновлённая задача
          if (task.id) {
            return $.ajax({
              url: `/tasks/update/${task.id}`,
              type: 'PUT',
              contentType: 'application/json',
              data: JSON.stringify(task)
            });
          } else {
            return $.ajax({
              url: '/tasks/create',
              type: 'POST',
              contentType: 'application/json',
              data: JSON.stringify(task),
              success: (res) => {
                task.id = res.id;
              }
            });
          }
        }

        return Promise.resolve(); // уже синхронизированная задача
      });

      // После всех отправок — обновим localStorage с сервера
      Promise.all(syncPromises).then(() => {
        // Запрос актуального состояния с сервера
        $.getJSON('/tasks/list', function(serverTasks) {
          if (Array.isArray(serverTasks)) {
            // Отметим все как синхронизированные
            serverTasks.forEach(task => (task._synced = true));
            localStorage.setItem(settings.storageKey, JSON.stringify(serverTasks));
            renderTasksFromStorage();
            console.log('Синхронизация завершена');
          }
        });
      }).catch(() => {
        console.warn('Ошибка при синхронизации изменений с сервером');
      });
    }


    function renderTasks() {
      const list = $(document).find('.task-list');
      $(list).empty();

      const now = new Date();
      const todayStr = now.toISOString().split('T')[0];

      let filtered = tasks.filter(t => {
        if (currentFilter === 'today') return t.date.startsWith(todayStr) && !t.completed;
        if (currentFilter === 'completed') return t.completed;
        return true;
      });

      if (searchQuery) {
        filtered = filtered.filter(t => t.title.toLowerCase().includes(searchQuery.toLowerCase()));
      }

      filtered.sort((a, b) => new Date(a.date) - new Date(b.date));
      // filtered.sort(SortByPriority);

      if (!filtered.length) {
        $(list).append('<p class="text-muted">Задач нет.</p>');
        return;
      }
    
      $.each(filtered, function(_, task) {
        const priorityLabel = task.priority === 'high' ? 'danger' : task.priority === 'medium' ? 'warning' : 'success';
        const checkedAttr = task.completed ? 'checked' : '';
        const textDecoration = task.completed ? 'text-decoration-line-through opacity-50' : '';



        const $card = $(`
          <div class="card mb-2 bg-dark text-white border-secondary w-100" data-id="${task.id}">
            <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center flex-nowrap">

              <!-- Левая часть: чекбокс + дата + заголовок -->
              <div class="d-flex align-items-center flex-nowrap overflow-hidden text-truncate">

                <!-- Чекбокс -->
                <div class="custom-control custom-checkbox mr-3">
                  <input type="checkbox" class="custom-control-input complete-checkbox" id="check-${task.id}" data-id="${task.id}" ${checkedAttr}>
                  <label class="custom-control-label" for="check-${task.id}"></label>
                </div>

                <!-- Дата -->
                <small class="text-muted mr-3 text-nowrap">
                  📅 ${new Date(task.date).toLocaleString()}
                </small>

                <!-- Заголовок -->
                <h6 class="mb-0 text-truncate text-transition task-title ${textDecoration}" style="max-width: 700px; cursor: pointer;">
                  📝 ${task.title}
                  <span class="arrow ml-2" style="transition: 0.3s;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2"/></svg></span>
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
          $(list).append($card);

        var alert_class = '';
        if(new Date(task.date) < new Date() && !$card.hasClass('completed')){
          $card.addClass('alert_task')
          $card.find('.priority_box').html('OVERDUE').removeClass('text-success').addClass('text-danger')
        }
      });

      // обработчик чекбоксов
      $(document).find('.complete-checkbox').on('change', function () {
        const id = $(this).data('id');
        const task = tasks.find(t => t.id === id);
        if (task) {
          const completed = this.checked;
          task.completed = completed;

          const card = $(this).closest('.card');
          var audio = new Audio('/assets/correctch.mp3');
          audio.play();

          // добавим анимацию
          $(card).addClass(completed ? 'task-completed-anim' : 'task-uncompleted-anim');

          // удалим анимацию через 1s
          setTimeout(() => $(card).removeClass('task-completed-anim task-uncompleted-anim'), 1000);

          saveTasks();
          renderTasks(); // Обновим весь список
        }
      });


      // $(document).sortable({
      //   update: function () {
      //     const newOrder = [];
      //     $(this).children('.card').each(function () {
      //       const id = $(this).attr('data-id');
      //       const task = tasks.find(t => t.id == id);
      //       if (task) newOrder.push(task);
      //     });
      //     tasks = newOrder;
      //     saveTasks();
      //   }
      // });
    }



    function applyTheme(theme) {
      // if(theme === 'light'){theme = 'dark';}
      // if(theme === 'dark'){theme = 'light';}
      $('body').removeClass('dark').removeClass('light').addClass(theme);
      localStorage.setItem('theme', theme);
    }

    function exportTasks() {
      const blob = new Blob([JSON.stringify(tasks, null, 2)], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'tasks.json';
      a.click();
    }

    function importTasks(file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        try {
          const imported = JSON.parse(e.target.result);
          if (Array.isArray(imported)) {
            tasks = imported;
            saveTasks();
            renderTasks();
          }
        } catch (err) {
          alert('Ошибка при импорте задач.');
        }
      };
      reader.readAsText(file);
    }

    function initEvents() {

      $(document).on('click', '.edit-task', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const $card = $(this).closest('.card');
        const $form = $card.find('.edit-form-wrapper');
        $('.task-details').slideUp();
        $('.edit-form-wrapper').hide('slow'); // сворачиваем остальные
        $form.show();
      });


      $(document).on('click', '.task-title', function () {
        const $card = $(this).closest('.card');
        const $details = $card.find('.task-details');
        const $arrow = $(this).find('.arrow');

        // Спрячем другие спойлеры
        $('.task-details').not($details).slideUp();
        $('.arrow').not($arrow).removeClass('rotated');

        // Показать / скрыть текущий
        $details.slideToggle(200);
        $arrow.toggleClass('rotated');
      });

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
        };
        // task._synced = false;
        tasks.unshift(task);
        saveTasks();
        this.reset();
        renderTasks();
        syncWithServer();
      });

      $(document).on('click', '.complete-btn', function() {
        const id = $(this).data('id');
        const task = tasks.find(t => t.id === id);
        if (task) {
          task.completed = true;
          renderTasks();
          saveTasks();
          syncWithServer(); 
        }
      });

      $(document).on('click', '.delete-task', function() {
        const id = $(this).data('id');
        tasks = tasks.filter(t => t.id !== id);
        saveTasks();
        renderTasks();
      });

      $(document).on('click', '.edit-task', function() {
        const id = $(this).data('id');
        const task = tasks.find(t => t.id === id);
        if (task) showEditModal(task);
      });

      $(document).find('#searchInput').on('input', function() {
        searchQuery = $(this).val();
        renderTasks();
      });

      $('body').find('#tabList .nav-link').on('click', function(e) {
        e.preventDefault();
        $container.find('#tabList .nav-link').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        renderTasks();
      });

      $('#themeToggle').on('click', function() {
        const theme = $('body').hasClass('dark') ? 'light' : 'dark';
        applyTheme(theme);
      });

      $('#editForm').on('submit', function(e) {
        e.preventDefault();
        const id = parseInt($('#editTaskId').val());
        const task = tasks.find(t => t.id === id);
        if (task) {
          task.title = $('#editTaskTitle').val();
          task.date = $('#editTaskDate').val();
          // task._synced = false;
          task.priority = $('#editTaskPriority').val();
          saveTasks();
          renderTasks();
          $('#editModal').modal('hide');
        }
      });

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
          const filesInput = $form.find('.edit-files')[0];
          task.files = filesInput?.files.length
            ? Array.from(filesInput.files).map(f => f.name)
            : task.files;

          saveTasks();
          renderTasks();
        }
      });


      $('#exportTasks').on('click', function() {
        exportTasks();
      });

      $('#importTasks').on('change', function(e) {
        const file = e.target.files[0];
        if (file) importTasks(file);
      });
    }


    function initTheme() {
      // const theme = localStorage.getItem('theme') || settings.defaultTheme;
      // applyTheme(theme);
    }

    function init() {
      loadTasks();
      renderTasksFromStorage();
      initTheme();
      initEvents();
      renderTasks();
      setInterval(syncWithServer, 180000);
    }

    init();
    return this;
  };

  $(function() {
    $('#makerTaskApp').makerTask();
  });
})(jQuery);
