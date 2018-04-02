<?php	
	define("ds",DIRECTORY_SEPARATOR);
	define("ls","/");
	define('http_type','http://');
	
	
	/*Root directory*/
	define("root_path","C:".ds."xampp".ds."htdocs".ds."ticaf");
	
	/*Root url*/
	define("root_url",http_type."localhost/ticaf");
	
	
	
	
	
	/*path constants*/
	define('plugin_path',root_path.ds.'plugins');
	define('config_path',plugin_path.ds.'config');
	define('object_path',plugin_path.ds.'object');
	define('cache_path',plugin_path.ds.'cache');
	define('controller_path',plugin_path.ds.'controller');
	define('public_path',root_path.ds.'public');
	define('image_path',public_path.ds.'images');
	define('file_path',public_path.ds.'files');
	define('plugin_url',root_url.ls.'plugins');
	define('config_url',plugin_url.ls.'config');
	define('public_url',root_url.ls.'public');
	
	/*Application name*/
	define('app_name','TiCAF');
	
	
	
	define("image_url",public_url.ls."images");
	define("file_url",public_url.ls."files");
	define("maxFileSize",10000);
	define("maxImageSize",5000);
	$imageFormatArray = array('png','jpg','gif','jpeg');
	$fileFormatArray = array('pdf','doc','docx','psd','csv','xlsx','xls','ppt','pptx','iso','zip','rar');
	
	
	$config = parse_ini_file(config_path.ds."config.ini");
	
	$connection=new mysqli($config['connectionServer'],$config['connectionUsername'],$config['connectionPassword'],$config['connectionDB']) or die (mysqli_error()."Unable to connect to database");
	
	$connect=mysqli_connect($config['connectionServer'],$config['connectionUsername'],$config['connectionPassword'],$config['connectionDB']) or die(mysqli_error()); 
	
	
	/*Config Files*/
	require_once config_path.ds."session.php";
	require_once config_path.ds."functions.php";
	require_once config_path.ds."custom-functions.php";
	require_once config_path.ds."db.php";
	require_once config_path.ds."paginate.php";

$display='';
$pageDescription='';
$title=app_name;

