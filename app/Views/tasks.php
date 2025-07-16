<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MakerTask</title>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="/assets/makerTask-responsive.css"/>
</head>
<body class="dark">
  <!-- Верхняя панель -->
  <div class="toolbar" id="toolbar">

    <a href="/" class="card-link" data-bubble="tasks">
      <button class="btn btn-sm btn-secondary" id=""><</button>
    </a>

    <div class="d-flex align-items-center">
      <svg width="36" height="36" fill="currentColor" viewBox="0 0 64 64">
        <path d="M32 4a28 28 0 1 0 0.001 56.001A28 28 0 0 0 32 4zm0 4a24 24 0 1 1 0 48 24 24 0 0 1 0-48z"/>
        <path d="M22 44V20h4l6 10 6-10h4v24h-4V28l-6 10-6-10v16h-4z" fill="#17a2b8"/>
        <path d="M31 24l2 2-10 10-2-2zM22 38l2 2 2-2-2-2z" fill="#ffc107"/>
      </svg>
      <strong class="ml-2">MakerTask</strong>
    </div>

    <form id="taskForm" class="form-inline mb-3 mr-2" style="margin-top: 20px;width: 75%">
        <input type="text" class="form-control mr-2 mb-2" id="taskTitle" placeholder="Название" required>
        <input type="datetime-local" class="form-control mr-2 mb-2" id="taskDate" required>
        <select class="form-control mr-2 mb-2" id="taskPriority">
          <option value="low">Низкий</option>
          <option value="medium" selected>Средний</option>
          <option value="high">Высокий</option>
        </select>
        <button type="submit" class="btn btn-primary mb-2">Создать</button>
        <input type="text" class="form-control ml-auto mb-2" id="searchInput" placeholder="Поиск...">
      </form>

      <button class="btn btn-sm btn-secondary" id="themeToggle" style="opacity: 0;">Сменить тему</button>
      <button class="btn btn-sm btn-secondary" id="themeToggle" style="opacity: 0;">См</button>

  </div>

  <!-- Контент -->
  <div class="content" style="width: 75%;margin:auto;">
    <div id="makerTaskApp">
      

      <ul class="nav nav-tabs mb-3" id="tabList">
        <li class="nav-item"><a href="#" class="nav-link active" data-filter="all">Все</a></li>
        <li class="nav-item"><a href="#" class="nav-link" data-filter="today">Сегодня</a></li>
        <li class="nav-item"><a href="#" class="nav-link" data-filter="completed">Выполнено</a></li>
      </ul>

      <div class="task-list">
        
      <div class="card mb-3 bg-dark text-white border-secondary">
        <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1">📝 Подготовить отчёт</h6>
            <small class="d-block">📅 2025-07-16 10:30</small>
            <small class="d-block">🔥 Приоритет: <span class="text-danger font-weight-bold">Высокий</span></small>
          </div>
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" id="taskMenu123" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ⋮
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="taskMenu123">
              <a class="dropdown-item edit-task" href="#" data-id="123">Редактировать</a>
              <a class="dropdown-item delete-task text-danger" href="#" data-id="123">Удалить</a>
            </div>
          </div>
        </div>
      </div>



      </div>
    </div>
  </div>

  <!-- Подключение библиотек -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <!-- <script src="/assets/makertask.plugin.js"></script> -->
  <script>

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
          const $list = $container.find('.task-list');
          $list.empty();

          const now = new Date();
          const todayStr = now.toISOString().split('T')[0];

          let filtered = tasks.filter(t => {
            if (currentFilter === 'today') return t.date.startsWith(todayStr) && !t.completed;
            if (currentFilter === 'completed') return t.completed;
            return currentFilter === 'all' || true;
          });

          if (searchQuery) {
            filtered = filtered.filter(t => t.title.toLowerCase().includes(searchQuery.toLowerCase()));
          }

          filtered.sort((a, b) => new Date(a.date) - new Date(b.date));

          if (!filtered.length) {
            $list.append('<p class="text-muted">Задач нет.</p>');
            return;
          }

          $.each(filtered, function(_, task) {
            const $card = $('<div class="card mb-2 bg-dark text-white border-secondary w-100">')
              .attr('data-id', task.id)
              .addClass('task-card');
            if (task.completed) $card.addClass('completed');

            const priorityLabel = task.priority === 'high' ? 'danger' : task.priority === 'medium' ? 'warning' : 'success';
            const $body = $('<div class="card-body py-2 px-3 d-flex justify-content-between align-items-center flex-wrap">');

            const $info = $('<div class="task-info text-truncate pr-2 flex-fill w-100 w-sm-auto">');
            $info.append(`<h6 class="mb-1">📝 ${task.title}</h6>`);
            $info.append(`<small class="d-block">📅 ${new Date(task.date).toLocaleString()}</small>`);
            $info.append(`<small class="d-block">🔥 Приоритет: <span class="text-${priorityLabel} font-weight-bold">${task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}</span></small>`);

            const $menu = $(`
              <div class="dropdown task-menu text-right">
                <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-toggle="dropdown">
                  ⋮
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item edit-task" href="#" data-id="${task.id}">Редактировать</a>
                  <a class="dropdown-item delete-task text-danger" href="#" data-id="${task.id}">Удалить</a>
                </div>
              </div>
            `);

            $body.append($info).append($menu);
            $card.append($body);
            $list.append($card);
          });

          $list.sortable({
            update: function() {
              const newOrder = [];
              $list.children('.card').each(function() {
                const id = $(this).attr('data-id');
                const task = tasks.find(t => t.id == id);
                if (task) newOrder.push(task);
              });
              tasks = newOrder;
              saveTasks();
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

      $(function() {
        $('#makerTaskApp').makerTask();
      });

    })(jQuery);


    $(document).ready(function() {
      $('#maker-task-app').makerTask();
    });
    $(document).on('click', '.edit-task', function () {
      const id = $(this).data('id');
      // логика редактирования задачи с id
    });

    $(document).on('click', '.delete-task', function () {
      const id = $(this).data('id');
      // логика удаления задачи с id
    });

  </script>
</body>
</html>
