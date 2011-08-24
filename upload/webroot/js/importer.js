$(document).ready(function(){
	$('.importAction').click(function(){
		var service_name = $(this).attr('title');

		$('#importer_service').html(service_name);
		$('#importer_form form').attr('action', $(this).attr('href'));
		$('#importer_form').show();

		return false;
	});

	$('.importerSubmit').click(function(){

		if($('.importerLogin').val() == ''){
			alert('Login is required');
			return false;
		}

		if($('.importerPassword').val() == ''){
			alert('Password is required');
			return false;
		}

		$('#importer_form').hide();
		$('#importer_inprogress').show();

		var postdata = "login=" + $('.importerLogin').val() + "&password=" + $('.importerPassword').val();
		$.ajax({	url: $('#importer_form form').attr('action'),
				type: 'POST',
				data: postdata,
				dataType: 'json',
				success: function(data){
					if (data.error) {
						alert(data.error);
					} else {
						var curval=$('#recipient_list').val();
						if (curval) {
							$('#recipient_list').val(curval+', '); 
						}
						
						$.each(data.contacts, function (id, email) {
							$('#recipient_list').val($('#recipient_list').val() + email + ', ');
						});
					}
					
					
					
					$('.importerLogin').val('');
					$('.importerPassword').val('');
					$('#importer_inprogress').hide();
				}
			});

		return false;
	});
});