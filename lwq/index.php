<!-- //首页 -->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Index</title>
	<style type="text/css">
		*{
			margin: 0;
			padding:0;
			border: none;
			list-style: none;
		}
		.box{
			height: 400px;
			width: 600px;
			margin:100px auto;
			/*background-color: pink;*/
		}
		h1{
			height: 50px;
			line-height: 50px;
			text-align: center;
			margin-top:20px;
		}
		.nav{
			margin-top: 50px;
			height: 100px;
			background-color: white;
		}
		ul li{
			float:left;
			width: 150px;
			text-align: center;
		}
		ul li a{
			display: inline-block;
			height: 58px;
			width: 120px;
			line-height: 58px;
			text-decoration: none;
			color:#fff;
		}
/*		ul li a:hover{
			display: inline-block;
			height: 58px;
			width: 120px;
			line-height: 58px;
			text-decoration: none;
			color:yellowgreen;
			background-color: #aaa;
			border:1px solid #fff;
		}*/
		.one{
			background: url(bg2.png) no-repeat;
		}
		.two{
			background: url(bg3.png) no-repeat;
		}
		.three{
			background: url(bg1.png) no-repeat;
		}
		.four{
			background: url(bg7.png) no-repeat;
		}
		.one:hover{
			background: url(bg8.png) no-repeat;
			color: #000;
		}
		.two:hover{
			background: url(bg8.png) no-repeat;
			color: #000;
		}
		.three:hover{
			background: url(bg8.png) no-repeat;
			color: #000;
		}
		.four:hover{
			background: url(bg8.png) no-repeat;
			color: #000;
		}
		.chicken{
			width: 316px;
			height: 291px;
			margin:0 auto;
		}
		.footer{
			border-top: 1px solid #000;
			margin-top: 20px;
			text-align: center;
		}
	</style>
</head>
<body>
	<div class="box">
		<h1>中传放心传导航</h1>
		<div class="nav">
			<ul>
				<li><a class="one" href="注册页面.php">新用户注册</a></li>
				<li><a class="two" href="登录页面.php">老用户登录</a></li>
				<li><a class="three" href="upload.php">文件上传</a></li>
				<li><a class="four" href="download.php">文件下载</a></li>
			</ul>
		</div>
		<div class="chicken">
			<img src="puppy.jpg" alt="">
		</div>
		<div class="footer">
		</div>
	</div>
</body>
</html>