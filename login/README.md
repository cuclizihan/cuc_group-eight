# 基于网页的用户注册与登录系统
1. check.php

允许用户注册到系统<br/>
用户名的合法字符集范围：中文、英文字母、数字<br/>
类似：-、_、.等合法字符集范围之外的字符不允许使用<br/>
用户口令长度限制在36个字符之内
对用户输入的口令进行强度校验，禁止使用弱口令<br/>
```
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
   ```
若用户的输入信息规范且两次密码输入一致，则连接数据库：
```
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
```

使用select操作查询该用户名是否已经存在于数据库的对应表中，如果用户名未重复，则利用libsodium库中的 
`string sodium_crypto_pwhash(int $output_length, string $password, string $salt, int $opslimit, int $memlimit)` 函数对用户的明文口令进行hash，将hash后的结果和过程中随机生成的盐值与对应的用户名一起存入数据库，随后提示用户注册成功，自动转到登录页面:
```
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
        		{
        			//$hash=hash("sha256", $psw,$raw_output=FALSE);
                    $out_len = SODIUM_CRYPTO_AEAD_AES256GCM_KEYBYTES; 
                    $salt = isset($argv[1]) ? hex2bin($argv[1]) : random_bytes(SODIUM_CRYPTO_PWHASH_SALTBYTES); 
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
        		}
```
Argon2加密算法详解：
```
$salt = random_bytes(SODIUM_CRYPTO_PWHASH_SALTBYTES);
$out_len = SODIUM_CRYPTO_SIGN_SEEDBYTES; 
$seed=sodium_crypto_pwhash($out_len,$password,$salt, SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE, SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE);
```
首先生成一个随机的16位盐值，然后闲置输出长度为32位，调用 `sodium_crypto_pwhash()` 函数，输入参数分别是输出长度、加密的明文口令、盐值、函数推荐的两个库常量。

2. logincheck.php

接受提交表单中的信息并将其保存在常量中，如果用户名和密码都不为空，则连接数据库，并选定数据库和表。
```
if(isset($_POST["submit"]) && $_POST["submit"] == "登录")
{
	//将用户名和密码存入变量中，供后续使用
    $user = $_POST["username"];
    $psw = $_POST["userpwd"];
    $hash = hash("sha256", $psw,$raw_output=FALSE);
    if($user == "" || $psw == "")
    {
        //用户名或者密码其中之一为空，则弹出对话框，确定后返回当前页的上一页
        echo "<script>alert('请输入用户名或密码！'); history.go(-1);</script>";
    }
    else{
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
```
使用加密哈希函数对用户输入的口令进行校验，由于注册时生成的盐值是随机的，因此用统一算法校验时，需要用到注册时生成的唯一盐值，在注册时和用户名以及生成的散列值一起被储存在数据库中。
将与用户名对应的盐值取出存放于常量中，select与用户输入用户名相同的记录，将其转化为关联数组，取出属性名为salt的项的值赋给salt变量，随后用用户给出的口令与盐值和输出长度一起进行相同的哈希运算，将得到的结果与数据库中存放的散列值进行比较，若一致则通过验证，用户登陆成功。

文件上传时要求限制匿名用户的操作功能，因此要在登陆成功时进行 `session_start()` 函数的调用，记录某一用户访问该网站的`cookie`，并将用户名存入`SESSION`的系统常量中，以便后面文件上传时进行身份判别。
```
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
```