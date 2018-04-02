<?php 
	require_once 'config.php';
	$User = new User();
	
	
	
	
	
	if($Session->active() and $Session->role==='user'){
		/*Active user codes*/
		
		
	}
	else{
		/*Login admin*/
			if(isset($_POST['loginEmail'])){
				$auth = $User->auth($_POST['loginEmail'],$_POST['password']);
				if(is_bool($auth) and $auth===true){
					/*Ajax response code sent to javascript*/
					echo 172;
					exit();
				}
				else{
					exit($auth);
				}
			}
			
			
			/*Registering User*/
			if(isset($_POST['registerEmail'])){
				echo $User->register($_POST['registerEmail'],$_POST['fullname'],'ordinary',$_POST['phone']);
				exit();
			}
	}
	
	
 ?>