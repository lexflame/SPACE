	
	<?$this->load->library('interact');?>
	</br>
	<div class="full_box">
		<ul class="list-group" style="width: 60%;margin: auto;">
		  <?foreach($list as $item){?>
		  	<?$dtaProject 	= $this->graber->GetDataProject($item['link']);?>
		  <li class="list-group-item d-flex justify-content-between align-items-center
		  <?if($_GET['id'] === $item['id']):?>
		  	set_class
		  <?endif;?>
		  ">
		  	<h7>
		  			<?//$this->interact->GetArc($item['link'],$str)?>
		  			<?//$this->interact->IcoAct($item['link'])?>
		  			
		  			<!--a href="#" class="badge badge-success"><?//=$item['name'];?></a-->
		  			
		  			<a href="./?page=edit&table=<?=$table;?>&id=<?=$item['id'];?>" class="badge badge-success">Edit</a>

		  			<?if(empty($item['path_file'])):?>
		  				<a href="./?page=game&action=getData&str=<?=$item['name'];?>" class="badge badge-warning">GetData</a>
		  			<?else:?>
		  				<a target="_blank" href="./game/<?=$item['path_file'];?>" class="badge badge-standart">ViewDsc</a>
		  			<?endif;?>


		  			<?if(empty($item['description'])):?>
		  				<a href="./?page=game&action=GetDescription&str=<?=$item['name'];?>&id=<?=$item['id'];?>" class="badge badge-warning">GetDescription</a>
		  			<?else:?>
		  				<a target="_blank" href="./game/<?=$item['path_file'];?>" class="badge badge-standart">Description</a>
		  			<?endif;?>


		  			<?if(empty($item['genre'])):?>
		  				<a href="./?page=game&action=GetGenre&str=<?=$item['name'];?>&id=<?=$item['id'];?>" class="badge badge-warning">GetGenre</a>
		  			<?endif;?>			  					  					  			

		  			<a href="./?page=game&str=<?=$item['name'];?>&action=mVote&id=<?=$item['id'];?>" class="badge badge-success">-</a>
		  			<a href="./?page=game&str=<?=$item['name'];?>" class="badge badge-success">Vote: <?=$item['vote'];?></a>
		  			<a href="./?page=game&str=<?=$item['name'];?>&action=pVote&id=<?=$item['id'];?>" class="badge badge-success">+</a>

		  			<span class="white_class"><?=urldecode($item['name']);?></span>
		  			
		  			<a target="_blank" href="./game/<?=$item['genre'];?>" class="badge badge-standart"><?=$item['genre'];?></a>

		  			<a target="_blank" href="https://www.youtube.com/results?search_query=<?=urlencode($item['name']);?>" class="badge badge-youtube">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg></a>
		  			
		  		</h7>
		  		</li>
		  		<?if(!empty($item['description'])):?>
		  		<li class="white_class list-group-item d-flex justify-content-between align-items-center">
		  			<?=$item['description'];?>
		  		</li>
		  		<?endif;?>
		  		<!--span class="badge badge-primary badge-pill">DOWNLOAD (14.0 kb)</span-->
		  
		  <?}?>
		</ul>		
	</div>