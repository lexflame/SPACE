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
        const $card = $('<div class="card mb-2">');
        if (task.completed) $card.addClass('completed');
        const $body = $('<div class="card-body">');
        $body.append(`<h5 class="card-title mb-1">${task.title}</h5>`);
        $body.append(`<p class="card-text mb-1"><strong>Дата:</strong> ${new Date(task.date).toLocaleString()}</p>`);
        $body.append(`<p class="card-text mb-2"><strong>Приоритет:</strong> ${task.priority}</p>`);

        if (!task.completed) {
          $body.append(`<button class="btn btn-sm btn-success complete-btn" data-id="${task.id}">Завершить</button> `);
        }
        $body.append(`<button class="btn btn-sm btn-danger delete-btn" data-id="${task.id}">Удалить</button>`);
        $card.append($body);
        $list.append($card);
      });
    }

    function applyTheme(theme) {
      $('body').removeClass('light dark').addClass(theme);
      localStorage.setItem('theme', theme);
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

      $container.on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        tasks = tasks.filter(t => t.id !== id);
        saveTasks();
        renderTasks();
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
})(jQuery);
