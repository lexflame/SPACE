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
  <div class="toolbar container-fluid py-3" id="toolbar">
    <div class="d-flex align-items-center flex-wrap mb-3">
      <svg width="36" height="36" fill="currentColor" viewBox="0 0 64 64">
        <path d="M32 4a28 28 0 1 0 0.001 56.001A28 28 0 0 0 32 4zm0 4a24 24 0 1 1 0 48 24 24 0 0 1 0-48z"/>
        <path d="M22 44V20h4l6 10 6-10h4v24h-4V28l-6 10-6-10v16h-4z" fill="#17a2b8"/>
        <path d="M31 24l2 2-10 10-2-2zM22 38l2 2 2-2-2-2z" fill="#ffc107"/>
      </svg>
      <strong class="ml-2 h5 mb-0">MakerTask</strong>
      <button class="btn btn-sm btn-secondary ml-auto mt-2 mt-sm-0" id="themeToggle">Сменить тему</button>
    </div>

    <!-- Форма -->
    <form id="taskForm" class="form-row w-100">
      <div class="form-group col-md-4 mb-2">
        <input type="text" class="form-control" id="taskTitle" placeholder="Название" required>
      </div>
      <div class="form-group col-md-3 mb-2">
        <input type="datetime-local" class="form-control" id="taskDate" required>
      </div>
      <div class="form-group col-md-2 mb-2">
        <select class="form-control" id="taskPriority">
          <option value="low">Низкий</option>
          <option value="medium" selected>Средний</option>
          <option value="high">Высокий</option>
        </select>
      </div>
      <div class="form-group col-md-2 mb-2">
        <button type="submit" class="btn btn-primary btn-block">Создать</button>
      </div>
      <div class="form-group col-md-12 col-lg-6 ml-auto mb-2">
        <input type="text" class="form-control" id="searchInput" placeholder="Поиск...">
      </div>
    </form>
  </div>

  <!-- Контент -->
  <div class="container">
    <div id="makerTaskApp">

      <!-- Вкладки -->
      <ul class="nav nav-tabs mb-3 flex-wrap" id="tabList">
        <li class="nav-item"><a href="#" class="nav-link active" data-filter="all">Все</a></li>
        <li class="nav-item"><a href="#" class="nav-link" data-filter="today">Сегодня</a></li>
        <li class="nav-item"><a href="#" class="nav-link" data-filter="completed">Выполнено</a></li>
      </ul>

      <!-- Список задач -->
      <div class="task-list"></div>
    </div>
  </div>

  <!-- Скрипты -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="/assets/makertask.plugin.js"></script>
  <script>
    $(document).ready(function() {
      $('#makerTaskApp').makerTask();
    });

    $(document).on('click', '.edit-task', function () {
      const id = $(this).data('id');
      // логика редактирования задачи
    });

    $(document).on('click', '.delete-task', function () {
      const id = $(this).data('id');
      // логика удаления задачи
    });
  </script>
</body>
</html>
