<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>登陆</title>

    <!-- Bootstrap -->
    <link href="/admin/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/admin/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/admin/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="/admin/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="/admin/build/css/custom.min.css" rel="stylesheet">
	<script src="/admin/vendors/jquery/dist/jquery.min.js"></script>
	<script>
		function do_login(){
			if($('form').find('#username').val() && $('form').find('#password').val()){
				$.ajax({
					url: "/login",
					method: 'POST',
					dataType: 'json',
					data: $("form").serialize(),
					success: function(resp)
					{
						
						if(resp.code == 200)
						{
							window.location.href = '/manage';
						}
						else
						{
							alert(resp.msg);
						}
					}
				});
			}
		}
	</script>
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form>
              <h1>登 陆</h1>
			  {{ csrf_field() }}
              <div>
                <input type="text" class="form-control" name="username" id="username" placeholder="账号" required="required" />
              </div>
              <div>
                <input type="password" class="form-control" name="password" id="password" placeholder="密码" required="required" />
              </div>
              <div>
                <a class="btn btn-default submit" id="signin" onclick="do_login()">登 陆</a>
                
              </div>

              <div class="clearfix"></div>

            </form>
          </section>
        </div>

        
      </div>
    </div>
  </body>

  <script src="/admin/vendors/validator/validator.js"></script>
 
</html>
