<?php 
	$User = new User();	if($Session->active()){		redirect_to(public_url('home'));	}	else{		/*Do something*/	}	
	require_once layout_path('nav.php');
?>
	