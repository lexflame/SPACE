
<link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/darkly/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
  body, html {margin:0;padding:0;}
  .top-panel {
    /*position: fixed;*/
    top: 0; left: 0; right: 0;
    height: 40px;
    z-index: 100;
    background: #212529;
    box-shadow: 0 2px 6px #0006;
    display: flex;
    align-items: center;
  }
  .top-panel .btn-panel-group {
    display: flex;
    gap: 36px;
    margin: 0 auto;
  }
  .top-panel .panel-btn {
    background: none;
    border: 1px solid #CCC;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #f8f9fa;
    transition: color 0.15s, background 0.15s;
    font-size: 1em;
    outline: none;
    min-width: 64px;
    border-radius: 5px;
  }
  .top-panel .panel-btn .icon {
    width: 44px;
    /*height: 44px;*/
    border-radius: 50%;
    background: #282c34;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4em;
    margin-bottom: 3px;
  }
  .top-panel .panel-btn:hover .icon {
    background: #404654;
    color: #FFD700;
    box-shadow: 0 0 8px #FFD70044;
  }
  .top-panel .panel-btn .text {
    font-size: 0.95em;
    line-height: 1.1;
    color: #e0e0e0;
    padding-bottom: 0;
  }
  #mainContent { padding-top: 100px; }
  @media (max-width: 600px) {
    .top-panel .btn-panel-group { gap: 10px; }
    .top-panel { height:90px;}
    .top-panel .panel-btn .icon { width:38px;height:38px;font-size:1.1em;}
    .top-panel .panel-btn .text { font-size: 0.7em;}
  }
</style>

  <nav class="top-panel">
    <div class="btn-panel-group">
      
      <a href="<?= site_url('tasks_to') ?>">
        <button class="panel-btn" title="Задачи">
          <!--span class="icon"><i class="fa-solid fa-list-check"></i></span-->
          <span class="text">Задачи</span>
        </button>
      </a>
      
      <a href="<?= site_url('mapmanager') ?>">
        <button class="panel-btn" title="Карты">
          <!--span class="icon"><i class="fa-solid fa-map"></i></span-->
          <span class="text">Карты</span>
        </button>
      </a>

      <button class="panel-btn" title="Земтки">
        <!--span class="icon"><i class="fa-solid fa-map-pin"></i></span-->
        <span class="text">Земтки</span>
      </button>
      <button class="panel-btn" title="Отладчик">
        <!--span class="icon"><i class="fa-solid fa-bug"></i></span-->
        <span class="text">Отладчик</span>
      </button>
      <button class="panel-btn" title="Викер">
        <!--span class="icon"><i class="fa-solid fa-book"></i></span-->
        <span class="text">Викер</span>
      </button>
      <button class="panel-btn" title="Еще...">
        <!--span class="icon"><i class="fa-solid fa-ellipsis"></i></span-->
        <span class="text">Еще...</span>
      </button>
    </div>
  </nav>
