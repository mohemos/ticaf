<?php
	require_once 'config.php';
	if($Session->active()){
		session_destroy();
	if(isset($_SERVER["HTTP_REFERER"])){
		header("location:".$_SERVER["HTTP_REFERER"]);		
	}
	else{
		header("location:".public_url());
	}
	}
	else{
		session_destroy();
		header("location:".public_url());
	}

 ?>