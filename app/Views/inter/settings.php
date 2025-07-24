

</br>
<div class="jumbotron" style="width: 50%;margin: auto;">
  <h2 class="display-5">Настройки</h2>
  <p>
	<?
		foreach ($settings as $item) {
			
			?>
			<form action="./?page=settings&action=set_settings" method="POST">
			<div class="input-group mb-3">
			  <div class="input-group-prepend">
			    <span class="input-group-text"><?=$item['NAME'];?> - <?=$item['TITLE'];?></span>
			  </div>
			  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" name='<?=$item['NAME'];?>' value="<?=$item['VALUE'];?>">
			  <div class="input-group-append">
			    <button class="button input-group-text set_settings">Сохранить</button>
			  </div>
			</div>
			</form>
			<?
		}
	?>
  </p>
</div>