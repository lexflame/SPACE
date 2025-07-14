<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Навигация по маршрутам</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Leaflet + Draw -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css"/>
    <style>
        #map { height: 480px; }
        .leaflet-container { background: #fcfcfc; border-radius: .5em; }
    </style>
</head>
<body class="container py-3">
    <header class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="fs-3">Ваши маршруты на карте</h1>
        <a href="/logout" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-right"></i> Выйти</a>
    </header>

    <section class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-light">
                    <strong>Импортировать GPX</strong>
                </div>
                <div class="card-body">
                    <form id="gpxForm" enctype="multipart/form-data" class="row g-2">
                        <div class="col-8">
                            <input type="file" id="gpxFile" class="form-control" accept=".gpx" required>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Загрузить GPX
                            </button>
                        </div>
                    </form>
                    <small class="text-muted">Маршрут автоматически добавится в ваш список.</small>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <b>Добавить или редактировать маршрут на карте</b>
                </div>
                <div class="card-body">
                    <form id="routeForm" class="row g-2 align-items-end">
                        <div class="col-12 mb-1">
                            <input type="text" name="name" id="routeName" class="form-control"
                                   placeholder="Название маршрута" required>
                        </div>
                        <input type="hidden" name="coordinates" id="routeCoords">
                        <div class="col-auto me-2">
                            <button type="submit" class="btn btn-success" id="saveBtn"><i class="bi bi-check2"></i> Сохранить маршрут</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-secondary" id="resetBtn"><i class="bi bi-x-lg"></i> Сбросить</button>
                        </div>
                        <div class="col-auto">
                            <span id="editState" class="badge text-bg-warning d-none"><i class="bi bi-pencil"></i> Редактирование</span>
                        </div>
                    </form>
                    <small class="text-muted">Нарисуйте линию <span class="badge text-bg-info">Polyline</span> на карте для нового маршрута или нажмите <span class="text-primary">Редактировать</span>.</small>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <strong>Список маршрутов <span class="text-success">(автообновление)</span></strong>
                </div>
                <div class="card-body p-0">
                    <ul id="routesList" class="list-group list-group-flush"></ul>
                </div>
            </div>
        </div>
    </section>

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
            <b>Карта (рисуйте, импортируйте, кликайте по маршрутам)</b>
        </div>
        <div class="card-body p-0 bg-body-secondary">
            <div id="map"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
    <script>
    const map = L.map('map').setView([55.75, 37.62], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const drawnItems = new L.FeatureGroup().addTo(map);
    let drawnPolyline = null, editingId = null, editingLayer = null;

    const drawControl = new L.Control.Draw({
        draw: { polygon: false, marker: false, circle: false, rectangle: false, circlemarker: false },
        edit: { featureGroup: drawnItems }
    });
    map.addControl(drawControl);

    map.on(L.Draw.Event.CREATED, function(e){
        if (drawnPolyline) drawnItems.removeLayer(drawnPolyline);
        drawnPolyline = e.layer;
        drawnItems.addLayer(e.layer);
        let geojson = drawnPolyline.toGeoJSON();
        document.getElementById('routeCoords').value = JSON.stringify(geojson.geometry);
        editingId = null;
        document.getElementById('editState').classList.add('d-none');
    });

    document.getElementById('gpxForm').onsubmit = function(e){
        e.preventDefault();
        let file = document.getElementById('gpxFile').files[0];
        if (!file || !file.name.endsWith('.gpx')) return alert('Выберите GPX!');
        let fd = new FormData();
        fd.append('file', file);
        fetch('/api/routes/upload', { method: 'POST', body: fd })
            .then(r=>r.json())
            .then(()=>{
                document.getElementById('gpxForm').reset();
            })
            .catch(()=>alert('Ошибка GPX!'));
    };

    // SSE: реактивная загрузка маршрутов
    if ('EventSource' in window) {
        const evtSource = new EventSource('/events/routes');
        evtSource.addEventListener('routes', function(event) {
            const routes = JSON.parse(event.data);
            updateRoutes(routes);
        }, false);
    } else {
        setInterval(fetchRoutes, 5000);
        fetchRoutes();
    }

    function updateRoutes(routes)
    {
        drawnItems.clearLayers();
        const list = document.getElementById('routesList');
        list.innerHTML = '';
        routes.forEach(route => {
            if (route.coordinates && route.coordinates.type === 'LineString') {
                const layer = L.geoJSON(route.coordinates).addTo(drawnItems);
                layer.bindPopup(
                    `<b>${route.name}</b><br>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editRoute(${route.id});return false;"><i class="bi bi-pencil"></i> Редактировать</button> 
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRoute(${route.id});return false;"><i class="bi bi-trash"></i> Удалить</button>`
                );
                layer._routeId = route.id;

                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center py-2';
                li.innerHTML = `<span onclick="zoomRoute(${route.id})" style="cursor:pointer;" title="Показать на карте">${route.name}</span>
                    <span>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="zoomRoute(${route.id});return false;" title="На карте"><i class="bi bi-pin-map"></i></button>
                        <button class="btn btn-sm btn-outline-secondary me-1" onclick="editRoute(${route.id});return false;" title="Редактировать"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteRoute(${route.id});return false;" title="Удалить"><i class="bi bi-trash"></i></button>
                    </span>`;
                list.appendChild(li);
            }
        });
    }

    window.zoomRoute = function(id) {
        drawnItems.eachLayer(layer => {
            if (layer._routeId === id) {
                map.fitBounds(layer.getBounds());
                layer.openPopup();
            }
        });
    }

    window.deleteRoute = function(id) {
        if (confirm('Удалить маршрут?')) {
            fetch('/api/routes/'+id, { method:'DELETE' }).then(()=>{});
        }
    }

    window.editRoute = function(id) {
        fetch('/api/routes/'+id).then(r=>r.json()).then(route=>{
            document.getElementById('routeName').value = route.name;
            document.getElementById('routeCoords').value = JSON.stringify(route.coordinates);
            document.getElementById('editState').classList.remove('d-none');
            if (drawnPolyline) drawnItems.removeLayer(drawnPolyline);
            if (editingLayer) drawnItems.removeLayer(editingLayer);

            editingLayer = L.geoJSON(route.coordinates).addTo(drawnItems);
            drawnPolyline = editingLayer;
            editingId = route.id;
            editingLayer.eachLayer(function (layer) {
                layer.editing && layer.editing.enable();
                layer.on('edit', function () {
                    document.getElementById('routeCoords').value = JSON.stringify(layer.toGeoJSON().geometry);
                });
            });
        });
    }

    document.getElementById('routeForm').onsubmit = function(e){
        e.preventDefault();
        const name = document.getElementById('routeName').value.trim();
        const coordsStr = document.getElementById('routeCoords').value;
        if (!name || !coordsStr) return alert('Дайте имя и нарисуйте маршрут');
        let verb = editingId ? 'PUT' : 'POST';
        let url = editingId ? '/api/routes/'+editingId : '/api/routes';
        fetch(url,{
            method: verb,
            headers: {'Accept':'application/json','Content-Type':'application/json'},
            body: JSON.stringify({ name:name, coordinates:JSON.parse(coordsStr) })
        }).then(r=>r.json())
        .then(()=>{
            document.getElementById('routeForm').reset();
            editingId = null;
            document.getElementById('editState').classList.add('d-none');
            if (drawnPolyline) drawnItems.removeLayer(drawnPolyline);
        });
    };

    document.getElementById('resetBtn').onclick = function(){
        document.getElementById('routeForm').reset();
        document.getElementById('editState').classList.add('d-none');
        editingId = null;
        if (drawnPolyline) drawnItems.removeLayer(drawnPolyline);
        if (editingLayer) drawnItems.removeLayer(editingLayer);
    };
    </script>
</body>
</html>
