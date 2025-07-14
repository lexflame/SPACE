<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Map Editor</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
    html,body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden !important;
        background: #181818;
    }
    #mainLayout {
        display: flex;
        flex-direction: column;
        height: 100vh;
        width: 100vw;
        overflow: hidden;
    }
    #toolbar {
        flex: 0 0 64px;
        background: #222;
        color: #fff;
        z-index: 10;
        box-shadow: 0 2px 6px #0008;
    }
    #mapArea {
        flex: 1 1 auto;
        height: 100%;
        width: 100%;
        position: relative;
        overflow: hidden;
        background: #181818;
    }
    #leafletMap, #globeMap {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        width: 100%; height: 100%;
        z-index: 1;
    }
    .leaflet-container {
      background: #202020;
    }
    /* Полноэкранный модал */
    .modal-fullscreen {
        padding: 0 !important;
    }
    .modal-content { background: #20232a; color: #fff;}
    </style>
</head>
<body>
<div id="mainLayout">
    <!-- Toolbar -->
    <nav id="toolbar" class="navbar navbar-dark navbar-expand px-3">
        <a class="navbar-brand fw-bold" href="#"><i class="fa-solid fa-map" style="color:gold;"></i> MapEditor</a>
        <div class="ms-2 d-flex flex-row flex-nowrap gap-2 align-items-center">
            <a href="/mapmanager">
            <button type="button" id="addMarkerBtn" class="btn btn-outline-success btn-sm" title="Назад к списку карт">
                <    
            </button>
            </a>
            <button type="button" id="addMarkerBtn" class="btn btn-outline-success btn-sm" title="Добавить метку">
                <i class="fa-solid fa-plus"></i>
            </button>
            <button type="button" id="layersBtn" class="btn btn-outline-secondary btn-sm" title="Слои">
                <i class="fa-solid fa-layer-group"></i>
            </button>
            <button type="button" id="categoriesBtn" class="btn btn-outline-secondary btn-sm" title="Категории">
                <i class="fa-solid fa-tags"></i>
            </button>
            <select id="projectionSelect" class="form-select form-select-sm bg-dark text-white" aria-label="Projection">
                <option value="mercator">Меркатор 2D</option>
                <option value="globe">Глобус 3D</option>
            </select>
            <select id="selectMap" class="form-select form-select-sm bg-dark text-white ms-2" aria-label="Maps">
                <?php foreach($maps as $map):?>
                <option value="<?=$map['id']?>"><?=esc($map['name'])?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="ms-auto d-flex gap-2">
            <button type="button" id="fullscreenBtn" class="btn btn-outline-light btn-sm" title="Во весь экран">
                <i class="fa-solid fa-expand"></i>
            </button>
            <!-- Можно добавить меню профиля/настроек -->
        </div>
    </nav>
    <!-- Карта -->
    <main id="mapArea" path="<?=esc($current['path_back'])?>">
        <div id="leafletMap" style="display:block;"></div>
        <div id="globeMap" style="display:none;"></div>
        <!-- Всплывающие Меню/Окна можно сюда -->
    </main>
</div>

<!-- Модальные окна метки/слоев/категорий могут быть даже вне mainLayout (чтобы перекрывать карту полностью) -->
<div class="modal fade" id="markerModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-fullscreen">
    <form class="modal-content" id="markerForm">
      <div class="modal-header">
        <h5 class="modal-title">Метка</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body"><!-- ... --></div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
        <button class="btn btn-primary">Сохранить</button>
      </div>
    </form>
  </div>
</div>
<!-- Аналогичные модалки для слоёв, категорий -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- Optionally Globe.gl and TinyMCE -->
<script src="/assets/mapeditor_advanced.js"></script>
<script>
document.documentElement.style.overflow = "hidden";
document.body.style.overflow = "hidden";
</script>
</body>
</html>
