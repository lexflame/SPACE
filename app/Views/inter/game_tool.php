</br>
<form>
	<div class="form-row">
		<div class="btn-group" role="group" aria-label="Basic example">
		<? foreach ($genre as $item) {
			?>
			<a 
				class="" 
				href="?page=game&action=genre&str=
				<?if(empty($item['genre'])):?>
					NAN
				<?else:?>
					<?=$item['genre'];?>
				<?endif;?>
				"
				style="margin-right: 15px;" 
				>
				<button type="button" class="btn btn-secondary">
				<?if(empty($item['genre'])):?>
					Без жанра
				<?else:?>
					<?=$item['genre'];?>
				<?endif;?>
				</button>
			</a>
			<?
		} ?>
		</div>
	</div>
</form>