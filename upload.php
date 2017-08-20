<?php 

//$lifetime = 3600*24;
//session.gc_maxlifetime in php.ini
session_start(); 

header("content-type:text/html;charset=utf-8");
//header("content-type:text/html;charset=gb2312");

date_default_timezone_set("PRC");
$pImg=$_FILES['pImg'];

if($pImg['error']==UPLOAD_ERR_OK){
    //get extention name
    $extName=strtolower(end(explode('.',$pImg['name'])));
    $prefixName=strtolower(reset(explode('.',$pImg['name'])));
    $filename=$prefixName.".".date("Ymdhis").".".$extName;
    $dest="uploadFiles/".$filename;

//$allowedExts = array("pdf", "doc", "docx"); 
$allowedExts = array("pdf"); 
$extension = end(explode(".", $_FILES["pImg"]["name"]));
//print($extension);
//print($_FILES["pImg"]["type"]);
if ((($_FILES["pImg"]["type"] == "application/msword") || ($_FILES["pImg"]["type"] == "application/pdf")) && ($_FILES["pImg"]["size"] < 20000000) && in_array($extension, $allowedExts)) {
   move_uploaded_file($pImg['tmp_name'],$dest);
} else {
   echo "<script>alert('请上传小于2M的pdf格式的简历！');history.go(-1)</script>";
   exit;
}

$email="";
$phone="";
$file=file($dest);
//header("Content-type: application/pdf");
//header("Content-type: application/msword");

echo $dest;
$phonerule="/1[3,5,7,8]{1}[0-9]{1}[0-9]{8}|0[0-9]{2,3}-[0-9]{7,8}(-[0-9]{1,4})?/";
$emailrule="/([a-z0-9_\-\.]+)@(([a-z0-9]+[_\-]?)\.)+[a-z]{2,3}/i";
//convert to text file for better parse
$pdftotext = shell_exec("/usr/local/bin/pdftotext $dest ${dest}.txt");

$handle = fopen("${dest}.txt", "r");
if ($handle) {
    while (!feof($handle)) {
        $line = fgets($handle, 512);
        if (preg_match($phonerule,$line,$result0)) {
		//print($result0['0']);	
		$phone=$result0['0'];
	} 
        if (preg_match($emailrule,$line,$result1)) {
		//print($result1['0']);	
		$email=$result1['0'];
	} 
	if ($email!="" and $phone!="") {
    		fclose($handle);
		break;	
	}
    }
    fclose($handle);
}

if ($email=='' or $phone=='') {
        echo "<script>alert('简历中电话或邮箱为空, 或修改格式！');history.go(-1)</script>";
	$old_file=$dest;
	$old_file_txt=$dest.'.txt';
	$file_name = end(explode('/',$dest));
	$new_file = 'uploadFilesINVALID/'.$file_name;	
	$new_file_txt = 'uploadFilesINVALID/'.$file_name.'.txt';	
	if(!(rename($old_file,$new_file))) {print("Failed to rename file");}
	if(!(rename($old_file_txt,$new_file_txt))) {print("Failed to rename file");}
        exit;
}

    //have uploaded file
    $myfile=$pImg;

    //set timeout
    $time_limit=60;
    set_time_limit($time_limit);
    unlink($myfile['tmp_name']);

    //file format, name, and size
    $file_type=$myfile["type"];
    $file_name=$myfile["name"];
    $file_size=$myfile["size"];
    $start_time=time();

    //connect database, and save info into db
    $conn=mysql_connect("localhost","root",'');
    if(!$conn) die("error : mysql connect failed");
    mysql_select_db("resumeDB",$conn);

    $countsql="select count(*) as total from resumeinfo where email='$email' and phone='$phone'";
    $query=mysql_query($countsql,$conn);
    $res=mysql_fetch_array($query);
    $count=$res['total'];

    if(isset($_SESSION['email']) and isset($_SESSION['phone'])) {
	$phone_cookie = $_SESSION['phone'];
	$email_cookie = $_SESSION['email'];
    } else {
	$phone_cookie = "";
	$email_cookie = "";
    }

    //print($phone_cookie);
    //print($email_cookie);
    //print($phone);
    //print($email);

    //no cookie, after 24 hours, or new upload
    if($phone_cookie=="" OR $email_cookie=="") {
	$countsql="select count(*) as total from userinfo where email='$email' and phone='$phone'";
    	$query=mysql_query($countsql,$conn);
    	$res=mysql_fetch_array($query);
    	$count=$res['total'];	
	//#1, new upload, not register ever
	if($count==0) {
		#a,insert data into resume
		$sql="insert into resumeinfo (email,phone,file_location,file_type,file_name,file_size,start_time,status) values ('$email','$phone','$dest','$file_type','$file_name','$file_size','$start_time',0)";
    		$result=mysql_query($sql,$conn);
		if(!($result)) {print("Failed to write info to database");}
		#b,create user password and write to userinfo
		$password=get_password(6);
		print($password);
		$sql="insert into userinfo (email,phone,password,count) values ('$email','$phone','$password','1')";
		$result=mysql_query($sql,$conn);
		if(!($result)) {print("Failed to write info to database");}	
		#c,send confirmation mail
                upload_confirmation($phone,$email,$password);
		
		#d, save to cookie
		//setcookie("email",$email,time()+24*3600,"/");
		//setcookie("phone",$phone,time()+24*3600,"/");
		$_SESSION['email'] = "$email"; 
		$_SESSION['phone'] = "$phone"; 
		
	} else {
	//#2, after 24 hours, replace
		echo '<script>alert("Please login with acount and password sent to you mail firstly");location.href="'.'../login/userlogin.html'.'"</script>';		
		exit;
	}
    } else {
	//#3, cookie exist, during 24 hours
	if($phone_cookie==$phone and $email_cookie=$email) {
		#a,rename the old one with new
		$orig_dest_sql="select file_location from resumeinfo where email='$email' and phone='$phone'";
		$query=mysql_query($orig_dest_sql,$conn);
		$orig_dest_array=mysql_fetch_array($query);
		$orig_dest=$orig_dest_array['file_location'];
	 	$old_file = $orig_dest;
		$file_name = end(explode('/',$old_file));
		$new_file = 'uploadFilesREP/'.$file_name;	
		if(!(rename($old_file,$new_file))) {print("Failed to rename file");}

		$old_txt = $old_file.'.txt';
		$old_file_txt = 'uploadFilesREP/'.$file_name.'.txt';	
		if(!(rename($old_txt,$old_file_txt))) {print("Failed to rename file");}

		#b, update resumeinfo
		$sql="update resumeinfo set email='$email',phone='$phone',file_location='$dest',file_type='$file_type',file_name='$file_name',file_size='$file_size',start_time='$start_time' where email = '$email' and phone = '$phone'";
		$result=mysql_query($sql,$conn);
		if(!($result)) {print("Failed to update resumeinfo database");}

		#c, update count in userinfo
		$count_orig="select count from userinfo where email='$email' and phone='$phone'";
                $query=mysql_query($count_orig,$conn);
                $count_orig_array=mysql_fetch_array($query);
                $orig_count=$count_orig_array['count'];
		$count=$orig_count+1;
		$count_remind=6-$count;
		$sql="update userinfo set count = '$count' where email = '$email' and phone = '$phone'";
		$result=mysql_query($sql,$conn);
		if($count>6){echo '<script>alert("The maximun count of modification is only 6, and the times your tried is more than 6!");location.href="'.'../index/HTML/index.html'.'"</script>';exit;}
                if(!($result)) {print("Failed to update userinfo database");} else {echo '<script>alert("You have only '.$count_remind.' times");location.href="'.'../survey/survey.html'.'"</script>';} 
	} else {
	//#4, upload another one
		$file_name = end(explode('/',$dest));
		$dest_txt = $dest.'.txt';
		$del_file = 'uploadFilesDEL/'.$file_name;	
		$del_file_txt = 'uploadFilesDEL/'.$file_name.'.txt';	
		if(!(rename($dest,$del_file))) {print("Failed to rename file");}
		if(!(rename($dest_txt,$del_file_txt))) {print("Failed to rename file");}
		echo '<script>alert("Not allow for upload another resume during 24 hours");location.href="'.'../index/HTML/index.html'.'"</script>';		
		exit;
	}
		
    }

    mysql_close($conn);
    
    //setcookie("file_location",$dest,time()+24*3600, "/");
    $_SESSION['file_location'] = $dest; 

    //recover timeout
    set_time_limit(30); 

    echo "Submitted! ";
    if ($result>0) {echo '<script>alert("Done！");location.href="'.'../survey/survey.html'.'"</script>'; } 
}else{
  echo "<script>alert('上传文件错误, 请重试！');history.go(-1)</script>";
}

function get_password($length) {
	$str = "1234567890"; 
	for($i=0;$i<$length;$i++)
	{
		$passwd.=$str{mt_rand(0,9)};
	}
	return $passwd;
}

function upload_confirmation($phone,$email,$password)
{
        $subject = "51AIjob resume upload verification mail";
        $headers = "From: email@domain.com \r\n";
        $headers .= "Reply-To: email@domain.com \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $message = '<html><body>';
        $message.='<div style="width:550px; background-color:#CC6600; padding:15px; font-weight:bold;">';
        $message.='51AIjob';
        $message.='</div>';
        $message.='<div style="font-family: Arial;">Confiramtion mail have been sent to your email id<br/>';
        $message.='click on the below link in your verification mail id to verify your account ';
        $message.="<a href='upload_confirmation.php?email=$email&phone=$phone'>click</a>";
        $message.='</div>';
        $message.='<div>';
        $message.='---#^.^#---</br>';
        $message.='Account: '.$email.'; Password:'.$password;
        $message.='</div>';
        $message.='</body></html>';

        mail($email,$subject,$message,$headers);
}

?>
