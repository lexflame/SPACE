

</br>
<div class="jumbotron" style="width: 50%;margin: auto;">
  <h2 class="display-5">Редактировать</h2>
  <p>
  	<form action="./?page=edit&action=save&table=<?=$table;?>&id=<?=$id;?>" method="POST">
	<?
		foreach ($editData as $key => $value) {
			
			?>
			
			<div class="input-group mb-3">
			  <div class="input-group-prepend">
			    <span class="input-group-text"><?=$key;?></span>
			  </div>
			  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" name='<?=$key;?>' value="<?=$value;?>">
			  <div class="input-group-append">
			    
			  </div>
			</div>
			
			<?
		}
	?>
	<button class="button input-group-text set_settings">Сохранить</button>
	</form>
  </p>
</div>