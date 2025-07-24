	
	<div class="action_box">
		<ul class="list-group">
		  <li class="list-group-item active" style="position: relative;">
		  	<div class="set_open_tag">Project</div>
		  	<div class="open_tag open"> ◉ </div>
		  </li>
		  <?foreach ($list as $item) {?>
		  		<li class="list-group-item hide">
		  			<?
		  				$arrName = explode('.', $item['name']);
		  			?>
		  			<a href="./?page=getlist&str=<?=$item['name'];?>">
		  				<? foreach ($arrName as $elm) {
		  					?> <?=$elm;?> <?
		  				} ?>
		  			</a>

		  			<div class="mini_btn float_left">
		  				<?$this->interact->SelectNotSection($item['name'],NULL)?>
		  			</div>

		  		</li>
		  <?}?>
		</ul>		
	</div>