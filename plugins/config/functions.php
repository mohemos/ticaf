<?php
	
	/*Automatically load classes*/
	function __autoload($class){
		require_once object_path(strtolower($class).'.php');
	}
	
	function sendVCode($email,$subject='Verification code'){
		$code = rand(10000,100000);
		$message = 'Your verification code is: '.$code."";
		if(mail($email,$subject,$message)){ 
			return $code;
		}
		else{ return false;}
	}
	
	
	
	
	
	
	/* collect cached pages */
	function pageCache($uri=null){
		$uri = is_null($uri)? $_SERVER['REQUEST_URI'] : $uri ;
		/*Preparing file name and directory*/
		$file = cache_path(md5($uri).'.php');
		
		/*Checking if file exist or expired */
		if(is_file($file) and (time() < filemtime($file)+ strtotime("+ 1days"))){
			include $file;
			return true;
		}
		else{
			return false;
		}
	}
	
	
	/*Cache page to file*/
	function cachePage($uri=null){
		$uri = is_null($uri)? $_SERVER['REQUEST_URI'] : $uri ;
		/*Preparing file name and directory*/
		$file = cache_path(md5($uri).'.php');
		
		/*Writing page to file*/
		fileWriter($file,ob_get_contents());
		ob_end_flush();
	}
	
	
	/*Clear cache pages*/
	function clearCache($uri=null){
		if(is_null($uri)){
			$uri = $_SERVER['REQUEST_URI'];
			/*Preparing file name and directory*/
			$file = cache_path(md5($uri).'.php');
			return unlink($file) ? true : false;
		}
		else{
			
		}
	}
	
	
	/*destroy cookie*/
	function cookie_destroy($name){
		setcookie(''.$name.'',1,1);
	}
	
	/*Javascript popup*/
	function testScript($text){
		echo '<script>alert("'.$text.'");</script>';
	}
	
	
	function start_transaction(){
		global $connection;
		$connection->autocommit(FALSE);
	}
	
	function rollback_transaction(){
		global $connection;
		$connection->rollback();
	}
	
	function commit_transaction(){
		global $connection;
		if ($connection->commit()) {
return true;
		}
		else{
$connection->rollback();
return false;
		}

	}
	
	
	
	
	
	
	
	/*Page file name*/
	function pageFile(){
		$file = $_SERVER['SCRIPT_NAME'];
		$file = explode('/',$file);
		$file = end($file);
		$file = explode('.',$file);
		return array_shift($file);
	}
	
	/*creating new object based on table in the database*/
	function createObject($DbTable){
		$file = object_path(strtolower($DbTable).'.php');
		$controllerFile = controller_path(strtolower($DbTable).'.php');
		global $connection;
		
		/*Collecting object properties*/
		$check = $connection->query("SHOW FIELDS FROM ".$DbTable);
		
		$dataArray="";
		$variables = "";
		$columns = $check->num_rows - 1;
		$count=0;
		while($result = $check->fetch_array(MYSQLI_NUM)){
if($count<$columns) {
	$dataArray.="'".$result[0]."',";
	$variables.="$".$result[0].",";
}
	
else {
	$dataArray.="'".$result[0]."'";
	$variables.="$".$result[0]."";
}
	

$count++;
		}
		
		
		
		
		/*Preparing file for writing*/
	$objectName = ucwords($DbTable);
	$content='<?php 
	class '.$objectName.' extends MysqlConnection{
	protected $DbTable="'.$DbTable.'";
	protected $DbTableColumns = array('.$dataArray.');
	public 	'.$variables.';
	
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
		';
		
		
	/*Generating controller file*/
	$controllerContent = "<?php 
	$".$objectName." = new ".$objectName."();
	/*Enter all your ".$objectName." controller codes here*/
?>
	";
	
	
	
		/*Creating object file*/
		if(is_file($file) and is_file($controllerFile)){
if(fileWriter($file,$content) and fileWriter($controllerFile,$controllerContent)){ echo "Object created!"; }
else{ return false; }
		}
		
		else{
	if(fileWriter($file,$content) and fileWriter($controllerFile,$controllerContent) ){
		echo "Object created!";
	}
	else{
		return false;
	}
}
		
		
		
	}

	
	
	/*creating new object based on table in the database and require it in the plugin file*/
	function createObject_require($DbTable){
		$file = object_path($DbTable.'.php');
		global $connection;
		
		/*Collecting object properties*/
		$check = $connection->query("SHOW FIELDS FROM ".$DbTable);
		
		$dataArray="";
		$variables = "";
		$columns = $check->num_rows - 1;
		$count=0;
		while($result = $check->fetch_array(MYSQLI_NUM)){
if($count<$columns) {
	$dataArray.="'".$result[0]."',";
	$variables.="$".$result[0].",";
}
	
else {
	$dataArray.="'".$result[0]."'";
	$variables.="$".$result[0]."";
}
	

$count++;
		}
		
		
		
		
		/*Preparing file for writing*/
	$objectName = ucwords($DbTable);
	$content='<?php 
	class '.$objectName.' extends MysqlConnection{
	protected $DbTable="'.$DbTable.'";
	protected $DbTableColumns = array('.$dataArray.');
	public 	'.$variables.';
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
	
	$'.$objectName.' = new '.$objectName.'();
	
	
	
 ?>
		';
		
		/*Creating object file*/
		if(is_file($file)){
if(fileWriter($file,$content)){ echo "Object created!"; }
else{ return false; }
		}
		
	else{
		if(fileWriter($file,$content)){

/*Adding object to plugin list*/
$newPlugin = rtrim(fileReader(config_path('plugin.php')));

/*Removing new line*/
$newPlugin = strtr($newPlugin,['
'=>'']);

$newPlugin.='
require_once object_path.ds."'.$DbTable.'.php";';



if(fileWriter(config_path('plugin.php'),rtrim($newPlugin))){
	echo "Object created!";
}
else{
	echo "Object created but not added to plugin, kindly add it up!";
}



		}
		else{
return false;
		}
		}
		
		
		
	}

	
	function emailToUsername($email){
		if(empty($email)){
return false;
		}
		else{
$email = explode('@',$email);
return $email[0];
		}
	}
	
	
	
	function logout(){
		redirect_to(public_url("logout.php"));
	}
	
	
	function referer(){
		$referer="";
		if(isset($_SERVER['HTTP_REFERER'])){$referer=$_SERVER['HTTP_REFERER'];}
		
		else if(isset($_SESSION["referer"])){$referer = $_SESSION["referer"];}
		else {$referer = public_url();}
		
		$page = http_type.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		
		if($referer != $page){
			$_SESSION["referer"]=$referer;
		}
		
		return $_SESSION["referer"];
	}
	
	
	
	function thisPage(){
		return $_SERVER["REQUEST_URI"];
	}
	
	function download($file,$output=false){
		if(empty($file)){
				return false;
				}
		else{
			if($output==false){
						$format = explode(".",$file);
						$format = end($format);
						$output = time().".".$format;
						}
				$type=filetype($file);
				header('Content-Type: '.$type.'');
				header('Content-Disposition: attachment; filename="'.$output.'"');
				readfile($file);
				}
				
	}
	
	function useRichText(){
		require_once(layout_path("richtext.php"));
	}
	
	function youtubeCode($link=""){
		if(empty($link)){
return false;
		}
		else{
$link = explode("=",$link);
		$code = end($link);
		return $code;
		}
		
	}
	
	function youtubeLink($code){
		if(empty($code)){
return false;
		}
		else{
return "https://www.youtube.com/watch?v=".$code;
		}
		
	}
	
	

	function redirect_to($location=null){
		if(is_null($location)){
			header("location:".public_url('logout.php'));
		}
		else{
			header("location:".$location);
		}
		
		exit();
	}
	
	
	function refererLink($referer=""){
		if(isset($referer) and !empty($referer)){
	$link = explode("/",$referer);
	if(!in_array("link",$link)){
		$_SESSION["referer_page"]=$referer;
	}
	}
	}
	

	/*Directory functions*/
	function config_path($path=""){
		return config_path.ds.$path;
	}

	function object_path($path=""){
		return object_path.ds.$path;
	}

	
	function controller_path($path=""){
		return controller_path.ds.$path;
	}

	
	function public_url($path=""){
		return public_url.ls.$path;
	}
	
	function public_path($path=""){
		return public_path.ds.$path;
	}

	function cache_path($path=""){
		return cache_path.ds.$path;
	}

	
	
	function image_url($path=""){
		return image_url.ls.$path;
	}

	function image_path($path=""){
		return image_path.ds.$path;
	}
	

	function file_url($path=""){
		return file_url.ls.$path;
	}

	function file_path($path=""){
		return file_path.ds.$path;
	}
	
	function layout_url($path=""){
		return public_url.ls.'layouts'.ls.$path;
	}

	function layout_path($path=""){
		return public_path.ds.'layouts'.ds.$path;
	}
	
	function addon_url($path=""){
		return public_url.ls.'addon'.ls.$path;
	}

	function addon_path($path=""){
		return public_path.ds.'addon'.ds.$path;
	}
	
	
	function admin_url($path=""){
		return public_url.ls.'admin'.ls.$path;
	}
	
	function admin_path($path=""){
		return public_path.ds.'admin'.ds.$path;
	}
	
	function adminLogout(){
		redirect_to(admin_url('logout.php'));
	}
	
	function brText($text){
		echo nl2br($text);
	}

	
	function fileWriter($file,$content,$mode='wt'){
		
		if($handle = fopen($file,$mode)){
if(fwrite($handle,$content)){
	fclose($handle);
	return true;
	
}
else{
	return false;
}
		}
	else{
		return false;
	}
	}
	
	
	function fileReader($file,$mode='r'){
		if($handle=fopen($file,$mode)){
$fileSize = filesize($file);
$content= fread($handle,$fileSize);
fclose($handle);
return $content;
		}
		else{
return false;
		}
	}
	
	
	function configReader($file,$mode="r"){
		if($handle=fopen($file,$mode)){
$fileSize = filesize($file);
$content= fread($handle,$fileSize);
fclose($handle);

/*Converting configuration details into array*/
$content = explode("|",$content);
$config = array();

foreach($content as $data){
	$data = explode("=",$data);
	$option = trim($data[0]);
	
	$config[$option] = trim($data[1]);
}


return $config;
		}
		else{
return false;
		}
	}
	
	
	
	

	function uploadFile($name,$acceptedFormat=array(),$location,$rename,$fileSize){
		$fileNames = array();
		$fileSize*=1000;
		$fileType="";
		$count = count($_FILES[$name]['name']);
		if($count==1){
$format=""; $tmpLoc="";
/*Checking data structure*/
if(is_array($_FILES[$name]['name'])){
	$format =$_FILES[$name]['name'][0];
	$tmpLoc = $_FILES[$name]['tmp_name'][0];	
	if($_FILES[$name]['size'][0] > $fileSize){
		return array(false,"File too large");
	}
}
else{
	$format =$_FILES[$name]['name'];
	$tmpLoc=$_FILES[$name]['tmp_name'];
	if($_FILES[$name]['size'] > $fileSize){
		return array(false,"File too large");
	}
}

/*Collecting file format*/
$format = explode(".",$format);
$format = strtolower(end($format));
		if(in_array($format,$acceptedFormat)){

/*Renaming file name*/
if(empty($rename)){
	$rename=time().rand(1000,10000).".".$format;
}
else{
$rename = makeStrtr($rename);

	$rename.="-".time().rand(1000,10000).".".$format;
}

if(move_uploaded_file($tmpLoc,$location.$rename)){
	return array(true,$rename);
}
else{
	return array(false,fileUploadError($_FILES[$name]['error']));
}

		}
		else{
return array(false,"File format not supported");
		}
		}
		else if($count>1){
$uploadedFileLocations = array();
/*Processing multiple file upload*/
for($x=0;$x<$count;$x++){
	
	/*Checking file size*/
	if($_FILES[$name]['size'][$x] > $fileSize){
		if(!empty($uploadedFileLocations)){
foreach($uploadedFileLocations as $fileLoc){
unlink($fileLoc);
}
		}

		return array(false,"Some file sizes are too large!");
	}
	
	
		/*Collecting file format*/
	$format = $_FILES[$name]['name'][$x];
	$format = explode(".",$format);
	$format = strtolower(end($format));
	if(in_array($format,$acceptedFormat)){
	
	/*Renaming file name*/
	$newFileName="";
	if(empty($rename)){
		$newFileName=time().rand().$x.".".$format;
	}
	else{
		$rename = makeStrtr($rename);
		$newFileName=$rename."-".time().rand().$x.".".$format;
	}
	
	if(move_uploaded_file($_FILES[$name]['tmp_name'][$x],$location.$newFileName)){
		$fileNames[]=$newFileName;
		$uploadedFileLocations[]=$location.$newFileName;
	}
	else{
		foreach($uploadedFileLocations as $fileLoc){
unlink($fileLoc);
		}
		return array(false,fileUploadError($_FILES[$name]['error'][$x]));
	}
	
	}
	else{
		foreach($uploadedFileLocations as $fileLoc){
unlink($fileLoc);
		}
		return array(false,"File format not supported");
	}
}

return array(true,$fileNames);
		}
		else{
return array(false,fileUploadError($_FILES[$name]['error'][$x]));
		}
	}
	


	
	function fileUploadError($error){
		$fileErrors = array(
	0 => 'There is no error, the file uploaded with success',
	1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
	2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
	3 => 'The uploaded file was only partially uploaded',
	4 => 'No file was uploaded',
	6 => 'Missing a temporary folder',
	7 => 'Failed to write file to disk.',
	8 => 'A PHP extension stopped the file upload.',
);
		return $fileErrors[$error];
	}
	
	/*
	function removeImage($name){
		if(!empty($name)){
if(is_file(image_path.$name)){
	if(unlink(image_path.$name)){
		return array(true,"Picture deleted!");
	}
	else{
		return array(false,"An error occured while deleting picture");
	}
}
else{
	return array(false,"Picture does not exist!".$name);
}
		}
		else{
return array(false,"No picture found!");
		}
	}
	*/
	
	
	
	function deleteFile($location,$message="File deleted!"){
		if(is_file($location)){
			if(unlink($location))
				return array(true,$message);
			
			else
				return array(false,"An error occured while deleting file");
			
		}
		else
			return array(false,"File does not exist!");	
	}
	


	function filterUrl($value){
		$value = stripslashes($value);
		$value = trim($value);
		return $value;
	}

	function makeStrtr($value){
	$value = trim($value);
	$value = strtr($value,[" "=>"-","&"=>"and","amp"=>"","'"=>"",'"'=>"",";"=>"",","=>"","."=>"","?"=>"",")"=>"","("=>"","`"=>"","`"=>"","`"=>"",">"=>"","<"=>"","^"=>"","!"=>"","@"=>"","#"=>"","$"=>"","%"=>"","*"=>"","+"=>"","{"=>"","}"=>"","/"=>"-","\\"=>"-",":"=>"-"]);
		return $value;
	}
	
	
	function removeStrtr($value){
		//$value = strtr($value,["-"=>" ","and"=>"&"]);
		$value = strtr($value,["-"=>" "]);
		return $value;
	}
	
	
	function limitText($text,$limit,$continue) {
		if(strlen($text)){
$text = substr($text,0,$limit);
return $text.$continue;
		}
		else{
return $text;
		}
    }
	
	

	function readMore($text,$limit,$url=""){
		$display="";
		$text_array=str_word_count($text,1);
		if(count($text_array)>$limit){
for($x=0;$x<$limit;$x++) {
	$display.=$text_array[$x]." ";
}

return $display.$url;
		}
		return $text;
		
	}


		/* Function for image resizer */
	function resize($currentLoc,$newLoc,$newWidth,$newHeight){
	global $imageFormatArray;
	if(!file_exists($currentLoc)){return array(false,"File does not exist!");}
	$previousName = explode(".",$currentLoc);
	$previousFormat = array_pop($previousName);
	$name = join('',$previousName);
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$fileFormat = finfo_file($finfo,$currentLoc);
	$fileFormat = explode("/",$fileFormat);
	$format = strtolower(array_pop($fileFormat));
	
	$format= $format=="jpeg"? "jpg" : $format;
	
	if(!in_array($format,$imageFormatArray)){
		return array(false,"File format not supported!");
	}
	
	
	
	if($format != $previousFormat){
		
		/*Updating new file location*/
$newLoc = explode(".",$newLoc);
array_pop($newLoc);
$newLoc = join('',$newLoc).".".$format;
		if(rename($currentLoc, $name.".".$format )){
$currentLoc = $name.".".$format;
		}
	}
	
	
	
	list($imageWidth,$imageHeight)=getimagesize($currentLoc);
	$scale=$imageWidth/$imageHeight;
	$new_scale=$newWidth/$newHeight;
	if($new_scale>$scale) {
		$newWidth=$newHeight*$scale;
	}
	else{
		$newHeight=$newWidth/$scale;
	}
	
	$temp="";
	if($format=="gif"){
	$temp=imagecreatefromgif($currentLoc);
	}
	else if($format=="png"){
	$temp=imagecreatefrompng($currentLoc);
	}
	else{
	$temp=imagecreatefromjpeg($currentLoc);
	
	}
	
	if(($newHeight > $imageHeight) && ($newWidth >$imageWidth)){
		$newHeight=$imageHeight;
		$newWidth=$imageWidth;
	}
	
	$tci=imagecreatetruecolor($newWidth,$newHeight);
	$white = imagecolorallocate($tci,255,255,255);
	imagefill($tci,0,0,$white);
	imagecopyresampled($tci,$temp,0,0,0,0,$newWidth,$newHeight,$imageWidth,$imageHeight);
	if(imagejpeg($tci,$newLoc,84)){
		return array(true,$format);
	}
	else{
		return array(false,"An error occured while resizing image");
	}
}


		/* Function for image resizer */
	function resizeExact($currentLoc,$newLoc,$newWidth,$newHeight){
	global $imageFormatArray;
	if(!file_exists($currentLoc)){return array(false,"File does not exist!");}
	$previousName = explode(".",$currentLoc);
	$previousFormat = array_pop($previousName);
	$name = join('',$previousName);
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$fileFormat = finfo_file($finfo,$currentLoc);
	$fileFormat = explode("/",$fileFormat);
	$format = strtolower(array_pop($fileFormat));
	
	$format= $format=="jpeg"? "jpg" : $format;
	
	if(!in_array($format,$imageFormatArray)){
		return array(false,"File format not supported!");
	}
	
	
	
	if($format != $previousFormat){
		
		/*Updating new file location*/
$newLoc = explode(".",$newLoc);
array_pop($newLoc);
$newLoc = join('',$newLoc).".".$format;
		if(rename($currentLoc, $name.".".$format )){
$currentLoc = $name.".".$format;
		}
	}
	
	
	
	list($imageWidth,$imageHeight)=getimagesize($currentLoc);
	$scale=$imageWidth/$imageHeight;
	$new_scale=$newWidth/$newHeight;
	/*if($new_scale>$scale) {
		$newWidth=$newHeight*$scale;
	}
	else{
		$newHeight=$newWidth/$scale;
	}
	*/
	$temp="";
	if($format=="gif"){
	$temp=imagecreatefromgif($currentLoc);
	}
	else if($format=="png"){
	$temp=imagecreatefrompng($currentLoc);
	}
	else{
	$temp=imagecreatefromjpeg($currentLoc);
	
	}
	
	/*
	if(($newHeight > $imageHeight) && ($newWidth >$imageWidth)){
		$newHeight=$imageHeight;
		$newWidth=$imageWidth;
	}*/
	
	$tci=imagecreatetruecolor($newWidth,$newHeight);
	$white = imagecolorallocate($tci,255,255,255);
	imagefill($tci,0,0,$white);
	imagecopyresampled($tci,$temp,0,0,0,0,$newWidth,$newHeight,$imageWidth,$imageHeight);
	if(imagejpeg($tci,$newLoc,84)){
		return array(true,$format);
	}
	else{
		return array(false,"An error occured while resizing image");
	}
}


function uploadPix($name,$location,$newWidth,$newHeight,$rename="",$size=maxImageSize){
	global $imageFormatArray;
		$upload = uploadFile($name,$imageFormatArray,$location,$rename,$size);
		if($upload[0]==true){

$location.=$upload[1];
$resize = resize($location,$location,$newWidth,$newHeight);
if($resize[0]==true){
		$filename= explode(".",$upload[1]);
		return array(true,$filename[0].".".$resize[1]);
	}
	else{
		is_file($location)?unlink($location):"";
		return array(false,$resize[1]);
	}

		}
		else{
return array(false,$upload[1]);
		}
	}
	
	
	/*Resize and crete thumbnail*/
function uploadPix_thumbnail($name,$location,$newWidth,$newHeight,$rename="",$size=maxImageSize){
	global $imageFormatArray;
	
		$upload = uploadFile($name,$imageFormatArray,$location,$rename,$size);
		if($upload[0]==true){

$newLocation=$location.$upload[1];
$resize = resize($newLocation,$newLocation,$newWidth,$newHeight);
if($resize[0]==true){
	
		$filename= explode(".",$upload[1]);
		$filename = $filename[0].".".$resize[1];
		
		/*Generating thumbnail*/
		$thumbnailLoc = $location.ds."thumbnail";
		$originalLoc = $location.ds.$filename;
		
		file_exists($thumbnailLoc) ? "" : mkdir($thumbnailLoc) ;
		
		$thumbnail = resizeExact($originalLoc,$thumbnailLoc.ds.$filename,360,200);
		
		if($thumbnail[0]==true){
return array(true,$filename);
		}
		else{
is_file($newLocation)?unlink($newLocation):"";
is_file($originalLoc)?unlink($originalLoc):"";
is_file($thumbnailLoc.ds.$filename)?unlink($thumbnailLoc.ds.$filename):"";
return array(false,$thumbnail[1]);
		}
		
	}
	else{
		is_file($newLocation)?unlink($newLocation):"";
		return array(false,$resize[1]);
	}

		}
		else{
return array(false,$upload[1]);
		}
	}
 



function uploadPixExact($name,$location,$newWidth,$newHeight,$rename="",$size=maxImageSize){
	global $imageFormatArray;
		$upload = uploadFile($name,$imageFormatArray,$location,$rename,$size);
		if($upload[0]==true){

$location.=$upload[1];
$resize = resizeExact($location,$location,$newWidth,$newHeight);
if($resize[0]==true){
		$filename= explode(".",$upload[1]);
		return array(true,$filename[0].".".$resize[1]);
	}
	else{
		is_file($location)?unlink($location):"";
		return array(false,$resize[1]);
	}

		}
		else{
return array(false,$upload[1]);
		}
	}
 

function requiredField($data,$required){
		foreach($data as $key => $value){
if(in_array($key,$required) and empty(trim($value))){
		return false;
}
		}
		return true;
	}
	
	
	function errorDisplay($errors){
		if(is_array($errors) and !empty($errors)){

		$display="";
		foreach($errors as $error){
$display.=$error;
		}

		return $display;
		}
		else{
return false;
		}
	}

	
 ?>