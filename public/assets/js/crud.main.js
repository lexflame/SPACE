// --- Эмуляция AJAX CRUD для задач ---
// Реальное API замените на свои урлы/методы
let serverDelay = 120;

function ajaxGetTasks(callback) {
  // localStorage эмуляция "GET /tasks"
  setTimeout(() => {
    let arr = JSON.parse(localStorage.getItem('task_list_v2')||'[]');
    callback(arr);
  }, serverDelay);
}
function ajaxAddTask(task, callback) {
  setTimeout(() => {
    let arr = JSON.parse(localStorage.getItem('task_list_v2')||'[]');
    arr.push(task);
    localStorage.setItem('task_list_v2', JSON.stringify(arr));
    callback(task);
  }, serverDelay);
}
function ajaxDeleteTask(id, callback) {
  setTimeout(() => {
    let arr = JSON.parse(localStorage.getItem('task_list_v2')||'[]');
    arr = arr.filter(x=>x.id!==id);
    localStorage.setItem('task_list_v2', JSON.stringify(arr));
    callback();
  }, serverDelay);
}
function ajaxUpdateTask(id, data, callback) {
  setTimeout(() => {
    let arr = JSON.parse(localStorage.getItem('task_list_v2')||'[]');
    let idx = arr.findIndex(x=>x.id===id);
    if (idx>=0) arr[idx]= {...arr[idx], ...data};
    localStorage.setItem('task_list_v2', JSON.stringify(arr));
    callback();
  }, serverDelay);
}

// --- Утилиты ---
function setCookie(name, value, days) {
  var expires = "";
  if (days) {
    var date = new Date();
    date.setTime(date.getTime() + (days*24*60*60*1000));
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "")  + expires + "; Path=/";
}
function getCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) {
      return decodeURIComponent(c.substring(nameEQ.length,c.length));
    }
  }
  return null;
}
function priorityIcon(pri) {
  let icons = {
    low:  '<span title="Низкий">⬇️</span>',
    normal: '<span title="Обычный">•</span>',
    high: '<span title="Высокий">⬆️</span>'
  };
  return icons[pri] || '•';
}
function formatDateTime(val) {
  if (!val) return '';
  let dt = new Date(val);
  if (isNaN(dt.valueOf())) return '';
  const pad=(n)=>n.toString().padStart(2,'0');
  return pad(dt.getDate())+'.'+pad(dt.getMonth()+1)+'.'+dt.getFullYear()+' '+pad(dt.getHours())+':'+pad(dt.getMinutes());
}
function uniqueId() {
  return 't'+Math.floor(Math.random()*1e7).toString(16)+(Date.now()%1e6).toString(16);
}

// --- UI logic ---
let tasks = [];
function renderTasks() {
  let filtered = tasks;
  // Фильтрация
  if($('#filterForm').is(':visible')) {
    let t = $('#filterText').val().toLowerCase();
    let d = $('#filterDate').val();
    let p = $('#filterPriority').val();
    filtered = tasks.filter(function(task){
      let ok = true;
      if(t) ok = task.title.toLowerCase().includes(t);
      if(ok && d) ok = (task.date && task.date.substring(0,10)===d);
      if(ok && p) ok = (task.priority===p);
      return ok;
    });
  }
  // Сортировка: дата, приоритет
  filtered.sort(function(a,b){
    let cmp = (a.date||'').localeCompare(b.date||'');
    if (cmp!==0) return cmp;
    let pri = {high:0,normal:1,low:2};
    return (pri[a.priority]||5)-(pri[b.priority]||5);
  });
  let $list = $('#tasksList').empty();
  filtered.forEach(function(task){
    let $li = $('<li class="task-item"></li>').attr('data-id', task.id);
    $li.append('<span class="priority">'+priorityIcon(task.priority)+'</span>');
    $li.append('<span class="task-title">'+$('<div>').text(task.title).html()+'</span>');
    $li.append('<span class="task-date">'+formatDateTime(task.date)+'</span>');
    $li.append('<span class="task-edit text-primary ml-3" title="Редактировать" style="cursor:pointer;">&#9998;</span>');
    $li.append('<span class="task-action text-danger" title="Удалить">&times;</span>');
    $list.append($li);
  });
}

// --- Event handlers ---
$(function(){
  // --- Тема ---
  let theme = getCookie('theme') || 'dark';
  $('html').attr('data-theme', theme);
  $('#themeToggle').prop('checked', theme==='dark');
  $('#themeToggle').on('change', function(){
    let th = $(this).prop('checked')? 'dark' : 'light';
    $('html').attr('data-theme', th);
    setCookie('theme', th, 360);
  });

  // --- Panel mode (tab) ---
  let panelMode = getCookie('panelMode') || 'create';
  function showPanelMode(mode) {
    if(mode==='create'){
      $('#createForm').show(); $('#filterForm').hide();
      $('#createTab').addClass('active'); $('#filterTab').removeClass('active');
    } else {
      $('#createForm').hide(); $('#filterForm').show();
      $('#createTab').removeClass('active'); $('#filterTab').addClass('active');
    }
    setCookie('panelMode', mode, 360);
    renderTasks();
  }
  $('#createTab, #filterTab').on('click', function(e){
    e.preventDefault();
    showPanelMode($(this).data('mode'));
  });
  showPanelMode(panelMode);

  // --- CRUD ---
  function reloadTasks() {
    ajaxGetTasks(function(arr){
      tasks = arr;
      renderTasks();
    });
  }
  reloadTasks();

  // --- Добавить задачу ---
  $('#createForm').submit(function(e){
    e.preventDefault();
    let task = {
      id: uniqueId(),
      title: $('#taskTitle').val().trim(),
      date: $('#taskDate').val(),
      priority: $('#taskPriority').val()
    };
    if (!task.title || !task.date || !task.priority) return;
    ajaxAddTask(task, function(){
      reloadTasks();
      $('#taskTitle').val('');
      $('#taskDate').val('');
      $('#taskPriority').val('low');
    });
  });

  // --- Удалить задачу ---
  $('#tasksList').on('click','.task-action', function(){
    let id = $(this).closest('.task-item').data('id');
    ajaxDeleteTask(id, reloadTasks);
  });

  // --- Фильтрация ---
  $('#filterForm input,#filterForm select').on('input change', renderTasks);
  $('#resetFilter').click(function(){
    $('#filterForm')[0].reset();
    renderTasks();
  });

  // --- Edit modal open ---
  $('#tasksList').on('click','.task-edit', function(){
    let id = $(this).closest('.task-item').data('id');
    let t = tasks.find(x=>x.id===id);
    if (!t) return;
    $('#editId').val(t.id);
    $('#editTitle').val(t.title);
    $('#editDate').val(t.date);
    $('#editPriority').val(t.priority);
    $('#editModal').modal('show');
  });

  // --- Сохранить редактирование ---
  $('#editForm').submit(function(e){
    e.preventDefault();
    let id = $('#editId').val();
    let newTask = {
      title: $('#editTitle').val().trim(),
      date: $('#editDate').val(),
      priority: $('#editPriority').val()
    };
    ajaxUpdateTask(id, newTask, function(){
      $('#editModal').modal('hide');
      reloadTasks();
    });
  });

  // --- чуть подправить высоту области задач ---
  function setTaskAreaTop() {
    let h = $('#panel').outerHeight();
    $('#tasks-area').css('top', h+'px');
  }
  setTaskAreaTop();
  $(window).on('resize', setTaskAreaTop);
});
