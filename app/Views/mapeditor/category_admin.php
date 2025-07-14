<h2>Категории меток</h2>
<form method="post" action="/categoryadmin/save" class="mb-3">
    <?= csrf_field() ?>
    <input type="hidden" name="id" id="category_id">
    <input name="name" id="cat_name" placeholder="Название" class="form-control mb-1" required>
    <input type="color" name="color" id="cat_color" value="#FFD700" class="form-control mb-1" style="width: 4em;">
    <input name="icon" id="cat_icon" placeholder="fa-solid fa-sword" class="form-control mb-1">
    <button class="btn btn-success">Сохранить</button>
</form>
<table class="table table-dark">
  <tr><th>Имя</th><th>Цвет</th><th>Иконка</th><th></th></tr>
  <?php foreach($categories as $cat): ?>
    <tr>
      <td><?=esc($cat['name'])?></td>
      <td><span style="background:<?=$cat['color']?>;padding:0.5em;"><?=esc($cat['color'])?></span></td>
      <td><i class="<?=$cat['icon']?>"></i></td>
      <td>
        <form action="/categoryadmin/delete/<?=$cat['id']?>" method="post" style="display:inline-block;">
          <?=csrf_field()?>
          <button class="btn btn-danger btn-xs" onclick="return confirm('Удалить?')">X</button>
        </form>
      </td>
    </tr>
  <?php endforeach;?>
</table>
