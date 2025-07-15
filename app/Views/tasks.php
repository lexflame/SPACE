<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MakerTask</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>

  <style>
    html, body {
      height: 100%;
      margin: 0;
      overflow: hidden;
    }
    body.dark {
      background-color: #343a40;
      color: white;
    }
    body.light {
      background-color: #f8f9fa;
      color: #212529;
    }
    .toolbar {
      height: 60px;
      background-color: #212529;
      color: white;
      display: flex;
      justify-content: space-evenly;
      align-items: center;
      padding: 0 20px;
      flex-direction: row;
      flex-wrap: wrap;
      align-content: center;
    }
    .toolbar.light {
      background-color: #e9ecef;
      color: #212529;
    }
    .content {
      height: calc(100% - 60px);
      overflow-y: auto;
      padding: 20px;
    }
    .completed {
      text-decoration: line-through;
      opacity: 0.6;
    }
  </style>
</head>
<body class="">
  <!-- Верхняя панель -->
  <div class="toolbar" id="toolbar">

    <div class="d-flex align-items-center">
      <svg width="36" height="36" fill="currentColor" viewBox="0 0 64 64">
        <path d="M32 4a28 28 0 1 0 0.001 56.001A28 28 0 0 0 32 4zm0 4a24 24 0 1 1 0 48 24 24 0 0 1 0-48z"/>
        <path d="M22 44V20h4l6 10 6-10h4v24h-4V28l-6 10-6-10v16h-4z" fill="#17a2b8"/>
        <path d="M31 24l2 2-10 10-2-2zM22 38l2 2 2-2-2-2z" fill="#ffc107"/>
      </svg>
      <strong class="ml-2">MakerTask</strong>
    </div>

    <form id="taskForm" class="form-inline mb-3 mr-2" style="margin-top: 20px;width: 75%;">
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

      <button class="btn btn-sm btn-secondary" id="themeToggle">Сменить тему</button>

  </div>

  <!-- Контент -->
  <div class="content">
    <div id="makerTaskApp">
      

      <ul class="nav nav-tabs mb-3" id="tabList">
        <li class="nav-item"><a href="#" class="nav-link active" data-filter="all">Все</a></li>
        <li class="nav-item"><a href="#" class="nav-link" data-filter="today">Сегодня</a></li>
        <li class="nav-item"><a href="#" class="nav-link" data-filter="completed">Выполнено</a></li>
      </ul>

      <div class="task-list"></div>
    </div>
  </div>

  <!-- Подключение библиотек -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="/assets/makertask.plugin.js"></script>
  <script>
    $(document).ready(function() {
      $('#maker-task-app').makerTask();
    });
  </script>
</body>
</html>
