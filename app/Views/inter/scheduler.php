

</br>
<?$this->load->library('interact');?>
<div class="jumbotron" style="width: 70%;margin: auto;background-color: #6c757d;">
  <h2 class="display-5">В очереди</h2>
  <?$this->interact->ErrorScheduler();?>
  <a href="./?page=scheduler&action=deleteDuplicateDB">Remove Duplicate Registry DB</a>
  | <a href="./?page=scheduler&action=fround">First round</a> <a href="./?page=scheduler&action=startFirstRound" class="badge badge-warning" style='transform: scale(0.7) translateY(-2px);'><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M13 2.004c5.046.504 9 4.783 9 9.97 0 1.467-.324 2.856-.892 4.113l1.738 1.005c.732-1.553 1.154-3.284 1.154-5.117 0-6.304-4.842-11.464-11-11.975v2.004zm-10.109 14.083c-.568-1.257-.891-2.646-.891-4.112 0-5.188 3.954-9.466 9-9.97v-2.005c-6.158.511-11 5.671-11 11.975 0 1.833.421 3.563 1.153 5.118l1.738-1.006zm17.213 1.734c-1.817 2.523-4.769 4.174-8.104 4.174s-6.288-1.651-8.105-4.175l-1.746 1.01c2.167 3.123 5.768 5.17 9.851 5.17 4.082 0 7.683-2.047 9.851-5.168l-1.747-1.011zm-8.104-13.863c-4.419 0-8 3.589-8 8.017s3.581 8.017 8 8.017 8-3.589 8-8.017-3.581-8.017-8-8.017zm-2 11.023v-6.013l6 3.152-6 2.861z"/></svg></a>
  | <a href="./?page=scheduler&action=sround">Second round</a> <a href="./?page=scheduler&action=startSecondRound" class="badge badge-warning" style='transform: scale(0.7) translateY(-2px);'><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M13 2.004c5.046.504 9 4.783 9 9.97 0 1.467-.324 2.856-.892 4.113l1.738 1.005c.732-1.553 1.154-3.284 1.154-5.117 0-6.304-4.842-11.464-11-11.975v2.004zm-10.109 14.083c-.568-1.257-.891-2.646-.891-4.112 0-5.188 3.954-9.466 9-9.97v-2.005c-6.158.511-11 5.671-11 11.975 0 1.833.421 3.563 1.153 5.118l1.738-1.006zm17.213 1.734c-1.817 2.523-4.769 4.174-8.104 4.174s-6.288-1.651-8.105-4.175l-1.746 1.01c2.167 3.123 5.768 5.17 9.851 5.17 4.082 0 7.683-2.047 9.851-5.168l-1.747-1.011zm-8.104-13.863c-4.419 0-8 3.589-8 8.017s3.581 8.017 8 8.017 8-3.589 8-8.017-3.581-8.017-8-8.017zm-2 11.023v-6.013l6 3.152-6 2.861z"/></svg></a>
  | <a href="./?page=scheduler&action=around">All round</a> <a href="./?page=scheduler&action=startAllRound" class="badge badge-warning" style='transform: scale(0.7) translateY(-2px);'><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M13 2.004c5.046.504 9 4.783 9 9.97 0 1.467-.324 2.856-.892 4.113l1.738 1.005c.732-1.553 1.154-3.284 1.154-5.117 0-6.304-4.842-11.464-11-11.975v2.004zm-10.109 14.083c-.568-1.257-.891-2.646-.891-4.112 0-5.188 3.954-9.466 9-9.97v-2.005c-6.158.511-11 5.671-11 11.975 0 1.833.421 3.563 1.153 5.118l1.738-1.006zm17.213 1.734c-1.817 2.523-4.769 4.174-8.104 4.174s-6.288-1.651-8.105-4.175l-1.746 1.01c2.167 3.123 5.768 5.17 9.851 5.17 4.082 0 7.683-2.047 9.851-5.168l-1.747-1.011zm-8.104-13.863c-4.419 0-8 3.589-8 8.017s3.581 8.017 8 8.017 8-3.589 8-8.017-3.581-8.017-8-8.017zm-2 11.023v-6.013l6 3.152-6 2.861z"/></svg></a>
  <style type="text/css">.jumbotron{width: 95% !important;}</style>

  <p>
  <ul class="list-group" style="width: 60%;margin: auto;">
  	<? $this->load->model('data_model'); ?>
  	<? $action = $this->data_model->ArgGetValue('action'); ?>
  	<? // var_dump($action); ?>
  	<? if($action != 'ErrorListFile'){ ?>
		<?php foreach ($scheduler as $item) {?>
		  	<li class="list-group-item d-flex justify-content-between align-items-center">
		  		<h7 class='white_class' >
					<?=substr($item['url'], 0, 88);?>
					<a href="./?page=scheduler&action=delete&id=<?=$item['id'];?>" class="badge badge-success" style="float: left;margin-right: 15px;">
						delete
					</a>
					<a href="./?page=scheduler&action=replace&id=<?=$item['id'];?>&field=status&set=1" class="badge badge-success" style="float: left;margin-right: 15px;">
						repack
					</a>					

		  		</h7>
					<a href="#" class="badge badge-success white_class" style="float: right;">
						<?=$item['status'];?>
					</a>		  		

		  	</li>			
		<?}}else{?>
			<? 
				$ErrorFile = $this->data_model->ErrorFile(); 
				$doaction = $this->data_model->ArgGetValue('doaction');
			?>
			<? // echo '<pre>'; print_r($ErrorFile); echo '</pre>'; exit; ?>
			<div class="jumbotron" style="width: 70%;margin: auto;background-color: #6c757d;">
				<a href="./?page=scheduler&action=ErrorListFile&doaction=allrepack">Set all repack</a>
			</div>
			<? foreach ($ErrorFile as $item) { ?>
			  	<li class="list-group-item d-flex justify-content-between align-items-center">
			  		<h7>
			  			<?
			  				$arrFileName = explode('/', $item);
			  				$name = array_pop($arrFileName);
			  				$name = str_ireplace('.html','',$name);
			  				// $name = str_ireplace('_',' ',$name);
			  				
			  			?>
			  			<?	

			  				if($doaction === 'allrepack'){
			  					$arrData = $this->data_model->SelectLikeCondition('scheduler','url',$name);
			  					$this->data_model->UpdateTable('scheduler','id',$arrData[0]['id'],'status','1');	
			  				}


			  				$this->db->where('status', 2);
			  				$arrData = $this->data_model->SelectLikeCondition('scheduler','url',$name);
			  				// echo '<pre>'; print_r(); echo '</pre>';

			  			?>
						 <span class="white_class"><?=$item;?></span> 
						<?if(isset($arrData[0]['id'])):?>
						<a href="./?page=scheduler&action=replace&id=<?=$arrData[0]['id'];?>&field=status&set=1&doaction=error" class="badge badge-success" style="float: left;margin-right: 15px;">
							repack
						</a>						 
						<?endif;?>
			  		</h7>
			  	</li>
			<? } ?>
	<?}?>
  </ul>
  </p>
</div>