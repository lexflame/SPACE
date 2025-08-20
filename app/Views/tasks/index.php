<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MakerTask</title>
  <!-- jQuery (обязательно перед Bootstrap) -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <!-- Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <!-- Bootstrap JS -->
  <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="/assets/css/makerTask-responsive.css"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="dark">

    <div id="page-fadeout"></div>
    <style>
        #page-fadeout {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: #000;
          opacity: 0;
          pointer-events: none;
          z-index: 9999;
          transition: opacity 0.6s ease;
        }
        #page-fadeout.show {
          opacity: 1;
          pointer-events: auto;
        }
        .inout-date-picker .inout-date-display {
          min-width: 120px;
          text-align: center;
          font-weight: 500;
        }
    </style>
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
          <option value="high">Высокий</option>
          <option value="medium" selected>Средний</option>
          <option value="low">Низкий</option>
        </select>
        <div class="form-row mb-2 disabled">
          <div class="col-md-6">
            <textarea id="taskDescription" class="form-control" placeholder="Описание задачи"></textarea>
          </div>
          <div class="col-md-6">
            <input type="text" id="taskLink" class="form-control" placeholder="Ссылка">
          </div>
        </div>

        <div class="form-row mb-2 disabled">
          <div class="col-md-4">
            <input type="text" id="taskTag" class="form-control" placeholder="Метка">
          </div>
          <div class="col-md-4">
            <input type="text" id="taskCoords" class="form-control" placeholder="Координаты (lat,lng)">
          </div>
          <div class="col-md-4">
            <input type="file" id="taskFiles" class="form-control-file" multiple>
          </div>
        </div>

        <button type="submit" class="btn btn-warning mb-2">Создать</button>
        <input type="text" class="form-control ml-auto mb-2" id="searchInput" placeholder="Поиск...">
      </form>

      <button class="btn btn-sm btn-secondary" id="themeToggle" style="opacity: 0;">Сменить тему</button>
      <button class="btn btn-sm btn-secondary" id="themeToggle" style="opacity: 0;">См</button>

  </div>

  <!-- Контент -->
  <div class="content" style="width: 75%;margin:auto;">
    <div id="makerTaskApp">

      <ul class="nav nav-tabs mb-3" id="tabList">
        <li class="nav-item"><a href="#" class="ndoundtabs nav-link active" data-filter="all">Все</a></li>
        <li class="nav-item"><a href="#" class="ndoundtabs nav-link today_btn" data-filter="today">Сегодня</a></li>
        <li class="nav-item"><a href="#" class="ndoundtabs nav-link" data-filter="completed">Выполнено</a></li>
        <li class="nav-item"><a href="#" class="ndoundtabs nav-link" data-filter="date">
          <div id="inout" class="inout_box"></div>
        </a></li>
      </ul>

    <div id="taskContainer">
      <div class="task-list"></div>
    </div>

    </div>
  </div>

  <!-- Подключение библиотек и медиа-->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="/assets/js/maker-task.plugin.js"></script>
  <script src="/assets/js/maker-task-date.plugin.js"></script>
  <script>
    
    $(function() {
      $('#taskContainer').makerTask({
        apiUrl: '/tasks',          // API CodeIgniter
        storageKey: 'makerTasks'
      });
    });
  </script>
  <script>
    $(document).ready(function() {
       $('.today_btn').click();
    });
  </script>
  <audio id="sound" preload="auto">
   <source src="/assets/media/correctch.mp3" type="audio/mpeg">
  </audio>
    <script>
      // Переход при клике по ссылке
      $(document).on('click', 'a[href]:not([target="_blank"]):not([href^="#"])', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        $('#page-fadeout').addClass('show');
        setTimeout(() => {
          window.location.href = href;
        }, 600);
      });

      // Переход при обновлении/перезагрузке
      window.addEventListener('beforeunload', () => {
        document.getElementById('page-fadeout').classList.add('show');
      });
    </script>
    <script>
      $(function(){
        // инициализация плагина
        $('#inout').inoutDatePicker({
          format: 'YYYY-MM-DD',
          dind: true,
          // startDate можно указать любую дату, иначе текущая
          // startDate: '2025-08-15',
          container: '#inout'
        });
      });
    </script>
</body>
</html>
