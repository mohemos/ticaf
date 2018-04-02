<?php 
	require_once 'config.php';
	require_once layout_path('nav.php');
?>




<div class="container top-100 bottom-100">
	<h1 class="text-center top-100 bottom-100">Welcome <?php echo emailToUsername($Session->username); ?>!</h1>
</div>







<?php require_once layout_path("footer.php"); ?>


