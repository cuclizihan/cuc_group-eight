<!DOCTYPE html>
<html>
<div class="content" align="center">
<head>
    <title>Login</title>
    <meta charset="utf-8" />
    <style>
       *{
            margin:0;
            padding: 0;
            border:none;
        }
        h1 a{
            color:#2e3e84;
            text-decoration: none;
        }
        h1 a:hover{
            color:#838ca7;
            text-decoration: underline;
        }
        span{
            color:#7f99b3;
            font-family: 楷体;
            font-size: 16px;
        }
        .background{
            width: 1023px;
            height: 618px;
            background: url("login.jpg");
            margin: 0px auto;
        }
        .box{
            float:left;
            width: 499px;
            height: 500px;
            /*background-color: pink;*/
            /*border:1px solid rgba(255,255,255,0.3);*/
            margin: 60px 262px;
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
            margin:30px auto;
        }
        .system input[type="text"],input[type="password"]{
            height: 30px;
            width: 165px;
            background-color:rgba(255,255,255,0.4);
            border:1px solid #392a63;
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
        .system input[type="submit"]{
            height: 78px;
            width: 80px;
            background: url("loginbutton3.jpg");
            margin-top: 50px;
            text-indent: -2000px;
        }
        .system input[type="submit"]:hover{
            height: 78px;
            width: 80px;
            background: url("loginbutton.jpg");
            margin-top: 50px;
            text-indent: -2000px;
        }
        .pic{
            margin-top: 50px;
            display: inline-block;
            width: 80px;
            height: 78px;
            background: url("signupbutton2.jpg");
        }
        .pic:hover{
            margin-top: 50px;
            display: inline-block;
            width: 80px;
            height: 78px;
            background: url("signbutton3.jpg");
        }
    </style>
</head>
<body>
<!-- 登录界面 login.php-->
<div class="background">
    <div class="box">
        <div class="title">
            <h1><a href="index.php" src="点此返回首页">用户登陆页面</a></h1>
        </div>
        <div class="system">
            <form action="logincheck.php" method="post">
                <table>
                    <tr>
                        <td align="right"><span>用户名：</span></td>
                        <td align="right"><input type="text" name="username" value=""/></td>
                    </tr>
                    <tr>
                        <td align="right"><span>密 码：</span></td>
                        <td align="right"><input type="password" name="userpwd" maxLength="36"/></td>
                    </tr>
                    <tr>
                        <td align="right"><input type="submit" name="submit" value="登录" /> </td>
                        <td align="right"><span>还没有账号？ </span><a href="注册页面.php" class="pic"></a></td>
                    </tr>
                </table>
    </form>
        </div>  
    </div>
</div>
</body>
</div>
</html>