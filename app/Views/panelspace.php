	<link rel="stylesheet" href="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/bootstrap.min.css"/>
	<link rel="stylesheet" href="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/bootstrap.add.min.css"/>
	<link rel="stylesheet" href="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/dragula.min.css"/>
	<script src="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/jquery-3.5.1.min.js"></script>
	<script src="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/bootstrap.min.js"></script>
	<script src="http://<?=$_SERVER['SERVER_ADDR'];?>/assets/space.panel.js"></script>
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