<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="favicon.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">   
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>    
    <script src="/js/jq_plugin.js"></script>
    <title>Interact</title>
  </head>
  <body style="padding: 15px;">
  	<style type="text/css">
      .name_text {
        
      }
      .del_btn {
        cursor: pointer;
        color: white;
      }
      .to_bottom_pos {
    position: fixed;
    bottom: 21px;
    right: 15px;
    width: 80.7%;
    background-color: #626262;
    padding: 12px;
    border-radius: 3px;        
      }
      pre {
        background-color: white;
        color: black;
      }
      .to_top {
        cursor: pointer;
        position: fixed;
        left: 15px;
        bottom: 24px;        
      }
      .to_top .cell {
        display: table-cell;
        width: 19%;
        height: 37px;
        text-align: center;
        vertical-align: middle;
        background-color: #cccccc7a;
      }
      .set_open_tag {
        position: absolute;
        cursor: pointer;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        padding-left: 20px;
        padding-top: 12px;        
      }
      .hide {
        display: none;
      }
      .open_tag {
         float: right;
        color: wheat;
        cursor: pointer;
      }
      .open_tag.open {
         float: right;
        color: white;
      }      
      .vert_balk2 {
        position: absolute;
        color: white;
        top: -39px;
        left: 43px;
        word-wrap: break-word;
      }
      .vert_balk1 {
        position: absolute;
        color: white;
        top: -20px;
        left: 43px;
        word-wrap: break-word;
      }
      .list-group-item h7 {
        position: relative;
      }
      .ml3 {
        margin-left: 3px;
      }
      .ml44 {
        margin-left: 43px;
      }
      .white_class {
        color: white;
      }
      .list-group-item.active {
        background-color: #35383c;
        border-color: white;
      }
      .badge-success {
        background-color: #494d52;
      }
      .badge-warning {
        background-color: #9a978d;
      }
      a, a:hover {
        color: #cac6c6;
      }
      .list-group-item {
        background-color: #2d2d2d;
      }
      body {
        background-color: black;
      }
      .list-group-item.project {
        background-color: #35383c;
        color: white;
        font-weight: 600;        
      }
      .list-group-item.category {
        background-color: #26282b;
        color: white;
        font-weight: 600;        
      }  		
  		.justify-content-between span.badge {

  		}
      .badge-youtube svg path {
        color: red;
        background: red;
      }
  		.mini_btn {
  			display: contents;
  			transform: scale(0.8);
  		}
  		.float_left {
		    float: right;
		    display: flex;  			
  		}
  		.act {
  			background-color: #21ff0040;
  		}
  		.list_box {
  			width: 82%;
    		float: right;  			
  		}
  		.action_box {
	   		float: left;
    		width: 100%;
    		clear: both;
  		}
  		.msize_svg button svg {
  			zoom: 83%;
  		}
  		.side_bar {
			   float: left;
    		width: 17%;
  		}
  		.set_class {
  			background-color: gainsboro;
  		}
      .debug {
        position: fixed;
        top: 21px;
        right: 11px;
        z-index: 99999;        
      }
  	</style>
    <a href="#" onclick="window.location.reload(true);" style="color: black;">
    	<h1 style="color: #f34e4e;">Interact</h1>
    </a>
  <div class="debug btn-group" role="group">
    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Debug_param
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
      <? foreach ($_REQUEST as $key => $value) {?>
        <a class="dropdown-item" href="./?debug_data=1<?=$this->argumentation->SARA();?>">debug_data</a>
        <?if(stristr($key, 'debug') === false){?>
        <a class="dropdown-item" href="./?debug_<?=$key;?>=1<?=$this->argumentation->SARA();?>">debug_<?=$key;?></a>
        <a class="dropdown-item" href="./?debug_<?=$value;?>=1<?=$this->argumentation->SARA();?>">debug_<?=$value;?></a>
        <?}?>
        <?$name_arg = 'debug_'.$key;if(isset($_GET[$name_arg])){?>
            <a class="dropdown-item" href="./?out=1<?=$this->argumentation->SARA();?>">Close Debug</a>
          <?}?>
        <?$name_arg = 'debug_'.$value;if(isset($_GET[$name_arg])){?>
            <a class="dropdown-item" href="./?out=1<?=$this->argumentation->SARA();?>">Close Debug</a>
          <?}?>
      <?}?>
    </div> 
  </div>
	<form>
	  <div class="form-row">
	<div class="btn-group" role="group" aria-label="Basic example">
	  <a class="rlink" href="?page=listpage"><button type="button" class="btn btn-secondary">Interaction</button></a>
	  <a class="rlink" href="?page=getlist"><button type="button" class="btn btn-secondary">GetList</button></a>
	  <a class="rlink" href="?page=getpage"><button type="button" class="btn btn-secondary" onclick="return location.href = '?getpage='+$('#AddrString').val();">GetPage</button></a>
	  
	  <a class="rlink" href="?page=scheduler"><button type="button" class="btn btn-secondary">Scheduler</button></a>
	  
	</div>	  	
	    <div class="col">
	      <input 
	      	type="text" 
	      	id='AddrString' 
	      	class="form-control" 
          name='enter_str'
	      	placeholder="Search or operation page" 
	      	value="<?=(!isset($_GET['str'])?'':$_GET['str']);?>"
	      >
	    </div>
	<div class="btn-group" role="group" aria-label="Basic example">
    <a class="rlink" href="?page=getlist&action=search">
      <button type="button" class="btn btn-secondary">Search</button>
    </a>
    <a class="rlink" href="?page=game">
      <button type="button" class="btn btn-secondary">Game</button>
    </a>
    <a class="rlink" href="?page=game&action=addGame">
      <button type="button" class="btn btn-secondary">+</button>
    </a>    
	  <a class="rlink" href="?page=project">
	  	<button type="button" class="btn btn-secondary">Wiki Project</button>
	  </a>
	  <a class="rlink" href="?page=project&action=addProject">
	  	<button type="button" class="btn btn-secondary">+</button>
	  </a>
	  <a class="rlink msize_svg" href="?page=settings">
	  	<button type="button" class="btn btn-secondary">
	  		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 16h-6v-3h6v3zm-2-5v-10h-2v10h2zm-2 7v5h2v-5h-2zm13-7h-6v-3h6v3zm-2-5v-5h-2v5h2zm-2 7v10h2v-10h-2zm13 3h-6v-3h6v3zm-2-5v-10h-2v10h2zm-2 7v5h2v-5h-2z"/></svg>
	  	</button>
	  </a>	  
	  
	</div>	    
	  </div>
	</form>