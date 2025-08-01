<!-- TACMap HTML -->
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Карта: <?=$name?></title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/tacmap.css">
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
      /*top: 0; left: 0;*/
      width: <?=$dif[1]?>px;
      background-image: url(<?//=$path?>); /* карта из видеоигры */
      background-size: cover;
      background-position: center;
      z-index: 0;
      background-repeat: no-repeat;
      background-position: center;
      transition: background-position 0.1s linear;
      cursor: grab;
      user-select: none;
      disp2lay: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      top: <?=$posTopMapWrapper?>px;
      left: <?=$startMapPosLeft?>px;
    }
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
    .opacity_temp {
      opacity: 0;
      transition: background-position 0.1s linear;
    }
    #layers_of_marker {
      display: block;
      width: 100%;
      height: 100vh;
      position: absolute;
      top: 0px;
      left: 0px;
      width: <?=$widthMapWrapper?>px;
      height: <?=$heightMapWrapper?>px;
    }
    .item_marker {
      width: 20px;
      height: 20px;
      border-radius: 15px;
      background-color: red;
      transition: background-position 0.1s linear;
      background: radial-gradient(circle, rgba(131, 58, 180, 1) 0%, rgba(253, 29, 29, 1) 50%, rgba(252, 176, 69, 1) 100%);
      box-shadow: blue;
      box-shadow: 2px 0px 14px 16px rgba(34, 60, 80, 0.45);
    }
    .minute-grid {
      /*display: none !important;*/
    }
    .layers_of_map_part {
      display: grid;
      grid-template-columns: repeat(<?=$flex_count?>, 1fr);
      grid-template-rows: repeat(<?=$flex_count?>, <?=$height_flex_box?>px);
      gap: -1px;
      wid1th: 80%;
      max-wid1th: 800px;
      width: <?=$dif[1]?>px;
    }
    .layers_of_map_part .flex_box {
      background-repeat: no-repeat;
      background-size: cover;
      display: flex;
      justify-content: center; /* Центрирование содержимого по горизонтали */
      align-items: center; /* Центрирование содержимого по вертикали */
      color: white; /* Цвет текста */
      font-size: 24px; /* Размер шрифта */
      width: <?=$width_flex_box?>px;
      height: <?=$height_flex_box?>px;
    }
    #map-wrapper 
    {
      width: <?=$widthMapWrapper?>px;
      height: <?=$heightMapWrapper?>px;
      top: -<?=$posTopMapWrapper?>px;
      left: -<?=$posLeftMapWrapper?>px;
    }
  </style>
</head>
<body style="transf3orm: scale(0.1);">
  <div id="page-fadeout"></div>
  <!-- Фон карты -->
  <div id="map-wrapper" data-id="<?=$id?>" style="bo2rder: 1px solid #fff;">
    <div id="map-background">
      <div class="layers_of_map_part">
        <? foreach ($src as $key => $item) { ?>
            <div class='flex_box' data-key="<?=$key?>" style="background-image: url(<?=$item['img_src']['pathToThumb'];?>);"></div>
        <? } ?>
      </div>
      <div id="layers_of_marker"></div>
    </div>
  </div>

  <!-- Верхняя панель -->
  <div class="top-bar d-flex justify-content-between align-items-center px-3 py-4 hidden-box" style="top: 103px;width: 35%;right: 104px;color: #fff;">
    <div id="posx"></div>
    <div id="posy"></div>
    <div id="scale_pos"></div>
    <div id="event_pos"></div>
  </div>
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
  <div class="left-sidebar d-flex flex-column align-items-center p-2 text-center hidden-box">
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
  <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/tacmap.plugin.js"></script>
  <script src="/assets/js/map-marker.plugin.js"></script>
  <script src="/assets/js/map-nav.plugin.js"></script>
  <script>
  $( function() {
    $( "#map-background" ).draggable({ containment: "#map-wrapper", scroll: false });
  } );
  </script>
  <script>
    $(function() {
      $('#map-wrapper').markerMap({
        apiUrl: '/tacmap',          // API CodeIgniter
        storageKey: 'markerMap'
      });
    });
  </script>
  <!-- инициализация tacMap -->
  <script>
    $(function() {
      $(document).tacMap(); 
      $(this).MapNav('setWrapper') 
    });
  </script>
  <script src="/assets/js/minute-frame.plugin.js"></script>
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
      // e.preventDefault();
    });  
  </script>
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
    $(function () {
      let isMouseDown = false;
      let $target = null;
      let offset = { x: 0, y: 0 };

      $('#map-background').on('mousedown', function (e) {
        if (e.which !== 1) return; // Только левая кнопка

        isMouseDown = true;
        $target = $(this);
        offset = $target.offset();
        $target.css('cursor', 'grabbing');
      });

      $('.flex_box').on('mouseover', function (e) {
        // console.log($(this).data('key'))
      });

      $(document).on('mouseup', function () {
        if ($target) {
          isMouseDown = false;
          $target.css({
            'cursor': 'grab',
            // 'background-position': 'center' // Убери эту строку, если не хочешь возвращать
          });
          $target = null;
        }
      });
    });
    </script>
</body>
</html>
