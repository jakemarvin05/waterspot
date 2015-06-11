(function( $ ){
		var global_settings = {
			form_counter:0,
			form_class_name:'AjaxUploadForm',
			form_id:'image-form-',
			form_file_id:'image-input-',
			delete_image_url:''
			};
		var form_counter = 0;
		$.fn.AjaxUpload = function(options){
			var defaults = { 
				div_image_class: 'images',
				ajax_url: '',
				file_input_name:'images[]'
				};
			var settings = $.extend( {}, defaults, options );
			settings.form_counter = global_settings.form_counter++;
			settings.this = this;
		
			
			load_form();
			load_link();
			load_event();
			function load_form(){
				$(settings.this).before('<form id="'+global_settings.form_id+settings.form_counter+'" class="'+global_settings.form_class_name+'"  accept-charset="utf-8" method="post" enctype="multipart/form-data" style="display:none;" novalidate="novalidate" action=""><input id="'+global_settings.form_file_id+settings.form_counter+'" class="fileupload" type="file" multiple="multiple" name="data[images][]" accept="image/*"></form>');
			}
			function load_link(){
				 $('.'+settings.div_image_class).prepend('<div class="service-image no-image add-image"><a id="image-link-'+settings.form_counter+'" href="Javascript:void(0);"><img src="/img/add-more.jpg"> </a><div class="loader" style="display:none;"></div></div>');
				$('#image-link-'+settings.form_counter).bind('click',function(){
					$('#'+global_settings.form_file_id+settings.form_counter).click();
				});
			}
			function load_event(){
				$('#'+global_settings.form_file_id+settings.form_counter).bind('change',function(){
					$('#'+global_settings.form_id+settings.form_counter).submit();
					
					});
				$('#'+global_settings.form_id+settings.form_counter).submit(function(){
					//var file = $('#'+global_settings.form_file_id+settings.form_counter).files;
					/*
					$.each(file,function(i,v){
						alert(i+'--'+v);
						});
					*/
					var form = new FormData(this);
					var formData = $(this);
					$('.no-image > .loader').show();
					$.ajax({
						url: settings.ajax_url,
						async: false,
						data: form,
						dataType:'json', 
						type:'post',
						cache: false,
						contentType: false,
						processData: false,
						success: function(data) {
							$.each(data,function(i,v){
							//alert(i+'--'+v.temp_name);
								$('.'+settings.div_image_class).show();
								$('.'+settings.div_image_class).prepend('<div class="service-image"><img src="'+v.temp_name+'" /><input type="hidden" name="'+settings.file_input_name+'" value="'+v.image+'"/><button class="close-image" data-id="'+v.image+'"></button></div>');
							});
							$('.service-image').delegate(".close-image","click",function(){
								$(this).parent().remove();
								$.ajax({
									url: settings.delete_image_url,
									async: false,
									data: {"image" : $(this).attr('data-id')},
									dataType:'json',
									type:'post',
									cache: false,
									success: function(data){
										
									}
								}); 
								
								
								
								return false;
							});
							
							$('.no-image > .loader').hide();
						}
					});
					
					
					
					return false;
					
					});
				
			
			}
			
			$('.service-image').delegate(".close-image","click",function(){
				$(this).parent().remove();
				return false;
			});
				
		};
	})(jQuery);
