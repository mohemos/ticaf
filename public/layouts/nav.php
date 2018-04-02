<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $title; ?></title>
	<meta name="description" content="<?php echo $pageDescription; ?>">
	<meta name="author" content="Afridemics">
	<base href="<?php echo public_url(); ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="icon" href="<?php echo image_url("afridemics-images".ls."icon.ico"); ?>" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	
	<?php 
	require_once layout_path("cdn.php");
	
	echo isset($pageRel)? $pageRel : "";

	?>
</head>
<body>
<nav class="navbar mynav navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="sr-only">Menu</span>
         <span class="fa fa-navicon"></span> <span>Menu</span>
      </button>

                    
                     <a class="navbar-brand" href=<?php echo public_url(); ?>>
					 <?php echo app_name ?> </a>
     </div>
	 
	 
	 
    <div class="collapse navbar-collapse" id="myNavbar">
      
      <ul class="nav navbar-nav navbar-right">
	  
	
	  
		<li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Drop down
          <span class="caret"></span></a>
          <ul class="dropdown-menu no-space">
		  
            <li><a href="<?php echo public_url(); ?>"><i class="fa fa-chevron-right"></i> Link1</a></li><li class="divider no-space"></li>
			
           <li><a href="<?php echo public_url(); ?>"><i class="fa fa-chevron-right"></i> Link2</a></li><li class="divider no-space"></li>
			
           
            <li><a href="#"><i class="fa fa-plus-circle"></i> Last link</a></li> 
          </ul>
        </li>
        
        <li class="divider no-space"></li>
		
	
		<?php if($Session->active()){ ?>
		<li><a href="<?php echo public_url('logout'); ?>"><i class="fa fa-power-off text-red"></i> Logout</a></li>
		<?php } else { ?>
        <li><a href="<?php echo public_url('user'); ?>"><i class="fa fa-user-circle"></i> User</a></li>
		
		<?php } ?>
		
        <li><a data-target="#searchModal" data-toggle="modal" href="<?php echo thisPage(); ?>#"><i class="fa fa-search"></i> Search</a></li>
        
		</ul>
	
	  
    </div>
  </div>
</nav>