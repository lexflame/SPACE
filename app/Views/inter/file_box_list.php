	
	<?$this->load->library('interact');?>
	<div class="list_box">
		<ul class="list-group">
		  <?foreach($HtmlFile as $item){?>
		  	<?foreach($item as $File){?>
		  		<li class="list-group-item d-flex justify-content-between align-items-center" ids='<?=$item['id'];?>'>
				<h7>
					<a href="./?page=getlist&action=getsetdir&str=<?=$File['Path'];?>" class="badge badge-success">Relocate CatDir</a>
		  			<a target="_blank" href="<?=$File['URL'];?>" > <?=$File['Name'];?></a>
		  			
		  		</h7>
		  		</li>
		  	<?}?>
		  <?}?>
		</ul>		
	</div>