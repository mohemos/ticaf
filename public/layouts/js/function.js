$(document).ready(function(){
	
	/*enter the root website url here*/
	var public_url="http://localhost/ticaf/public/";
	var ajax_url=public_url+"ajax/";
	var utility = ajax_url+'utility.php';

	

	
	
	$('[data-toggle="tooltip"]').tooltip();
	String.prototype.nl2br = function(){ return this.replace(/\n/g, "<br />"); }
			
	
	
	$(".clearDefault").click(function(e){
		e.preventDefault();
	});

	
	
				
				
/*Processing Registration Form*/
	$(".formProcessor").submit(function(e){
		e.preventDefault();
		var formUrl = $(this).attr('ajaxUrl');
		var that=$(this).find(".formAlert");
		that.html("<div class='text-center'><i class='fa fa-spinner fa-spin fa-2x'></i></div>");
		$.ajax({
			url:ajax_url+formUrl,
			type:"POST",
			data:new FormData(this),
			cache:false,
			contentType:false,
			processData:false,
			success:function(data){
				/*actions to server response code, more actions can be added here*/
				if(data==172){window.location=public_url+'home';}
				else if(data==300){
						location.reload();
						$(window).scrollTop(0);
					}
				else if(data==301){ location.reload();}
				else{
					that.show();
					that.html(data);
					setTimeout(function(){that.hide(1000);},30000);
					}
			
			}
		});
	});
	
		
	
		
	/*JSon Form processor*/
	$(".jsonFormProcessor").submit(function(e){
		e.preventDefault();
		var formUrl = $(this).attr('ajaxUrl');
		var thisForm = $(this);
		var that=thisForm.find(".formAlert");
		that.html("<div class='text-center'><i class='fa fa-spinner fa-spin fa-2x'></i></div>");
		$.ajax({
			url:ajax_url+formUrl,
			type:"POST",
			data:new FormData(this),
			dataType:"json",
			cache:false,
			contentType:false,
			processData:false,
			success:function(data,status){
				if(data.display==301){location.reload();
					$(window).scrollTop(0);}
				if(data.display=="url"){window.location=data.url;};
				if(data.field==0){
					thisForm.find("input").val('');
					thisForm.find("textarea").val('');
					thisForm.find("select").val('');
				}
				if(data.stay==1){that.html(data.display);}
				else{
					that.show();
					that.html(data.display);
					setTimeout(function(){that.hide(1000)},5000);
				}
			}
		});
	});
	
	
	
	
	
	$(".toggleImager").click(function(){
			$(this).siblings(".logoPicker").click();
			});
			
			$(".logoPicker").change(function(event){
				var showImage=$(this).siblings(".toggleImager").children(".showImage");
			showImage.show();
			$(this).siblings(".toggleImager").children(".i").hide();
				showImage.attr("src",URL.createObjectURL(event.target.files[0]));
			});	
	
	
});