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

        function loadTasks() {
          const saved = localStorage.getItem(settings.storageKey);
          tasks = saved ? JSON.parse(saved) : [];
        }

        function saveTasks() {
          localStorage.setItem(settings.storageKey, JSON.stringify(tasks));
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
                    <h6 class="mb-0 text-truncate text-transition ${textDecoration}" style="max-width: 250px;">
                      📝 ${task.title}
                    </h6>
                  </div>

                  <!-- Приоритет справа -->
                  <div class="ml-auto mr-3 text-nowrap">
                    🔥 Приоритет: <span class="text-${priorityLabel} font-weight-bold">
                      ${task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}
                    </span>
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
              </div>
            `);

            if (task.completed) $card.addClass('completed');
            $(list).append($card);
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

        function syncWithServer() {
          $.ajax({
            url: '/api/tasks/sync', // Укажи свой реальный URL на сервере
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(tasks),
            success: function(response) {
              console.log('Синхронизация успешна:', response);
              // если сервер вернул новые данные — можно обновить
              // tasks = response.tasks || tasks;
              // renderTasks();
            },
            error: function(xhr, status, error) {
              console.error('Ошибка при синхронизации:', error);
            }
          });
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

        function showEditModal(task) {
          $('#editTaskId').val(task.id);
          $('#editTaskTitle').val(task.title);
          $('#editTaskDate').val(task.date);
          $('#editTaskPriority').val(task.priority);
          $('#editModal').modal('show');
        }

        function initEvents() {
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
              completed: false
            };
            tasks.unshift(task);
            saveTasks();
            this.reset();
            renderTasks();
          });

          $(document).on('click', '.complete-btn', function() {
            const id = $(this).data('id');
            const task = tasks.find(t => t.id === id);
            if (task) {
              task.completed = true;
              saveTasks();
              renderTasks();
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
              task.priority = $('#editTaskPriority').val();
              saveTasks();
              renderTasks();
              $('#editModal').modal('hide');
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
          initTheme();
          initEvents();
          renderTasks();
        }

        init();
        return this;
      };



    })(jQuery);