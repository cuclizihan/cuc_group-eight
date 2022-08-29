<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Register</title>
	<style type="text/css">
		*{
			margin:0;
			padding: 0;
			border:none;
		}
		h1{
			color:#9a3d6d;
		}
		span{
			color:#3e8fc8;
			font-size:16px;
			font-weight: 700;
			font-family: 楷体;
		}
		span.font{
			color:rgba(0,0,0,0.5);
			font-size:16px;
			/*font-weight: 700;*/
			font-family: 楷体;
		}
		.background{
            width: 1023px;
            height: 618px;
			background: url("register.jpg");
			margin: 0px auto;
		}
		.box{
			float:left;
			width: 499px;
			height: 550px;
			/*background-color: pink;*/
			margin: 45px 300px;
			position: relative;
		}
		.title{
			height: 100px;
			width: 209px;
			line-height: 100px;
			position: absolute;
			left: 145px;
			/*background-color: red;*/
		}
		.system{
			width: 399px;
			height: 450px;
			/*background-color: green;*/
			position: absolute;
			top:100px;
			left: 50px;
		}
		.system table{
			margin-left: 50px;
		}
		.system input[type="text"],input[type="password"]{
			height: 30px;
			width: 165px;
			background-color:rgba(255,255,255,0.4);
			border:1px solid #68b5df;
		}
		.system input[type="text"]{
			background: url("userbutton.png") no-repeat;
			padding-left:35px;
		}
		.system input[type="password"]{
			background: url("passwordbutton.png") no-repeat;
			padding-left:35px;
		}
		.system tr{
			height: 70px;
		}
		.system tr.comment{
			font-size: 13px;
			height: 10px;
			line-height: 10px;
			color:rgba(0,0,0,0.6)/*4ba1d6*/;
			font-family: 楷体;
		}
		.system input[type="submit"]{
			height: 78px;
			width: 80px;
			background: url("signbutton3.jpg");
			margin-top: 30px;
			text-indent: -2000px;
		}
		.system input[type="submit"]:hover{
			height: 78px;
			width: 80px;
			background: url("signupbutton2.jpg");
			margin-top: 30px;
			text-indent: -2000px;
		}
		.pic{
			margin-top: 30px;
			display: inline-block;
			width: 80px;
			height: 80px;
			background: url("loginbutton.jpg");
		}
		.pic:hover{
			margin-top: 30px;
			display: inline-block;
			width: 80px;
			height: 80px;
			background: url("loginbutton3.jpg");
		}
	</style>
</head>
<body>
<div class="background">
	<div class="box">
		<div class="title">
			<h1><a href="index.php">用户注册页面</a></h1>
		</div>
		<div class="system">
			<form action="check.php" method="post">
			<table>
				<tr>
					<td align="right"><span>用户名：</span></td>
					<td><input type="text" name="username" value=""/></td>
				</tr>
				<tr class="comment">
					<td align="right" colspan="2">
						<p>*用户名长度大于两位，不得出现不合法字符</p>
					</td>
				</tr>
				<tr>
					<td align="right"><span>密 码：</span></td>
					<td><input type="password" name="userpwd" maxLength="36"/></td>
				</tr>
				<tr class="comment">
					<td align="right" colspan="2">
						<p>*密码长度6-36位，必须同时包含字母和数字</p>
					</td>
				</tr>
				<tr>
					<td align="right"><span>确认密码：</span></td>
					<td><input type="password" name="confirm" maxLength="36"/></td>
				</tr>
				<tr class="comment">
					<td align="right" colspan="2">
						<p>*请确认两次输入密码一致</p>
					</td>
				</tr>
				<tr>
                    <td align="right"><input type="submit" name="submit" value="注册"/></td>
                    <td align="right">
						<span class="font">已有账号？ </span><a class="pic" href="登录页面.html"></a>
					</td>
            	</tr>
			</table>
			</form>
		</div>	
	</div>
</div>
</body>
</html>

