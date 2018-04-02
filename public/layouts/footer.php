
	
	<div class="top-10">
	
		<div class="row no-margin">
			<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 no-margin">
				<ul class="footer-links">
				
				
					<!--li><a href="#"><i class="fa fa-link"></i> Link</a> </li>
					<li><a href="#"><i class="fa fa-link"></i> Link2</a> </li>
					<li><a href="#"><i class="fa fa-link"></i> Link3</a> </li-->
					
					
				
				</ul>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 text-right no-margin">
				<p class="text-bold"> All Rights reserved. &copy; Copyright <?php echo app_name; ?>  <?php echo date("Y") ?> </p>
			</div>
		</div>
		
		
	</div>

	
	
	
	<div id='searchModal' class='modal fade' role='dialog'>
  <div class='modal-dialog col-lg-12 center-block'>

    <!-- Modal content-->
    <div class='modal-content col-lg-12 col-md-12 col-sm-12 col-xs-12'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><i class="fa fa-window-close text-red"></i></button>
        <h4 class='modal-title'><?php echo app_name ?> Search engine</h4>
      </div>
      <div class="modal-body">
	 

		
			 <form>
				<div class="form-group">
					<div class="input-group">
						<input name="text" type="text" required value="<?php echo isset($_GET["text"]) ? $_GET["text"] : "" ;  ?>" class="form-control  no-radius" placeholder="Enter school name, acronym, description.....">
						<span class="input-group-btn">
						<button class="btn btn-info  no-radius" name="search" type="submit"><i class="fa fa-search"></i> <span class="mobile-off">Search</span></button>
						</span>
					</div>
				</div>					
			</form>
		 
		
		
		</div>
	  
       
		
		<div class="searchResultDisplay top-30"></div>
		
		
		
      </div>

    </div>

  </div>
</div>
	

	<script type="text/javascript" src="<?php echo public_url("layouts/js/script.js") ?>"></script>
	<script type="text/javascript" src="<?php echo public_url("layouts/js/function.js") ?>"></script>
	</body>
</html>