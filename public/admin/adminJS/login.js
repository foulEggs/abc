(function (window) {
				
	jQuery(document).ready(function($)
	{
		console.log($("#signin"))
		// Validation and Ajax action
		$("#signin").click(function(){
			alert(1)
				$.ajax({
					url: "do_login",
					method: 'POST',
					dataType: 'json',
					data: {
						
						username: $(form).find('#username').val(),
						pwdencrypt: $(form).find('#passwd').val(),
					},
					success: function(resp)
					{
						//console.log(resp);return;
						if(resp.accessGranted)
						{
							window.location.href = '/trades/trades_operation';
						}
						else
						{
							toastr.error(resp['errors'], "Invalid Login!", opts);
						}
					}
				});
			});
		
		// Set Form focus
		//$("form#login .form-group:has(.form-control):first .form-control").focus();
	});
	
	
})(window);