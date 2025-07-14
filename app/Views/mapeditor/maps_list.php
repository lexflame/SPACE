<div style="margin: 15px;">
  <h1>Список карт</h1>
<a href="/mapmanager/addMap" class="btn btn-primary mb-2">Добавить карту</a>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<table class="table table-dark">
  <tr>
    <th>Название</th>
    <th>Проекция</th>
    <th></th>
    <th></th>
  </tr>
  <?php foreach($maps as $map): ?>
  <tr>
    <td><?=$map['name']?></td>
    <td><?=$map['projection']?></td>
    <td><a href="/mapeditor/<?=$map['id']?>" class="btn btn-info btn-sm">Перейти</a></td>
    <td>
      <form method="post" action="/mapmanager/delete/<?=$map['id']?>">
        <?=csrf_field()?>
        <button class="btn btn-danger btn-sm" onclick="return confirm('Удалить?')">Удалить</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
</div>