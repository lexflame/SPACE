<div style="margin: 15px;">
<h1>Форма карты</h1>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/darkly/bootstrap.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<form method="post" action="/mapmanager/saveMap">
    <?= csrf_field() ?>
    <div>
      <label>Название:</label>
      <input type="text" name="name" required class="form-control">
    </div>
    <div>
      <label>Проекция</label>
      <select name="projection" class="form-control">
        <option value="mercator">Меркатор</option>
        <option value="globe">Глобус</option>
      </select>
    </div>
    <div>
      <label>Путь к изображению (опц.)</label>
      <input type="file" name="image_path" class="form-control">
    </div>
    <button class="btn btn-success mt-2">Сохранить</button>
</form>
</div>