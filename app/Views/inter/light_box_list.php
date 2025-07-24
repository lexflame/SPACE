	
	<?$this->load->library('interact');?>
	<div class="list_box">
		<ul class="list-group">

		<?if(count($load_list) > 0):?>
			<li class="list-group-item d-flex justify-content-between align-items-center project">
				Загружаемые файлы
			</li>
			<?foreach($load_list as $item){?>
				<li class="list-group-item d-flex justify-content-between align-items-center">
					<h7 class="white_class">
						<?=$item;?>
					</h7>
				</li>
			<?}?>
		<?endif;?>

			<?
			if(isset($_GET['debug_getlist']))
			{
				echo '<pre>';

					print_r($list);

				echo '</pre>';
			}
			?>
			<?$s=0;?>
			<?
			if(isset($_GET['per_page'])){
				if($_GET['per_page'] > 1){

				$step = round($count_list/$_GET['per_page'],0,PHP_ROUND_HALF_DOWN)*$limit_page;

				$steps = ($_GET['per_page']-1)*$limit_page;

				// echo '<pre>';var_dump($steps);echo '</pre>';

				$k = 0;

				$list_sheet = array();

				foreach ($list as $item) {

					// unset($list[$k]);

					$k++;
							
					// if($k > $step){										
						
					//  	break;	
					//  }

					if($k > $steps){

						$list_sheet[] = $item;

					}

				}

				$list = $list_sheet;

				}	
			}		
			?>
		  <?foreach($list as $item){?>
		  	<?$s++?>
		  	<?if($s > $limit_page)
		  		break;?>
		  	<?
		  		unset($ingrit);
		  		unset($isset_p);	
		  		if(!isset($current_project)){
		  			$current_project = $item['project'];
		  			$isset_p = true;
		  			$ingrit = 1;
		  		}else{
		  			if($current_project != $item['project']){
		  				$current_project = $item['project'];
		  				$isset_p = true;
		  				$ingrit = 1;
		  			}else{
		  				$isset_p = false;
		  				$ingrit = 2;
		  			}
		  		}

		  		if($isset_p === true):?>
		  			<li class="list-group-item d-flex justify-content-between align-items-center project">		
			  			<h7>
			  				<a href="/?page=getlist&str=<?=$item['project'];?>">◆ <?=$item['project'];?><?$s++;?></a>
			  			</h7>
		  			</li>	
		  		<?endif;
		  		if(!isset($current_section)){
		  			$current_section = $item['section'];
		  			$isset = true;
		  		}else{
		  			if($current_section != $item['section']){
		  				$current_section = $item['section'];
		  				$isset = true;
		  			}else{
		  				$isset = false;
		  			}
		  		}
		  		$set_section = 0;
		  		if($isset === true):?>
		  			<li class="list-group-item d-flex justify-content-between align-items-center category">		
			  			<h7>
			  				 <span class="white_class ml3"><?if($ingrit === 1):?>╚<?endif;?><?if($ingrit === 2):?>╚<?endif;?>══ <a href="/?page=getlist&str=<?=$item['project'];?>&section=<?=$item['section'];?>">◆</span> <?=$item['section'];?></a>
			  				 <?$set_section = 1;?>
			  			</h7>
			  			<span class="del_btn"><a href="?type=path&delete=/load/<?=$item['project'];?>/<?=$item['section'];?>&id=<?=$item['id'];?>&table=link_list">Удалить</a></span>
		  			</li>	
		  		<?endif;
		  		$dtaProject 	= $this->graber->GetDataProject($item['link']);
		  	?>
		  <li class="list-group-item d-flex justify-content-between align-items-center
		  <?if($_GET['link'] === $item['link']):?>
		  	set_class
		  <?endif;?>
		  " ids='<?=$item['id'];?>'>
		  		<h7>
		  			<?if($set_section === 1){?>
		  				<div class="vert_balk1">║</div>
		  				<div class="vert_balk2">║</div>
		  			<?}else{?>
		  				<div class="vert_balk1">◆</div>
		  			<?}?>
		  			
		  			<span class="white_class ml44">╚════════ </span>
		  			<?$this->interact->GetArc($item['link'],$str,$item['section'])?>
		  			<?$this->interact->IcoAct($item['link'])?>
		  			<?$this->interact->GetSection($item['link'],$str)?>
		  			<?$this->interact->GetScheduler($item['link'],$item['section'],$item['project'])?>
		  			<?$title=$this->argumentation->TitleLink($item['link']);$title = substr($title, 0, 28);?>
		  			<a href="#" class="badge badge-success">
		  				<?if(!empty($item['project'])):?><?=$item['project'];?><?else:?><?=$dtaProject[0];?><?endif;?>
		  			</a>
		  			<?$validFile = '';?>
		  			<?if(strlen($item['section']) > 0):?>
		  				<?$validFile 	= $this->notifer->Init('ValidFileExist',$item['link'])?>
		  				<a href="#" class="badge badge-success"><?=$item['section'];?></a>
		  			<?else:?>
		  				<a href="./?page=getlist&action=InteractSection&str=<?=$str;?>&link=<?=$item['link'];?>" class="badge badge-warning"> No set category </a>
		  			<?endif;?>
		  			<?
		  				$link_file = str_ireplace(' ','_',$item['link']);
		  				$link = './load/'.$item['project'].'/'.$item['section'].'/'.$link_file;
		  				$del_link = '/load/'.$item['project'].'/'.$item['section'].'/'.$link_file;
		  			?>
		  			<a target="_blank" href="<?=$link;?>"> <?=$title;?></a>
		  		</h7>
		  		<?if(strlen($validFile) > 0){?><?=$validFile;?><?}?>	
		  		<span class="del_btn"><a href="?delete=<?=$del_link;?>&id=<?=$item['id'];?>&table=link_list">Удалить</a></span>	  		
		  	</li>
		  <?}?>
		</ul>
		<?if(isset($list_search)):?>
		</br>
		<h5 class="white_class">Найденно в файлах</h5>		
		</br>
		  <?foreach($list_search as $item){?>
		  	<?
		  		if(!isset($current_project)){
		  			$current_project = $item['project'];
		  			$isset_p = true;
		  		}else{
		  			if($current_project != $item['project']){
		  				$current_project = $item['project'];
		  				$isset_p = true;
		  			}else{
		  				$isset_p = false;
		  			}
		  		}
		  		if($isset_p === true):?>
		  			<li class="list-group-item d-flex justify-content-between align-items-center project">		
			  			<h7>
			  				<?=$item['project'];?>
			  			</h7>
		  			</li>	
		  		<?endif;
		  		if(!isset($current_section)){
		  			$current_section = $item['section'];
		  			$isset = true;
		  		}else{
		  			if($current_section != $item['section']){
		  				$current_section = $item['section'];
		  				$isset = true;
		  			}else{
		  				$isset = false;
		  			}
		  		}
		  		if($isset === true):?>
		  			<li class="list-group-item d-flex justify-content-between align-items-center category">		
			  			<h7>
			  				<?=$item['section'];?>
			  			</h7>
		  			</li>	
		  		<?endif;
		  		$dtaProject 	= $this->graber->GetDataProject($item['link']);
		  	?>
		  <li class="list-group-item d-flex justify-content-between align-items-center
		  <?if($_GET['link'] === $item['link']):?>
		  	set_class
		  <?endif;?>
		  " ids='<?=$item['id'];?>'>
		  		<h7>
		  			<?$this->interact->GetArc($item['link'],$str,$item['section'])?>
		  			<?$this->interact->IcoAct($item['link'])?>
		  			<?$this->interact->GetSection($item['link'],$str)?>
		  			<?$this->interact->GetScheduler($item['link'],$item['section'],$item['project'])?>
		  			<?$title=$this->argumentation->TitleLink($item['link']);$title = substr($title, 0, 28);?>
		  			<a href="#" class="badge badge-success">
		  				<?if(!empty($item['project'])):?><?=$item['project'];?><?else:?><?=$dtaProject[0];?><?endif;?>
		  			</a>
		  			<?$validFile = '';?>
		  			<?if(strlen($item['section']) > 0):?>
		  				<?$validFile 	= $this->notifer->Init('ValidFileExist',$item['link'])?>
		  				<a href="#" class="badge badge-success"><?=$item['section'];?></a>
		  			<?else:?>
		  				<a href="./?page=getlist&action=InteractSection&str=<?=$str;?>&link=<?=$item['link'];?>" class="badge badge-warning"> No set category </a>
		  			<?endif;?>
					<?if($item['project'] != 'Загружается'):?>
					<?
		  				$link_file = str_ireplace(' ','_',$item['link']);
		  				$link = './load/'.$item['project'].'/'.$item['section'].'/'.$link_file;
		  			?>
		  			<?endif;?>
		  			<?if($item['project'] != 'Загружается'):?><a target="_blank" href="<?=$link;?>"><?else:?><span class="white_class"><?endif;?>
		  					<?=$title;?>
		  			<?if($item['project'] != 'Загружается'):?></a><?else:?></span><?endif;?>

		  		</h7>
		  		<?if(strlen($validFile) > 0){?><?=$validFile;?><?}?>		  		
		  	</li>
		  <?}?>
		<?endif;?>
	</br>
	<div class="btn-toolbar to_bottom_pos justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
		<? 
		
		$arr['total_rows'] = $count_list;
		$arr['limit_page'] = $limit_page;

		$this->interact->Pagination($arr); 

		?>
	</div>	
</br>	
	</div>
