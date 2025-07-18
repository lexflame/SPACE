<!-- TACMap HTML -->
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TACMap</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="/assets/tacmap.css">
  <style>
    .left-sidebar .btn {
      width: 56px;
      padding: 0.5rem 0;
    }
    .left-sidebar .btn small {
      font-size: 11px;
      line-height: 1.2;
      margin-top: 4px;
    }
    .left-sidebar i {
      font-size: 20px;
    }
    #map-background {
      position: absolute;
      top: 0; left: 0;
      width: 100vw;
      height: 100vh;
      background-image: url('https://i2.wp.com/images.wikia.com/reddeadredemption/images/archive/a/a3/20110625221743!Red-Dead-Redemption-Detailed-Game-Map.jpg'); /* карта из видеоигры */
      background-size: cover;
      background-position: center;
      z-index: 0;
    }
  </style>
</head>
<body>
  <!-- Фон карты -->
  <div id="map-wrapper">
    <div id="map-background"></div>
  </div>

  <!-- Верхняя панель -->
  <div class="top-bar d-flex justify-content-between align-items-center px-3 py-4">
    <div class="d-flex align-items-center">
      <button class="btn btn-outline-light mr-2"><i class="fas fa-cube"></i> Маркеры</button>
      <button class="btn btn-outline-light mr-2"><i class="fas fa-map"></i> Карты</button>

      <!-- Выпадающее меню -->
      <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="mainMenuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-bars"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="mainMenuDropdown">
          <a class="dropdown-item" href="/"><i class="fas fa-cube mr-2"></i>В Пространство</a>
          <a class="dropdown-item" href="/tasks"><i class="fas fa-tasks mr-2"></i>Задачи</a>
          <a class="dropdown-item" href="/tacmap"><i class="fas fa-map-marked-alt mr-2"></i>Карты</a>
          <a class="dropdown-item" href="/notes"><i class="fas fa-sticky-note mr-2"></i>Заметки</a>
          <a class="dropdown-item" href="/debug"><i class="fas fa-bug mr-2"></i>Отладчик</a>
          <a class="dropdown-item" href="/wicker"><i class="fas fa-rss mr-2"></i>Викер</a>
        </div>
      </div>
    </div>

    <div class="d-flex align-items-center">
      <div class="badge badge-success mr-2">1</div>
      <button class="btn btn-outline-light mr-2"><i class="fas fa-users"></i></button>
      
      <!-- <button class="btn btn-outline-light dropdown-toggle" type="button" id="mainMenuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button> -->
    </div>
  </div>


  <!-- Левый сайдбар -->
  <div class="left-sidebar d-flex flex-column align-items-center p-2 text-center">
    <div class="mb-3">
      <button class="btn btn-light d-flex flex-column align-items-center">
        <i class="fas fa-search"></i>
        <small>Поиск</small>
      </button>
    </div>
    <div class="mb-3">
      <button class="btn btn-light d-flex flex-column align-items-center">
        <i class="fas fa-route"></i>
        <small>Маршруты</small>
      </button>
    </div>
    <div class="mb-3">
      <button class="btn btn-light d-flex flex-column align-items-center">
        <i class="fas fa-user-friends"></i>
        <small>Друзья</small>
      </button>
    </div>
    <div class="mb-3">
      <button class="btn btn-light d-flex flex-column align-items-center">
        <i class="fas fa-book"></i>
        <small>Гид</small>
      </button>
    </div>
    <div class="mb-3">
      <button class="btn btn-light d-flex flex-column align-items-center">
        <i class="fas fa-home"></i>
        <small>Жильё</small>
      </button>
    </div>
  </div>

  <!-- Правая панель -->
  <div class="right-toolbar d-flex flex-column align-items-center p-2">
    <button class="btn btn-outline-light mb-2"><i class="fas fa-pen"></i></button>
    <button class="btn btn-outline-light mb-2"><i class="fas fa-location-arrow"></i></button>
    <button id="zoom-in" class="btn btn-outline-light mb-2"><i class="fas fa-plus"></i></button>
    <button id="zoom-out" class="btn btn-outline-light mb-2"><i class="fas fa-minus"></i></button>
    <button class="btn btn-outline-light mb-2"><i class="fas fa-globe"></i></button>
    <button class="btn btn-outline-light mb-2"><i class="fas fa-layer-group"></i></button>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/tacmap.plugin.js"></script>
  <!-- инициализация tacMap -->
  <script>
    $(function() {
      $(document).tacMap(); 
    });
  </script>
  <script src="/assets/minute-frame.plugin.js"></script>
  <!-- инициализация minuteGrid -->
  <script>
    $('#map-background').minuteGrid({
      stepPx: 100,
      showLabels: true,
      scale: 1
    });
  </script>
  <script>
    $(document).on('contextmenu', function(e) {
      e.preventDefault();
    });  
  </script>
</body>
</html>
