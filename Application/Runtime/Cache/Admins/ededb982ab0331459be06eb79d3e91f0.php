<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE>
<html>
<head>
<title>登陆与注册</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link href="/public/css/common.css" rel="stylesheet">
<link href="/public/css/style.css" rel="stylesheet">
<script type="text/javascript" src="/public/js/jquery.min.js" ></script>
</head>
<body class="login-body">
		<div class="login-div">
			<form name="login">
				<ul>
					<li>
						<input type="text" name="username" placeholder="用户名">
					</li>
					<li>
						<input type="password" name="passwd" placeholder="密码">
					</li>
					<li>
						<input type="text" name="verify" placeholder="验证码">
					</li>
					<li>
						<img class="verify" src="<?php echo U('Verify/verify1');?>">
					</li>
					<p><input type="checkbox" name="remeber" value="1">remeber</p>
					<li>
						<input type="button" id="login-bt" value="登陆">
					</li>
					<p id="tip-msg" class="c-red "> </p>
				</ul>
			</form>
		</div>
</body>
<script type="text/javascript">
var ajaxini = true;
	$(document).ready(function(){
		$('.verify').click(function(){
			$(this).attr('src',$(this).attr('src')+'?'+Math.random());
		});
		$('#login-bt').click(function(){
			var username = $('input[name="username"]').val();
			var password = $('input[name="passwd"]').val();
			var verify = $('input[name="verify"]').val();
			
			if(username==''){
				set_msg('用户名不能为空!');
				return false;
			}
			
			if(password==''){
				set_msg('密码不能为空!');
				return false;
			}
			if(verify==''){
				set_msg('验证码不能为空!');
				return false;
			}
			set_msg('');
			if(ajaxini){
				ajaxini = false;
				$.ajax({
					url:'<?php echo U('Login/login');?>',
					data:{
						username:username,
						password:password,
						verify:verify
					},
					type:'post',
					//dateType:'json',
					success:function(data){
						console.log(data);
						ajaxini = true;
					}
				});
			}
		})
		function set_msg(msg){
			$('#tip-msg').text(msg);
		}
	});
</script>
</html>