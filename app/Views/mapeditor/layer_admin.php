<h2>Слои карты</h2>
<form method="post" action="/layeradmin/save" class="mb-3">
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="layer_id">
    <select name="map_id" class="form-control mb-1">
        <?php foreach($maps as $map):?>
            <option value="<?=$map['id']?>" <?= $map['id']==$map_id?'selected':'' ?>><?= esc($map['name']) ?></option>
        <?php endforeach;?>
    </select>
    <input name="name" id="layer_name" placeholder="Название слоя" class="form-control mb-1" required>
    <input name="sort_order" id="layer_sort" type="number" class="form-control mb-1" placeholder="Порядок" value="0">
    <button class="btn btn-success">Сохранить</button>
</form>
<table class="table table-dark">
  <tr><th>Слой</th><th>Видимость</th><th>Сортировка</th><th></th></tr>
  <?php foreach($layers as $layer): ?>
    <tr>
      <td><?=esc($layer['name'])?></td>
      <td><?= $layer['visible']?'да':'нет' ?></td>
      <td><?=esc($layer['sort_order'])?></td>
      <td>
        <form action="/layeradmin/delete/<?=$layer['id']?>" method="post" style="display:inline-block;">
          <?=csrf_field()?>
          <button class="btn btn-danger btn-xs" onclick="return confirm('Удалить?')">X</button>
        </form>
      </td>
    </tr>
  <?php endforeach;?>
</table>
