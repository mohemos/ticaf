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
					/*Creating new user account*/	public function register($email,$name,$role,$phone=''){			$email = filter_var($this->filter($email),FILTER_VALIDATE_EMAIL);		$name = $this->filter($name);		if(empty($name) or empty($email) or empty($phone)){			$display = $this->alertDanger('Please fill all fields correctly!');			echo $display;		}		else {		if($this->dataExists('email',$email)){			$display = $this->alertDanger('Email already taken!');			echo $display;		}		else if($this->dataExists('phone',$phone)){			$display = $this->alertDanger('Phone number already taken!');			echo $display;		}				else{			$create = array('email'=>$email,'name'=>$name,'phone'=>$phone,'role'=>$role,'created_at'=>time());			$this_id = $this->create($create);			if($this_id){					$password = rand(10000,100000);					$subject = app_name." login password";					$message = 'Your new password is: '.$password." \r \n Please make sure you change your password immediately you login!";					if(mail($email,$subject,$message)){						if($this->update($this_id,['password'=>md5($password)])){														// exit($this->alertSuccess('Please check your inbox or spam for your new password! '.$password));							$display = $this->alertSuccess('Please check your inbox or spam for your new password! '.$password);							// echo json_encode(['display'=>$display,'field'=>'0']);							echo $display;													}						else{							$this->delete($this_id);							$display = $this->alertDanger('102:Connection error, Please try again!');							echo $display;						}											}					else{						$this->delete($this_id);						$display = $this->alertDanger('101:Unable to send email, please try again!');						echo $display;					}			}			else{				$display = $this->alertDanger('100: Connection error!');				echo $display;			}		}		}					}				/*user login authentication*/	public function auth($email,$password){		$email = filter_var($this->filter($email),FILTER_VALIDATE_EMAIL);		$password = md5($password);				/*Checking form fields*/		if(empty($email) or empty($password)){			return $this->alertDanger('Please fill all fields correctly!');		}		else{		$check = $this->where('id,role','email="'.$email.'" AND password="'.$password.'" LIMIT 1');		if(empty($check) or count($check)==0 or !is_array($check)){			return $this->alertDanger('Login details not valid');		}		else{			global $Session;			$user = array_shift($check);						/*Creating session data*/			$Session->login($user->id,$email,$user->role);			return true;		}		}			}		
}
 ?>
		