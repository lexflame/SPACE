	
	<div class="action_box">
		<ul class="list-group">
		  <li class="list-group-item active">Group Operation</li>
		  <?if(!empty($current_project_page)):?>
		  <a  class="rlink" href="./?page=getlist&action=grablinkMass"><li class="list-group-item"> Mass Graber Current Project </li></a>
		  <?endif;?>
		  <a  class="rlink" href="./?page=getlist&action=deleteDuplicate"><li class="list-group-item"> Remove Duplicate Preload File </li></a>
		  <a  class="rlink" href="./?page=getlist&action=showLoadFile"><li class="list-group-item"> Show Preload File </li></a>
		  <a  class="rlink" href="./?page=getlist&action=deleteDuplicateDB&str=<?=$_GET['str'];?>"><li class="list-group-item"> Remove Duplicate Registry DB</li></a>
		</ul>		
	</div>