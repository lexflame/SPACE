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
    #map-background {
      width: <?=$dif[1]?>px;
      top: <?=$posTopMapWrapper?>px;
      left: <?=$startMapPosLeft?>px;
    }
    #layers_of_marker {
      width: <?=$widthMapWrapper?>px;
      height: <?=$heightMapWrapper?>px;
    }
    .layers_of_map_part {
      grid-template-columns: repeat(<?=$flex_count?>, 1fr);
      grid-template-rows: repeat(<?=$flex_count?>, <?=$height_flex_box?>px);
      width: <?=$dif[1]?>px;
    }
    .layers_of_map_part .flex_box {
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
    .horizontal_ruler {
      width: <?=$widthMapWrapper?>px;
    }
    .vertical_ruler {
      height: <?=$heightMapWrapper?>px;
    }
    .horizontal_ruler .line_marker {
      left: -<?=$posLeftMapWrapper - 480?>px;
    }
    .vertical_ruler .line_marker {
      top: -<?=$posTopMapWrapper - 480?>px;
    }
  </style>
</head>
<body>
  <div id="page-fadeout"></div>
  <!-- Фон карты -->
  <div class="topBox"></div>
  <div class="horizontal_ruler">
    <div class="line_marker"></div>
  </div>
  <div class="vertical_ruler">
    <div class="line_marker"></div>
  </div>
  <div id="map-wrapper" data-id="<?=$id?>">
    <div id="map-background">
      <div class="layers_of_map_part" id="layers_of_map_part">
        <? foreach ($src as $key => $item) { ?>
            <div 
              class='flex_box' 
              data-key="<?=$key?>" 
              data-src="<?=$item['img_src']['pathToThumb'];?>"
            >
            </div>
        <? } ?>
      </div>
      <div id="layers_of_marker" data-lock="0"></div>
    </div>
  </div>

  <!-- Верхняя панель -->
  <div class="top-bar d-flex justify-content-between align-items-center px-3 py-4 hidden-box" style="top: 103px;width: 35%;right: 104px;color: #fff;height:200px;">
    <div id="posx"></div>
    <div id="posy"></div>
    <div id="scale_pos"></div>
    <div id="event_pos"></div>
    <div id="cons_debug"></div>
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
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  
  <script src="/assets/js/tacmap.plugin.js"></script>
  <script src="/assets/js/map-marker.plugin.js"></script>
  <script src="/assets/js/map-nav.plugin.js"></script>
  <script src="/assets/js/minute-frame.plugin.js"></script>
  <script>
    $('.flex_box').on('mouseenter', function (e) {
      
      var obj = $('#map-background');
      var transformMatrix = obj.css("transform");
      var matrix = transformMatrix.replace(/[^0-9\-.,]/g, '').split(',');

      if(parseInt(matrix[0]) > 0){
        $(this).css("background-image", "url('" + $(this).data('src').replace('thumb_','') + "')");
      }

    });
    // var pos = [];
    // $('#map-background').on('mousemove', function(event) {
    //   const layersDiv = document.getElementById('layers_of_map_part');
    //   layersDiv.addEventListener('mousemove', function(event) {
    //     const rects = this.getClientRects();
    //     if (rects.length > 0) {
    //       pos.rect = rects[0];
    //       pos.x = event.clientX - rect.left-20;
    //       pos.y = event.clientY - rect.top-20;
    //       console.log(pos)
    //     }
    //   });
    // });
    $('.layers_of_map_part').on('dblclick', function(event) {
        $('#layers_of_marker').dblclick()
    });
  </script>
  <script>
    // Image for transition
    $('#map-background').fadeOut();
    $('.layers_of_map_part').find('.flex_box').each(function( indx,box ){
      var image = new Image();
      image.src = $(box).data('src');
      image.onload = function () {
        $(box).css("background-image", "url('" + image.src + "')");
      };
    });
    $('#map-background').fadeIn();
  </script>
  <script>
    $( function() {
      var draggable = $( "#map-background" ).draggable(
          { 
            containment: "#map-wrapper", 
            scroll: false,
            drag: function() {
             var horizontal_ruler_pos = parseInt($(this).css("left"))-980;
             var vertical_ruler_pos = parseInt($(this).css("top"))-1900;
             $('.horizontal_ruler').find('.line_marker').css('left',''+horizontal_ruler_pos+'px')
             $('.vertical_ruler').find('.line_marker').css('top',''+vertical_ruler_pos+'px')
            },
          });
      });
  </script>
  <script>
    $(function() {
      $('#map-wrapper').markerMap({
        apiUrl: '/tacmap',          // API CodeIgniter
        storageKey: 'markerMap'
      });
    });
    $('#layers_of_map_part').on('mousemove', function(event_prt) {
      $(this).markerMap('mousemove',event_prt)
    });
    $('#layers_of_marker').on('dblclick', function(event) {
      $(this).markerMap('cenvasMarker',event);
    });
    $(function() {
        $(document).on('focusout', '.name_new_marker', function(){ 
            $(this).markerMap('fucusOutMark',this)
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
