<?php 
	require_once 'config.php';
	require_once controller_path('user.php');
?>




<div class="container top-100 bottom-100">
		
			
	<div class="col-md-5 center-block top-50 bottom-50">
		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#school"><i class="fa fa-lock"></i> Login</a></li>
		  <li><a data-toggle="tab" href="#study"><i class="fa fa-user-circle"></i> Register</a></li>
		</ul>

		<div class="tab-content">
		  <div id="school" class="tab-pane fade in active">
			   <form method="POST" class="formProcessor" ajaxUrl="user.php">
				  <div class="formAlert"></div>
					<div class="form-group">
					<label>Email Address</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-envelope"></i> </span>
						<input name="loginEmail" type="email" class="form-control" required placeholder="Enter username" />
					</div>
					</div>
					
					<div class="form-group">
					<label>Password</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-key"></i> </span>
						<input name="password" type="password" class="form-control" required placeholder="Enter password" />
					</div>
					</div>
					
					
					<button name="schoolAdminLogin" type="submit" class="btn btn-info btn-block">
					<i class="fa fa-unlock-alt"></i> Login
					</button>
					
				</form>
		  </div>
		  
		  <div id="study" class="tab-pane fade">
				<form class="formProcessor" ajaxUrl="user.php">
						<div class="formAlert"></div>
						
						<div class="form-group">
							<label>Full name</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user-circle"></i> </span>
								<input name="fullname" type="text" pattern="[0-9a-zA-z ]*" class="form-control" required placeholder="Full name " />
							</div>
						</div>
						
												
						<div class="form-group">
							<label>Email address</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-envelope"></i> </span>
								<input name="registerEmail" type="email" class="form-control" required placeholder="Enter username" />
							</div>
						</div>
						
							
						<div class="form-group">
							<label>Phone number</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-tty"></i> </span>
								<input name="phone" type="text" class="form-control" required placeholder="Enter phone number" />
							</div>
						</div>
						
							
						
						<div class="form-group">
							<button class="btn btn-info btn-block" type="submit" >
								<i class="fa fa-check-circle"></i> Sign Up
							</button>
						</div>
						
					</form>
				
		  </div>
		</div>
	  
       
		
	</div>
		
		
</div>







<?php require_once layout_path("footer.php"); ?>


