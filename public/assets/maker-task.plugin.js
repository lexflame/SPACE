// maker-task.plugin.js (гибрид: localStorage + CodeIgniter API)

(function($) {
  $.fn.makerTask = function(options) {
    const settings = $.extend({
      storageKey: 'makerTasks',
      apiUrl: '/tasks'
    }, options);

    const $container = this;

    // ============ ИНИЦИАЛИЗАЦИЯ ============ //
    function init() {
      loadTasks(function(tasks) {
        renderTasks(tasks);
      });

      bindEvents();
    }

    // ============ ЗАГРУЗКА ЗАДАЧ ============ //
    function loadTasks(callback) {
      const local = getFromStorage();
      if (local.length > 0) {
        callback(local);
      } else {
        $.getJSON(settings.apiUrl, function(data) {
          saveToStorage(data);
          callback(data);
        });
      }
    }

    // ============ ХРАНЕНИЕ ============ //
    function getFromStorage() {
      return JSON.parse(localStorage.getItem(settings.storageKey) || '[]');
    }

    function saveToStorage(tasks) {
      localStorage.setItem(settings.storageKey, JSON.stringify(tasks));
    }

    function addToStorage(task) {
      const tasks = getFromStorage();
      tasks.push(task);
      saveToStorage(tasks);
    }

    function updateInStorage(updatedTask) {
      const tasks = getFromStorage().map(t => t.id === updatedTask.id ? updatedTask : t);
      saveToStorage(tasks);
    }

    function removeFromStorage(id) {
      const tasks = getFromStorage().filter(t => t.id !== id);
      saveToStorage(tasks);
    }

    // ============ CRUD ============ //
    function createTask(task) {
      addToStorage(task);
      renderTasks(getFromStorage());

      $.ajax({
        url: settings.apiUrl,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(task),
        success: function(res) {
          if (res.id) {
            task.id = res.id;
            updateInStorage(task);
            renderTasks(getFromStorage());
          }
        }
      });
    }

    function editTask(task) {
      updateInStorage(task);
      renderTasks(getFromStorage());

      $.ajax({
        url: `${settings.apiUrl}/${task.id}`,
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(task)
      });
    }

    function deleteTask(id) {
      removeFromStorage(id);
      renderTasks(getFromStorage());

      $.ajax({
        url: `${settings.apiUrl}/${id}`,
        method: 'DELETE'
      });
    }

    // ============ РЕНДЕРИНГ ============ //
    function renderTasks(tasks) {
      $container.empty();
      tasks.forEach(task => {
        const card = $(
          `<div class="card mb-2" data-id="${task.id}">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span class="task-title" style="cursor:pointer">${task.title}</span>
              <div>
                <button class="btn btn-sm btn-primary edit-task">✏</button>
                <button class="btn btn-sm btn-danger delete-task">🗑</button>
              </div>
            </div>
            <div class="task-details card-body" style="display:none">
              <p>${task.description || 'Нет описания'}</p>
            </div>
            <div class="edit-form-wrapper card-body" style="display:none">
              <input class="form-control mb-1" name="title" value="${task.title}" />
              <textarea class="form-control mb-2" name="description">${task.description || ''}</textarea>
              <button class="btn btn-success save-edit">Сохранить</button>
            </div>
          </div>`
        );
        $container.append(card);
      });
    }

    // ============ СОБЫТИЯ ============ //
    function bindEvents() {
      // Показать описание
      $container.on('click', '.task-title', function() {
        const $card = $(this).closest('.card');
        const $details = $card.find('.task-details');
        const $form = $card.find('.edit-form-wrapper');

        $('.task-details').not($details).slideUp();
        $('.edit-form-wrapper').not($form).slideUp();

        $details.slideToggle();
      });

      // Редактирование
      $container.on('click', '.edit-task', function() {
        const $card = $(this).closest('.card');
        const $form = $card.find('.edit-form-wrapper');
        const $details = $card.find('.task-details');

        $('.task-details').not($details).slideUp();
        $('.edit-form-wrapper').not($form).slideUp();

        $form.slideToggle();
      });

      // Сохранить изменения
      $container.on('click', '.save-edit', function() {
        const $card = $(this).closest('.card');
        const id = parseInt($card.data('id'));
        const title = $card.find('input[name="title"]').val();
        const description = $card.find('textarea[name="description"]').val();
        editTask({ id, title, description });
      });

      // Удалить
      $container.on('click', '.delete-task', function() {
        if (!confirm('Удалить задачу?')) return;
        const id = parseInt($(this).closest('.card').data('id'));
        deleteTask(id);
      });
    }

    // ============ СТАРТ ============ //
    init();
    return this;
  };
})(jQuery);