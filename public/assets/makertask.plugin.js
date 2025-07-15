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
        const $card = $('<div class="card mb-2 bg-dark text-white border-secondary">').attr('data-id', task.id);
        if (task.completed) $card.addClass('completed');

        const priorityLabel = task.priority === 'high' ? 'danger' : task.priority === 'medium' ? 'warning' : 'success';
        const $body = $('<div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">');

        const $info = $('<div>');
        $info.append(`<h6 class="mb-1">📝 ${task.title}</h6>`);
        $info.append(`<small class="d-block">📅 ${new Date(task.date).toLocaleString()}</small>`);
        $info.append(`<small class="d-block">🔥 Приоритет: <span class="text-${priorityLabel} font-weight-bold">${task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}</span></small>`);

        const $menu = $(`
          <div class="dropdown">
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
      $('body').removeClass('light dark').addClass(theme);
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
      $container.find('#taskForm').on('submit', function(e) {
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

      $container.on('click', '.complete-btn', function() {
        const id = $(this).data('id');
        const task = tasks.find(t => t.id === id);
        if (task) {
          task.completed = true;
          saveTasks();
          renderTasks();
        }
      });

      $container.on('click', '.delete-task', function() {
        const id = $(this).data('id');
        tasks = tasks.filter(t => t.id !== id);
        saveTasks();
        renderTasks();
      });

      $container.on('click', '.edit-task', function() {
        const id = $(this).data('id');
        const task = tasks.find(t => t.id === id);
        if (task) showEditModal(task);
      });

      $container.find('#searchInput').on('input', function() {
        searchQuery = $(this).val();
        renderTasks();
      });

      $container.find('#tabList .nav-link').on('click', function(e) {
        e.preventDefault();
        $container.find('#tabList .nav-link').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        renderTasks();
      });

      $container.find('#themeToggle').on('click', function() {
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
      const theme = localStorage.getItem('theme') || settings.defaultTheme;
      applyTheme(theme);
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
