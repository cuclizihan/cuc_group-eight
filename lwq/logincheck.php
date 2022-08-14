<?php
//登录处理界面 logincheck.php
//判断是否按下提交按钮
if(isset($_POST["submit"]) && $_POST["submit"] == "登录")
{//1
	//将用户名和密码存入变量中，供后续使用
    $user = $_POST["username"];
    $psw = $_POST["userpwd"];
    $hash = hash("sha256", $psw,$raw_output=FALSE);
    if($user == "" || $psw == "")
    {
        //用户名或者密码其中之一为空，则弹出对话框，确定后返回当前页的上一页
        echo "<script>alert('请输入用户名或密码！'); history.go(-1);</script>";
    }
    else{//2
    	//确认用户名密码不为空，则连接数据库
        $conn = mysqli_connect("localhost","root","root");//数据库帐号密码为安装数据库时设置
        if(mysqli_errno($conn))
        {
            echo mysqli_errno($conn);
            exit;
        }
        else{
        	echo "connect successful";
        }
        mysqli_select_db($conn,"userdb");
        mysqli_set_charset($conn,'utf8');
        $sql = "select username,userpwd from user where username = '$user' and userpwd = '$hash'";
        $result = mysqli_query($conn,$sql);
        $num = mysqli_num_rows($result);
        if($num)
        {
            // mysqli_query($conn,"update user set state = '1' where username = '$user'");
            // if(mysqli_affected_rows($conn)!=1) die(0);
            session_start();
            $_SESSION['username'] = $user;
        	echo "<script>alert('登陆成功！');window.location.href='upload.php';</script>";
            //echo "<script>登录成功!;window.location.href='success.html';</script>";
            //var_dump($_COOKIE);
            
        }
        else{
        	echo "<script>alert('用户名或密码不正确！');history.go(-1);</script>";
        }
    }//2
}//1
else{
	echo "<script>alert('提交未成功！');</script>";
}
?>