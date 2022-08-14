<?php
    //判断用户是否已经登录状态
    session_start();
    if(isset($_SESSION['userame'])&&!empty($_SESSION['username'])){
        echo "Welcome！Dear";
        echo $_SESSION['userame'];
    }
    else{
        echo"<script>alert('未登录用户没有上传文件权限！请登录');window.location.href='登录页面.php';</script>";
    }
    //接收提交文件的用户信息   
    $fileintro=$_POST['fileintro']; 
    //
    $file_size=$_FILES['file']['size'];  
    if($file_size>10*1024*1024) {  
        echo "文件过大，不能上传大于10M的文件";  
        exit();  
    }  
  
    $file_type=$_FILES['file']['type'];  
    echo $file_type;  
    if($file_type!="image/jpeg" && $file_type!='image/pjpeg'&& $file_type!='image/gif') {  
        echo "文件类型只能为jpg或gif格式";  
        exit();  
    }  
    if ($_FILES["file"]["error"] > 0)
    {
        echo "Error: " . $_FILES["file"]["error"] . "<br />";
    }
    else
    {
        echo "Upload: " . $_FILES["file"]["name"] . "<br />";
        echo "Type: " . $_FILES["file"]["type"] . "<br />";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
        echo "Stored in: " . $_FILES["file"]["tmp_name"]."<br/>";
    }

   //把文件转存到你希望的目录（不要使用copy函数）  
    $uploaded_file=$_FILES['file']['tmp_name'];  
  
    //我们给每个用户动态的创建一个文件夹  
    $user_path="D:/Lenovo/xampp/upload/".$username;  
    //判断该用户文件夹是否已经有这个文件夹  
    if(!file_exists($user_path)) {  
        mkdir($user_path);  
    }  
    if (file_exists($user_path.$_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    //$move_to_file=$user_path."/".$_FILES['myfile']['name'];  
    $file_true_name=$_FILES['file']['name'];   
    //echo "$uploaded_file   $move_to_file"; 
    if(move_uploaded_file($uploaded_file,$user_path.$file_true_name)) {  
        echo $_FILES['file']['name']."上传成功";  
        echo "Stored in: " . $user_path.$file_true_name;
    } else {  
        echo "上传失败";  
    }  
    
?>