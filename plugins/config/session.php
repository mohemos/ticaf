<?php

class Session{
	 private $loggedIn=false;
	 public $user_id,$username,$message,$role,$privilege,$dp,$country,$countryFlag;
	 function __construct(){
		 ob_start();
		 session_start();
		 isset($_COOKIE["token"])?"":setcookie("token","".time().rand(10000,100000)."",strtotime("+ 1 days"));
		 
		 $this->checkMessage();
		 /*Setting country*/
		 if(isset($_SESSION["country"])){
			 $this->country=$_SESSION['country'];
		 }
		 else if(isset($_GET["country"])){
			 $this->country=$_GET['country'];
			 $_SESSION["country"]=$_GET["country"];
		 }
		 
		 /*Setting country flag*/
		if(isset($_SESSION["country_flag"])){
			 $this->countryFlag=$_SESSION["country_flag"];
		 }
		 else if(isset($_GET["country_flag"])){
			 $this->countryFlag=$_GET["country_flag"];
			 $_SESSION["country_flag"]=$_GET["country_flag"];
		 }
		else{
			 $this->countryFlag="africa.png";
		}
		
	 }
	 
	 public function loggedIn(){
		 return $this->loggedIn;
	 }
	 
	 public function myId(){
		 return (int)$_SESSION['user_id'];
	 }
	 
	 public function login($user_id,$username,$role,$dp="",$privilege=0,$url=''){
		$_SESSION["active_time"]=time() + (3600*5);
		 $this->user_id=$_SESSION["user_id"]=(int)$user_id;
		 $this->username=$_SESSION["username"]=$username;
		 $this->dp=$_SESSION["dp"]=$dp;
		 $this->role=$_SESSION["role"]=$role;
		 $this->privilege=$_SESSION["privilege"]=$privilege;
		 $this->loggedIn=true;
		 
		 if(!empty($url)){
			 redirect_to($url);
		 }
	 }
	 
	 public function active(){
	 if(isset($_SESSION["username"]) and isset($_SESSION["user_id"]) and (time() < $_SESSION["active_time"])){
		 $this->username = $_SESSION["username"];
		 $this->user_id = (int)$_SESSION["user_id"];
		 $this->role = $_SESSION["role"];
		 $this->privilege = $_SESSION['privilege'];
		 $this->dp = $_SESSION['dp'];
		 return true;
	 }
	 else{
		 return false;
	 }
	 }
	 
	 public function message($msg=""){
		 if(!empty($msg)){
			 $_SESSION["message"] = $msg;
		 }
		 else{
			 return $this->message;
		 }
	 }
	 
	 public function checkMessage(){
		 if(isset($_SESSION["message"])){
			 $this->message = $_SESSION["message"];
			 unset($_SESSION["message"]);
		 }
		 else{
			 $this->message ="";
		 }
	 }

}
	
	$Session = new Session();
	
	
	
 ?>