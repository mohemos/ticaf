<?php 
/*
		TiCAF PHP FRAMEWORK 1.0
*/
class MysqlConnection{
	
/*Database properties*/
		/*Database connection variable*/
		public $connection="";
		
		/*Current object(table)*/
		protected $DbTable="";
		
/*All the table attributes (Usually in an array and automatically collected when an object is created using createObject function)*/
		protected $DbTableFields="";
		
		
		
/*All sql query goes through this function*/
	public function sqlQuery($sql){
		
			/*Connect to database*/
			global $connection;
			
			/*Send query to database, the die function should be removed when hosted into production server*/
			$check = $connection->query($sql) or die($this->alertDanger($connection->error)."<hr>".$sql);
			
			/*returned database query*/
			return $check;
		}
	
	
	
/*Filtering unwanted characters from input data: this function removes all sql injections and potential harmful character*/
	public function filter($value){
		global $connection;
			$value = trim($value);
			$value = stripslashes($value);
			$value = htmlspecialchars($value,ENT_QUOTES);
			$value = $connection->real_escape_string($value);
			return $value;
		}
		
			
/*Checking if table column exist in database fo creating an object property*/
	public function hasAttribute($attribute){
		$objectAttributes = $this->attributes();
		if(array_key_exists($attribute,$objectAttributes)){
			return true;
		}
		else{
			return false;
		}
	}
	
	
		
/*Converting table columns(array index) to object */
	public function makeObject($result){
		$object = new $this;
		foreach($result as $attribute => $value){
			
			/*Checking if table column is specified in attribute array*/
			if($object->hasAttribute($attribute)){
				
				/*Assign value to object property*/
				$object->$attribute = $value;
			}
			
		}
		return $object;
			
	}
	
	
/*Convert database output to object*/
	public function getResultAsObject($sql){
		$check = $this->sqlQuery($sql);
		if($check->num_rows > 0){
			/*Array to store all results*/
			$resultArray=array();
			while($result=$check->fetch_array(MYSQLI_ASSOC)){
				/*Storing objects properties into array*/
				$resultArray[] = $this->makeObject($result);
			}
			return $resultArray;
		}
		else{
			return false;
		}
		
	}
	
	

/*Database result as array: the index parameter detaermines the type of array to return, change the to num to return a number index array, on default it will return an associative array*/
	public function getResultAsArray($sql,$index=null){
		$check = $this->sqlQuery($sql);
		if($check->num_rows > 0){
			/*Array to store all results*/
			$resultArray=array();
			
			
			/*return associative array*/
			if(is_null($index)){
				while($result=$check->fetch_array(MYSQLI_ASSOC)){
					/*Storing results into associative array*/
					$resultArray[] = $result;
				}
			}
			
			
			/*Return number index array*/
			else{
				while($result=$check->fetch_array(MYSQLI_NUM)){
					/*Storing results into associative array*/
					$resultArray[] = $result;
				}
			}
			
			return $resultArray;
		}
		else{
			return false;
		}
		
	}
	
	

	
	
/*>> Insert into database table:
	Create new instance of an object and store to database:
	This function helps you to insert data into active database table, 
	all insertions goes through this function*/
	public function create($objectData = array()){
		global $connection;
		$filteredField = array();
		
		/*Collecting table columns from object data array*/
		$tableColumns = join(',',array_keys($objectData));
		foreach($objectData as $data){
			/*removing harmful character from supplied data*/
			$filteredField[] = $this->filter($data);
		}
		
		/*Collecting filtered data as value to sql query*/
		$values = join("','",array_values($filteredField));
		$sql = "INSERT INTO ".$this->DbTable." (".$tableColumns.") VALUES('".$values."')";
		if($this->sqlQuery($sql)){
			/*Collect last inserted row id*/
			return $connection->insert_id;
		}
		else{
			/*Retutn false if query failed*/
			return false;
		}
	}
	
	
	
/*Update an instance of object: All updates goes through this function*/
	public function update($id=0,$update=array()){
		$id = (int)$id;
		/*Checking if correct Id and array is supplied*/
		if($id !=0 and is_array($update)){
			$updateQuery = array();
			foreach($update as $index=>$value){
				/*Filtering supplied data with mysqli real escape*/
				$updateQuery[] =$index."='".$this->filter($value)."'";
			}
			
			/*Construction update query*/
			$sql = "UPDATE ".$this->DbTable." SET ".join(',',$updateQuery)."  WHERE id=".$id."";
				/*Execute query*/
			$check = $this->sqlQuery($sql);
			
				/*Checking if update was affected*/
			global $connection;
			 if($connection->affected_rows > 0){
				 return true;
			 }
			 else{
				 return false;
			 }
		}
		else{
			/*Correct parameters not supplied*/
			return "Invalid arguments supplied";
		}
		 
	}
	
	
 /*==============Any method that has $table as a parameter means, you can switch between object by specifying the table, this is not compulsory=================*/
	
/*Deleting an instance of an object: */
	public function delete($id=0,$table=''){
		global $connection;
		$id = (int)$id;
		if($id>0){
			
			if(empty($table)){
				/*Delete active object*/
				if($this->sqlQuery("DELETE FROM ".$this->DbTable." WHERE id=".$id."")){
					
					/*Checking if data was deleted*/
				if($connection->affected_rows > 0) {
					return true;
				}
					
				else {
					return false;
				}
					
				}
				else{
					return false;
				}
			}
			else{
				/*Delete specified object*/
				if($this->sqlQuery("DELETE FROM ".$table." WHERE id=".$id."")){
				
					/*Checking if data was deleted*/
				if($connection->affected_rows > 0) {
					return true;
				}
					
				else {
					return false;
				}
					
				}
				else{
					return false;
				}
			}
			
		}
		else{
			return false;
		}
		
	}
	
	
	
	/*Custom delete function: This function delete based on supplied condition*/
	public function deleteCond($cond,$table=""){
		global $connection;
		
		if(empty($table)){
			/*deleting from active object*/
			if($this->sqlQuery("DELETE FROM ".$this->DbTable." WHERE ".$cond."")){
				if($connection->affected_rows > 0){
					return true;
				}
					
				else {return false;}
				
			}
			else{
				return false;
			}
		}
		else{
			/*deleting from specified object(table)*/
			if($this->sqlQuery("DELETE FROM ".$table." WHERE ".$cond."")){
				if($connection->affected_rows > 0){
					return true;
				}
					
				else {return false;}
				
			}
			else{
				return false;
			}
		}
		
	}
	
	
	
	



	/*Count all instances (rows) of an object(table): if no argument is supplied, it will retun all the row count */
	public function count($columns='',$value='',$table=''){
		
		if($columns==='' and $value===''){
			if($table===''){
				$check = $this->sqlQuery('SELECT COUNT(id) FROM '.$this->DbTable.'');
			}
			else{
				$check = $this->sqlQuery('SELECT COUNT(id) FROM '.$table.'');
			}
			
		}
		else if(is_array($columns)){
			$sql = 'SELECT COUNT(id) FROM '.$this->DbTable.' WHERE ';
			foreach($columns as $value => $key){
				$sql.=" $value='$key' AND ";
			}
			$sql .= "ID > 0";
			$check = $this->sqlQuery($sql);
		}
		else{
			if(empty($table)){ 
				/*Count active object(table)*/
				$check = $this->sqlQuery("SELECT COUNT(id) FROM ".$this->DbTable." WHERE $columns='$value' ");
			}
			
		else{
				/*Count specified object(table)*/
				$check = $this->sqlQuery("SELECT COUNT(id) FROM ".$table." WHERE $columns='$value' ");
			}
		}
		
		
		$result = $check->fetch_array(MYSQLI_NUM);
		return !empty($result) ? array_shift($result) : false;
	}
	
	
	
	
	
	
	
	/*Count all instances (rows) of an object(table): a useless function, i wonder why is here :) */
	public function countResult($table=""){
		
		if(empty($table)){ 
			/*Count active object(table)*/
			$check = $this->sqlQuery("SELECT COUNT(id) FROM ".$this->DbTable);
			}
			
		else{
			/*Count specified object(table)*/
			$check = $this->sqlQuery("SELECT COUNT(id) FROM ".$table);
			}
		
		$result = $check->fetch_array(MYSQLI_NUM);
		return !empty($result) ? array_shift($result) : false;
	}
	
	
	
	
	/*Count rows based on a supplied condition:
		example->countResult_cond(' gender="male" and age="50"  ');
	*/
	public function countResult_cond($cond=""){
		
		if(empty($cond)){ 
			/*Count active object(table)*/
			$check = $this->sqlQuery("SELECT COUNT(id) FROM ".$this->DbTable);
			}
			
		else{
			/*Count specified object(table)*/
			$check = $this->sqlQuery("SELECT COUNT(id) FROM ".$this->DbTable." WHERE ".$cond);
			}
		
		$result = $check->fetch_array(MYSQLI_NUM);
		return !empty($result) ? array_shift($result) : false;
	}
	
	
	
	
	/*Enter a custom COUNT sql query, this will only return the counted result:
		example->countSqlResult('SELECT COUNT(id) FROM student where id > 70');
	*/
	public function countSqlResult($sql){		
		$check = $this->sqlQuery($sql);
		$result = $check->fetch_array(MYSQLI_NUM);
		return !empty($result) ? array_shift($result) : false;
		
	}
	

	
	/*Generate random result*/
	public function sqlRand($columns=null,$number=1){
		$count = $this->count();
		
		if($number===1){
			$id = rand(1,$count);
			if(is_null($columns)){
				$sql = 'SELECT * FROM '.$this->DbTable.' WHERE id='.$id.' LIMIT 1';
				$result = array_shift($this->getResultAsObject($sql));
				
			} 
			else{
				$sql = 'SELECT '.$columns.' FROM '.$this->DbTable.' WHERE id='.$id.' LIMIT 1 ';
				$result = array_shift($this->getResultAsObject($sql));
			}
		}
		else{
			$sql = 'SELECT * FROM '.$this->DbTable.' WHERE id='.$id.' LIMIT 1';
				$result = $this->getResultAsObject($sql);
		}
		
		return $result;
	}
	
	
	
	/*Return all fields of an instance with specified ID*/
	public function findById($id=0,$table=""){
		$id = (int)$id;
		
		if($id>0){
			if(empty($table)){
				/*Find from current object*/
				$result = $this->getResultAsObject("SELECT * FROM ".$this->DbTable." WHERE id=".$id." LIMIT 1");
			}
			else{
				/*Find from specified object*/
				$result = $this->getResultAsObject("SELECT * FROM ".$table." WHERE id=".$id." LIMIT 1");
			}
			
			/*Return single result*/
			return array_shift($result);
			
		}
		else{
			return false;
		}
		
	}
	
	
	
	/*Return Specified columns of an instance having specified ID*/
	public function findById_index($columns='',$id=0,$table=""){
		$id = (int)$id;
		if($id > 0 and !empty($columns)){
			if(empty($table)){
				/*find in active object*/
				$result = $this->getResultAsObject("SELECT ".$columns." FROM ".$this->DbTable." WHERE id=".$id." LIMIT 1");
			}
			else{
				/*find in specified object*/
				$result = $this->getResultAsObject("SELECT ".$columns." FROM ".$table." WHERE id=".$id." LIMIT 1");
			}
		
			if(is_array($result)) {
					return array_shift($result);
				}
					
				else {return false;}
		
		}
		else { return false; }
				
	}
	
	
	
	/*Return all fields of an instance with specified ID*/
	public function findByColumn($column="id",$value=0,$shift=true,$table=""){
		if(!empty($column) and !empty($value)){
			
			$limit = ($shift==true) ? 'LIMIT 1' : '';
			
			$value = $this->filter($value);
			if(empty($table)){
				$result = $this->getResultAsObject("SELECT * FROM ".$this->DbTable." WHERE ".$column."='".$value."' ".$limit."");
			}
			else{
				$result = $this->getResultAsObject("SELECT * FROM ".$table." WHERE ".$column."='".$value."' ".$limit."");
			}
			
			if($shift==true){
				return empty($result)? false : array_shift($result);
			}
			else{
				return empty($result)? false : $result;
			}
			
		}
		else{
			return false;
		}
		
	}
	
	
	
	/*Return Specified fields of an instance having specified ID*/
	public function findByColumn_index($indexes,$column="id",$value=0,$shift=true,$table=""){
		if(empty($indexes) or empty($column) or empty($value)){
			return false;
		}
		else{
			
			$limit = ($shift==true) ? 'LIMIT 1' : '';
			if(empty($table)){
				$result = $this->getResultAsObject("SELECT ".$indexes." FROM ".$this->DbTable." WHERE ".$column."='".$value."' ".$limit."");	
			}
			else{
				$result = $this->getResultAsObject("SELECT ".$indexes." FROM ".$table." WHERE ".$column."='".$value."' ".$limit."");	
			}
			
			if(is_array($result)) { 
				if($shift==true){
					return array_shift($result); 
				}
				else{
					return $result;
				}
			
			}
		
			else {return false;}
		
		}
		
		
	}
	
		
	/*Return all instances of an object*/
	public function all($table="",$order=""){
		if(empty($table)){
			/*Active object*/
			return $this->getResultAsObject("SELECT * FROM ".$this->DbTable." ".$order);
		}
		else{
			/*specified object*/
			return $this->getResultAsObject("SELECT * FROM ".$table." ".$order);
		}
		
	}
	
	
	
	/*Return selected indexes*/
	public function selectColumn($column,$table="",$order=""){
		if(empty($table)){
			return $this->getResultAsObject("SELECT ".$column." FROM ".$this->DbTable." ".$order);
		}
		else{
			return $this->getResultAsObject("SELECT ".$column." FROM ".$table." ".$order);
		}
		
	}
	
	
	public function selectDistinct($column,$table="",$order=""){
		if(empty($table)){
			return $this->getResultAsObject("SELECT DISTINCT ".$column." FROM ".$this->DbTable." ".$order);
		}
		else{
			return $this->getResultAsObject("SELECT DISTINCT ".$column." FROM ".$table." ".$order);
		}
		
	}
	
	
	
	
	/*Filter result with where clause*/
	public function where($column='*',$clause,$table=''){
		
		if(empty($clause)){
			return false;
		}
		else{
			
			if(empty($table)){
				/*Filter active object result*/
				if($column=='*' or empty($column)){
						$result = $this->getResultAsObject("SELECT * FROM ".$this->DbTable." WHERE ".$clause);
					}
					else{
						$result = $this->getResultAsObject("SELECT ".$column." FROM ".$this->DbTable." WHERE ".$clause);
					}
			}
			else{
				/*Filter specified object result*/
				if($column=='*' or empty($column)){
						$result = $this->getResultAsObject("SELECT * FROM ".$table." WHERE ".$clause);
					}
					else{
						$result = $this->getResultAsObject("SELECT ".$column." FROM ".$table." WHERE ".$clause);
					}
			}
			
			return $result;
		}
		
		
	}
	
	
	public function where_distinct($distinct_column,$clause,$table=''){
		
		if(empty($clause)){
			return false;
		}
		else{
			
			if(empty($table)){
				/*Filter active object result*/
						$result = $this->getResultAsObject("SELECT DISTINCT ".$distinct_column." FROM ".$this->DbTable." WHERE ".$clause);
			}
			else{
				/*Filter specified object result*/
				
						$result = $this->getResultAsObject("SELECT DISTINCT ".$distinct_column." FROM ".$table." WHERE ".$clause);
					
			}
			
			return $result;
		}
		
		
	}
	
	
	
	/*Checking if data already exist*/
	public function dataExists($column,$data,$table=""){
		
		$data = $this->filter($data);
		$column = $this->filter($column);
		if(empty($column) or empty($data)){
			return 'arg_error';
		}
		else{
			if(empty($table)){
				/*Check active object*/
				$check = $this->sqlQuery("SELECT id FROM ".$this->DbTable." WHERE ".$column." = '".$data."' LIMIT 1 ");
					if($check->num_rows > 0){
						return true;
					}
					else{
						return false;
					}
			}
			else{
				/*Check specified object*/
				$check = $this->sqlQuery("SELECT id FROM ".$table." WHERE ".$column." = '".$data."' LIMIT 1 ");
					if($check->num_rows > 0){
						return true;
					}
					else{
						return false;
					}
			}
			
		}
	}
	
	
	
	
	
	
	/*Check if particular field(attribute) is not empty*/
	public function checkField($id,$column,$table=""){
		$id = (int)$id;
		if(empty($id) or empty($column)){
			return false;
		}
		else{
			if(empty($table)){
				/*Check active object*/
				$result = $this->where($column," id=".$id." ");
				$result = array_shift($result);
				if(!empty($result->$column)){
					return true;
				}
				else{
					return false;
				}
			}
			else{
				/*Check specified object*/
				$result = $this->where($column," id=".$id." ",$table);
				$result = array_shift($result);
				if(!empty($result->$column)){
					return true;
				}
				else{
					return false;
				}
			}
		}
		
	}
	
	
	
	
	/*Display object attributes*/
	public function showFields($table=""){
		global $connection;
		$columns=array();
		if(empty($table)){
			$check = $connection->query("SHOW FIELDS FROM ".$this->DbTable);
		}
		else{
			$check = $connection->query("SHOW FIELDS FROM ".$table);
		}
		
		while($result = $check->fetch_array(MYSQLI_NUM)){
			$columns[]=$result[0];
		}
		$columns ="'".join("','",$columns)."'"; 
		echo $columns;
	}
	
	/*Display attribute as text*/
	public function showFieldsAsValue($table=""){
		global $connection;
		$columns=array();
		if(empty($table)){
			$check = $connection->query("SHOW FIELDS FROM ".$this->DbTable);
		}
		else{
			$check = $connection->query("SHOW FIELDS FROM ".$table);
		}
		while($result = $check->fetch_array(MYSQLI_NUM)){
			$columns[]=$result[0];
		}
		$columns =join(",",$columns); 
		echo $columns;
	}
	
	
	
	/*Display attribute as variable*/
	public function showFieldsAsVariable($table){
		global $connection;
		$columns=array();
		if(empty($table)){
			$check = $connection->query("SHOW FIELDS FROM ".$this->DbTable);
		}
		else{
			$check = $connection->query("SHOW FIELDS FROM ".$table);
		}
		while($result = $check->fetch_array(MYSQLI_NUM)){
			$columns[]=$result[0];
		}
		$columns= "$".join(',$',$columns).";";
		echo $columns;
	}
	
	
	
	
	
	/*Bootstrap alert methods*/
	public function alertDanger($message="Operation Failed"){
		return "<div class='alert alert-danger text-center col-lg-10 center-block top-10'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <i class='fa fa-exclamation-circle'></i> ".$message."</div>";
	}
	public function alertEmpty($message="Operation Failed"){
		return "<div class='alert alert-danger text-center col-lg-10 center-block top-10'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <i class='fa fa-trash'></i> ".$message."</div>";
	}
	
	public function alertSuccess($message="Operation Successful"){
		return "<div class='alert alert-success text-center col-lg-10 center-block top-10'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <i class='fa fa-check-circle'></i> ".$message."</div>";
	}
	
	public function alertWarning($message="Operation partially Failed"){
		return "<div class='alert alert-warning text-center col-lg-10 center-block top-10'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <i class='fa fa-warning'></i> ".$message."</div>";
	}
	
	public function alertInfo($message="Operation Successful"){
		return "<div class='alert alert-info text-center col-lg-10 center-block top-10'>  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> <i class='fa fa-thumbs-up'></i> ".$message."</div>";
	}
	
	}
	
	$DB = new MysqlConnection();
	

	
	
 ?>