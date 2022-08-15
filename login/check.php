<?php
//注册处理界面 regcheck.php
if(isset($_POST["submit"]) && $_POST["submit"] == "注册")
{
 	//获取表单数据
    $user = $_POST["username"];
    $psw = $_POST["userpwd"];
    $psw_confirm = $_POST["confirm"];
    
    if($user == "" || $psw == "" || $psw_confirm == "")
    {
        echo "<script>alert('请确认信息完整性！'); history.go(-1);</script>";
    }
    else 
    {
    	$minLen=6;
		$maxLen=36;
    	$match='/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{'.$minLen.','.$maxLen.'}$/'; 
    	if(!preg_match("/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]{1,}$/", $user))//两位以上的字母，数字或下划线
    	{
    		echo  "<script>alert('用户名不合法'); history.go(-1);</script>";
    	}
		else if(!preg_match($match,$psw)) //检验口令是否合法
		{
			echo  "<script>alert('密码不合法'); history.go(-1);</script>";
		}
		else{
			if($psw == $psw_confirm)
			{
				echo "建立连接";
				//建立连接
				$conn = mysqli_connect("localhost","root","root");
				//连接数据库,帐号密码为自己数据库的帐号密码
				//如果连接失败就返回上一次链接错误的错误代码
				if(mysqli_errno($conn))
				{
					echo mysqli_error($conn);
					exit;
				}
				else{
					echo "\nconnect successful";
				}
				mysqli_select_db($conn,"userdb"); //选择数据库
        		mysqli_set_charset($conn,'utf8'); //设定字符集
        		$sql = "select username from user where username = '$user'"; //准备SQL语句，查询用户名
        		$result = mysqli_query($conn,$sql); //执行SQL语句
        		$num = mysqli_num_rows($result); //统计执行结果影响的行数

        		if($num) //如果已经存在该用户
        		{
            		echo "<script>alert('用户名已存在'); history.go(-1);</script>";
        		}
        		else //不存在当前注册用户名称
        		{//5
        			//$hash=hash("sha256", $psw,$raw_output=FALSE);
                    $out_len = SODIUM_CRYPTO_AEAD_AES256GCM_KEYBYTES;
                    $salt = isset($argv[1]) ? hex2bin($argv[1]) : random_bytes(SODIUM_CRYPTO_PWHASH_SALTBYTES); // 16
                    $password = sodium_crypto_pwhash(
                    $out_len,
                    $psw,
                    $salt,
                    SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                    SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
                    );
                    $hash=bin2hex($password);
                    $salt=bin2hex($salt);
        			echo "\nhash";
        			echo $hash;
            		//准备SQL语句插入用户数据
            		$sql_insert = "insert into user(id,username,userpwd,salt) values (null,'$user','$hash','$salt')";
            		//执行SQL语句
            		$result = mysqli_query($conn,$sql_insert);
           			if($result)
            		{
                		echo "<script>alert('注册成功！');window.location.href='登录页面.html'</script>";
                		mysqli_close($conn);
            		}
            		else
            		{
                		echo "<script>alert('系统繁忙，请稍候！'); history.go(-1);</script>";
            		}
        		}//5
			}//4
			else{
        		echo "<script>alert('密码不一致！'); history.go(-1);</script>";
    		}
		}//3
	}//2
}//1
else
{
    echo "<script>alert('提交未成功！');</script>";
}
    
    
	
	


?>