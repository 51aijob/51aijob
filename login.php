
<?php   
$mailname=$_REQUEST['mailname'];  
$password=$_REQUEST['pass'];  
if($mailname=="" or $password=="") {
	echo "<script>alert('用户名或密码不能为空！');history.go(-1)</script>";
	exit;
}
$conn = mysql_connect('52.221.163.108','root');  
if(!$conn) die('error: failed to connect mysql');
mysql_select_db("resumeDB",$conn);
$query=mysql_query("SELECT mailname,password FROM hrinfo WHERE mailname = '$mailname'",$conn);  
$row = mysql_fetch_array($query,MYSQL_ASSOC);  
if($_REQUEST['submit']){      
    if($row['mailname']==$mailname && $row['password']==$password){  
        setcookie('mailname',$mailname,time()+24*3600,"/");  
        echo "<script>alert('登陆成功，欢迎！');window.location= 'hrboard.html.php';</script>";  
    }  
    else echo "<script>alert('登陆失败，请稍后再试！');history.go(-1)</script>";  
}  
include('login.html');?>  
