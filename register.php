
<?php   
$mailname=$_REQUEST['mailname'];  
$password=$_REQUEST['pass'];  
$address=$_REQUEST['address'];  
$phone=$_REQUEST['phone'];  
//$datetail=time();
$mailprefix=strtolower(reset(explode('@',$mailname)));
$mailsuffix=strtolower(end(explode('@',$mailname)));
//$uniqinfo=$mailprefix.$datetail."";
$uniqinfo=$mailprefix;

//$nonallowedExts = array("163.com", "126.com", "yahoo.com", "sina.com", "gmail.com");
$nonallowedExts = array("gmail.com");
if (in_array($mailsuffix, $nonallowedExts)) {
	echo "<script>alert('请输入公司邮箱！');history.go(-1)</script>";
}

$conn = mysql_connect('52.221.163.108','root','');  
if(!$conn) die("error : mysql connect failed");
mysql_select_db("resumeDB",$conn);
$query=mysql_query("SELECT mailname FROM hrinfo WHERE mailname = '$mailname'",$conn);
$row = mysql_fetch_array($query,MYSQL_ASSOC);
//print($row['mailname']);
if($_REQUEST['submit']){  
    if ($row['mailname']==$mailname) {
		echo "<script>alert('用户名已存在！');</script>";	
    } else {
	$rand=rand(100000,100000000);
    	if(mysql_query("insert into hrinfo (mailname,password,uniqinfo,confirmid,verified,adminverified,address,phone) values('$mailname','$password','$uniqinfo','$rand',0,0,'$address','$phone')",$conn)){  
		//setcookie("mailname",$mailname,time()+7200,"/");  
		register_confirmation($name,$rand,$mailname,$password);	
		echo "<script>alert('验证邮件已发至注册邮箱，请验证后登陆！');window.location= 'login.html';</script>";  
    	}else {  
       		echo "<script>alert('注册失败，请稍后再试！');history.go(-1)</script>";  
    	}  
    }
    mysql_close($conn);
}  

function register_confirmation($name,$rand,$mailname,$password)
{
	$subject = "Account Verification mail";
	$headers = "From: mailname@domain.com \r\n";
	$headers .= "Reply-To: mailname@domain.com \r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	$message = '<html><body>';
	$message.='<div style="width:550px; background-color:#CC6600; padding:15px; font-weight:bold;">';
	$message.='51AIjob account confirmation mail';
	$message.='</div>';
	$message.='<div style="font-family: Arial;">Confiramtion mail have been sent to your mailname id<br/>';
	$message.='click on the below link in your verification mail id to verify your account ';
	$message.="<a href='register-confirmation.php?mailname=$mailname&confirmation_code=$rand'>click</a>";
	$message.='</div>';
	$message.='<div>';
        $message.='---#^.^#---</br>';
        $message.='Your Account: '.$email.'; Password:'.$password;
        $message.='</div>';
	$message.='</body></html>';

	mail($mailname,$subject,$message,$headers);
}

include('register.html');?>  
