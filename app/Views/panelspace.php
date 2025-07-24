	<link rel="stylesheet" href="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/css/bootstrap.add.min.css"/>
	<link rel="stylesheet" href="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/css/dragula.min.css"/>
	<script src="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/js/jquery-3.5.1.min.js"></script>
	<script src="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/js/bootstrap.min.js"></script>
	<script src="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/js/space.panel.js"></script>
	<section id="mainApp">
  		<!-- Ваш внутренний контент -->
	</section>
	<?php

	?>
	<script>
		$('#mainApp').topControlPanel({
		  onButtonClick: function(id, e, btn){
		    alert('Нажата кнопка: '+id);
		    // ... обработайте нужное действие
		    $(btn).siblings().removeClass('active');
		    $(btn).addClass('active');
		  }
		});
	</script>