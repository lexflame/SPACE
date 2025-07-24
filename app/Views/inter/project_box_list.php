	
	<?$this->load->library('interact');?>
	</br>
	<div class="full_box">
		<ul class="list-group" style="width: 60%;margin: auto;">
		  <?foreach($list as $item){?>
		  	<?$dtaProject 	= $this->graber->GetDataProject($item['link']);?>
		  <li class="list-group-item d-flex justify-content-between align-items-center">
		  	<h7>
		  			<?//$this->interact->GetArc($item['link'],$str)?>
		  			<?//$this->interact->IcoAct($item['link'])?>
					<a href="./?page=project&action=delete&id=<?=$item['id'];?>&nprj=<?=$item['name'];?>" class="badge badge-success" style="float: left;margin-right: 15px;">
						delete
					</a>		  			
		  			<a href="#" class="badge badge-success white_class"><?=$item['name'];?></a>
		  			<span class="white_class"><?=$item['name'];?></span>
		  			<a href="./?page=getlist&str=<?=$item['name'];?>" class="badge badge-success">GetList</a>
		  			
		  			
		  		</h7><span class="badge badge-primary badge-pill">DOWNLOAD (14.0 kb)</span>
		  </li>
		  <?}?>
		</ul>		
	</div>