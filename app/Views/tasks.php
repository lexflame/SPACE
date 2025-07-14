<!DOCTYPE html>
<html lang="ru" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Менеджер задач (Toist)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- dragula for drag-and-drop (npm: dragula, можно CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.css">
    <style>
        body { background: #18191b; }
        .main-panel { max-width:1100px; margin:auto; }
        .task-card { background: #23272b;}
        .done .task-title { text-decoration: line-through; color: #9adfa7;}
        .list-group { min-height:400px; }
        .tasks-list li { cursor:grab; user-select:none; }
        .dragging { opacity:.52; background:#243c44!important;}
        .badge-tag { background:#317488cc; font-size:.95em; margin:0 2px;}
        .file-link { color:#7ddcdd;}
        .filter-area { background: #262930; border-radius: 9px; }
        .kanban-col { background: #20262e; min-height:350px; margin:0 4px; border-radius:8px; box-shadow:0 2px 8px #0002; padding: 7px;}
        .kanban-col-header { font-weight:bold; color:#999; text-align:center; border-bottom:1px solid #333; margin-bottom:7px;}
        .kanban-card { background:#23272b; margin:4px 0; border-radius:7px; padding:7px; box-shadow:0 1px 6px #0001; cursor:grab; }
        .kanban-card.done {opacity:.5;}
        .kanban-card:hover {background:#32373d;}

    </style>
</head>
<body>
<div class="container py-5 main-panel">
    <!--h1 class="shine-title text-center mb-3 text-light">Менеджер задач</h1-->
    <!-- Фильтры -->
    <div class="row filter-area p-3 mb-4 align-items-end">
        <div class="col-md-3">
          <label class="form-label text-light">Проект</label>
          <select id="filter-project" class="form-select">
            <option value="">Все</option>
            <?php foreach($projects as $p): ?>
            <option value="<?=$p['id']?>"><?=$p['name']?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label text-light">Статус</label>
          <select id="filter-status" class="form-select">
            <option value="">Все</option>
            <option value="0">Открытые</option>
            <option value="1">Выполненные</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label text-light">Тег</label>
          <input type="text" id="filter-tag" class="form-control" placeholder="например: важное">
        </div>
        <div class="col-md-2">
          <label class="form-label text-light">Поиск</label>
          <input type="text" id="filter-search" class="form-control" placeholder="По поиску">
        </div>
        <div class="col-md-2">
          <button id="filter-btn" class="btn btn-warning w-100">Применить</button>
        </div>
    </div>

    <!-- Новая задача -->
    <form id="task-form" class="mb-4 bg-dark rounded p-3">
      <div class="row g-2 align-items-end">
        <div class="col-md-3">
          <input name="title" required type="text" class="form-control" placeholder="Заголовок">
        </div>
        <div class="col-md-2">
          <input name="due_date" type="date" class="form-control" placeholder="Дата">
        </div>
        <div class="col-md-2">
          <input name="cost" type="number" step="0.01" class="form-control" placeholder="Стоимость">
        </div>
        <div class="col-md-2">
          <select name="project_id" class="form-select">
            <?php foreach($projects as $p): ?>
            <option value="<?=$p['id']?>"><?=$p['name']?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-md-3">
          <input name="tags" type="text" class="form-control" placeholder="теги (через запятую)">
        </div>
        <div class="col-md-12 mt-2">
          <input name="description" type="text" class="form-control" placeholder="Описание">
        </div>
        <div class="col-md-6 mt-2">
          <input name="repeat_rule" type="text" class="form-control" placeholder="Повтор (например: daily, weekly, custom)">
        </div>
        <div class="col-md-3 mt-2">
          <input name="map" type="text" class="form-control" placeholder="Карта (ид)">
        </div>
        <div class="col-md-3 mt-2">
          <input name="map_marker" type="text" class="form-control" placeholder="Метка на карте (координаты)">
        </div>
        <div class="col-md-6 mt-2">
          <input name="files[]" type="file" class="form-control" multiple>
        </div>
        <div class="col-md-2 mt-2">
          <button type="submit" class="btn btn-success w-100">+</button>
        </div>
      </div>
    </form>

    <!-- Список задач -->
    <ul class="list-group tasks-list" id="tasks-list"></ul>
</div>


<div id="kanban-board" style="display:none;">
  <div class="row" id="kanban-cols">
    <div class="col kanban-col" data-status="НОВОЕ"><div class="kanban-col-header">Новые</div><div class="kanban-tasks"></div></div>
    <div class="col kanban-col" data-status="В РАБОТЕ"><div class="kanban-col-header">В работе</div><div class="kanban-tasks"></div></div>
    <div class="col kanban-col" data-status="ГОТОВО"><div class="kanban-col-header">Готово</div><div class="kanban-tasks"></div></div>
  </div>
</div>


<!-- dragula, можно поменять на SortableJS — используется для drag'n'drop -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const siteUrl = '<?= site_url('tasks/') ?>';

function escapeHtml(txt) { return txt.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#039;'}[m])); }

function renderTask(task) {
  let tags = (task.tags||'').split(',').map(t=>t.trim()).filter(Boolean);
  let files = (task.files||[]).map(f=>`<a href="${f.url}" target="_blank" class="file-link">${escapeHtml(f.filename)}</a>`).join(' ');
  return `<li class="list-group-item mb-2 p-3 ${task.is_done==1?'done':''}" data-id="${task.id}">
    <div class="d-flex">
      <div class="me-3 pt-2">
        <input type="checkbox" onchange="toggleTask(${task.id})" ${task.is_done==1?'checked':''}>
      </div>
      <div class="flex-grow-1">
        <div>
          <span class="fw-bold task-title">${escapeHtml(task.title)}</span>
          ${task.due_date && task.due_date!='0000-00-00'?`<span class="badge bg-info ms-2">${task.due_date}</span>`:''}
          ${task.cost ? `<span class="badge bg-warning ms-2">💰${task.cost}</span>`: ''}
          ${task.project_id ? `<span class="badge bg-secondary ms-2">Проект #${task.project_id}</span>` : ''}
          ${task.repeat_rule ? `<span class="badge bg-dark ms-2">${escapeHtml(task.repeat_rule)}</span>`: ''}
        </div>
        <div class="small text-secondary">${escapeHtml(task.description||'')}</div>
        <div>
          ${tags.map(t=>`<span class="badge badge-tag">${escapeHtml(t)}</span>`).join('')}
        </div>
        <div>
          ${task.map?'⛳ <span class="badge bg-info">Карта: '+escapeHtml(task.map)+'</span>':''}
          ${task.map_marker?'📍<span class="badge bg-success">Метка: '+escapeHtml(task.map_marker)+'</span>':''}
        </div>
        <div>${files}</div>
      </div>
      <div>
        <button class="btn btn-sm btn-outline-secondary me-1" onclick="editTask(${task.id})" title="Ред.">✏</button>
        <button class="btn btn-sm btn-outline-danger" onclick="deleteTask(${task.id})" title="Удалить">✕</button>
      </div>
    </div>
  </li>`;
}

function loadTasks() {
  let data = {
    project_id:document.getElementById('filter-project').value,
    tag:document.getElementById('filter-tag').value,
    is_done:document.getElementById('filter-status').value,
  };
  fetch(siteUrl+'list?'+(new URLSearchParams(data))).then(r=>r.json()).then(tasks=>{
    let q = document.getElementById('filter-search').value.trim().toLowerCase();
    if(q) tasks = tasks.filter(t=>(t.title+t.description).toLowerCase().includes(q));
    // загрузи вложения (DEMO)
    let html = tasks.map(renderTask).join('');
    document.getElementById('tasks-list').innerHTML = html;
  });
}
loadTasks();

document.getElementById('task-form').onsubmit = function(e){
  e.preventDefault();
  let form = e.target, data = new FormData(form);
  fetch(siteUrl+'add', { method:'POST', body:data })
    .then(r=>r.json())
    .then(()=>{form.reset(); loadTasks();});
};
// Фильтры
document.getElementById('filter-btn').onclick = loadTasks;

// Edit, toggle, delete (аналогично предыдущему примеру)
function toggleTask(id) { fetch(siteUrl+'toggle/'+id, {method:'POST'}).then(()=>loadTasks()); }
function deleteTask(id){ fetch(siteUrl+'delete/'+id, {method:'POST'}).then(()=>loadTasks()); }
function editTask(id){ /* Аналогично предыдущему примеру открывай/заполняй форму редактирования */ }

// dragula — инициализация drag&drop
dragula([document.getElementById('tasks-list')]).on('drop', function(el, target, source, sibling){
    // Тут отправь на сервер новый порядок или смену проекта
    // пример: fetch(siteUrl+'reorder', {method:'POST',body:...})
});

// shine-title для заголовка:
</script>
<style>
.shine-title {
  display: block; position: relative; font-size: 2.3rem; color:#fff;
  background: linear-gradient(110deg,#b39c6a 0%, #eee 33%, #fffde4 40%, #b19142 55%, #e5e2cf 60%, #a57227 77%, #ffe 100%);
  background-size: 200% 100%;
  background-position: -100% 0;
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  background-clip: text; text-fill-color: transparent; filter: drop-shadow(0 2px 14px #fff1);
  animation: shine-move 2.8s cubic-bezier(.61,0,.55,1) infinite forwards;
}
@keyframes shine-move {
  0%{background-position:-100% 0;}
  60%{background-position:120% 0;}
  100%{background-position:120% 0;}
}
</style>

<button id="toggle-forecast" class="btn btn-outline-primary mb-3">Прогноз</button>
<button id="toggle-list2" class="btn btn-outline-secondary mb-3" style="display:none;">Обычный список</button>
<div id="forecast-board" style="display:none;"></div>

<script>
document.getElementById('toggle-forecast').onclick = function(){
  document.getElementById('tasks-list').style.display='none';
  document.getElementById('kanban-board').style.display='none';
  document.getElementById('forecast-board').style.display='';
  this.style.display='none';
  document.getElementById('toggle-list2').style.display='';
  loadForecast();
};
document.getElementById('toggle-list2').onclick = function(){
  document.getElementById('tasks-list').style.display='';
  document.getElementById('kanban-board').style.display='none';
  document.getElementById('forecast-board').style.display='none';
  this.style.display='none';
  document.getElementById('toggle-forecast').style.display='';
};

function loadForecast() {
  fetch('<?=site_url('tasks/list')?>')  // лучше добавить особый роут для фильтра
    .then(r=>r.json())
    .then(tasks=>{
      // Группируем по дате (следующие 7/14 дней)
      let days = {};
      let today = new Date();
      for(let i=0; i<14; ++i){
        let d = new Date(today); d.setDate(today.getDate()+i);
        let key = d.toISOString().slice(0,10);
        days[key] = [];
      }
      tasks.forEach(t=>{
        if(t.due_date && days[t.due_date]) days[t.due_date].push(t);
      });
      let html = Object.keys(days).map(date=>{
        let ds = days[date];
        if(!ds.length) return '';
        return `<div class="mb-3">
          <div class="fw-bold text-info">${date}</div>
          <ul class="list-group">${ds.map(t=>"<li class='list-group-item bg-dark text-light'>"+escapeHtml(t.title)+"</li>").join('')}</ul>
        </div>`;
      }).join('');
      document.getElementById('forecast-board').innerHTML = html || '<div class="text-secondary">Нет будущих задач</div>';
    });
}
</script>



</body>
</html>
