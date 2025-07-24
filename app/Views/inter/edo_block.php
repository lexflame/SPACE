<? $name_block 	= 	key($block); ?>
<? $elm_list 	=	key($block[$name_block]); ?>

<div class="edo_block <?=$name_block;?>">
	
	<ul id="<?=$name_block;?>">
		<?foreach($block[$name_block][$elm_list] as $li):?>

			<<?=$elm_list;?> id='<?=$li['id'];?>' class='li_list'><?=$li['link'];?></<?=$elm_list;?>>

		<?endforeach;?>

	</ul>

</div>