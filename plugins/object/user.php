<?php 
	class User extends MysqlConnection{
	protected $DbTable="user";
	protected $DbTableColumns = array('id','name','phone','email','password','role','created_at');
	public 	$id,$name,$phone,$email,$password,$role,$created_at;
	
	protected function attributes(){
		$attributes=array();
		foreach ($this->DbTableColumns as $field){
		if(property_exists($this,$field)){ 
		$attributes[$field] = $this->$field; 
		}
		}
		return $attributes;	
	}
	
}
 ?>
		