
<?php

if(isset($_REQUEST))
{
	$mailname=$_COOKIE['mailname'];
	if ($mailname == "") {
		echo '<script>alert("请先登录，再发布招聘信息！");location.href="'.'./login.html'.'"</script>';
		exit;
	}
	$mailsuffix=strtolower(end(explode('@',$mailname)));

        $conn=mysql_connect("52.221.163.108","root",'');
        if(!$conn) die("error : mysql connect failed");
        mysql_select_db("resumeDB",$conn);
        $hrverifiedsql="select verified,adminverified from hrinfo where mailname='$mailname'";
        $query=mysql_query($hrverifiedsql,$conn);
        $res=mysql_fetch_array($query);
        $verified=$res['verified'];
        $adminverified=$res['adminverified'];
        mysql_close($conn);
        if(!($verified)) {echo '<script>alert("请登陆您的注册邮箱，完成邮件确认");history.go(-1)</script>';exit;}
        if(!($adminverified)) {echo '<script>alert("后台审核中，通过后会邮件通知您");history.go(-1)</script>';exit;}

        $job_location=$_REQUEST['btn_select0'];
        $degree=$_REQUEST['btn_select1'];
        if($degree == "Doctor") {$degree=5;}
        if($degree == "Master") {$degree=4;}
        if($degree == "Bachelor") {$degree=3;}
        if($degree == "College") {$degree=2;}
        if($degree == "Other") {$degree=1;}

//$job_location=$_REQUEST['joblocation'];
        $job_name=$_REQUEST['jobname'];
        $skill_description=$_REQUEST['skilldescription'];
        $special_character=array(" ","　","\t","\n","\r");
        $skill_description=str_replace($special_character, '', $skill_description);
        $skill_description=strtolower($skill_description);
        $skills_pair = split("[:|;]", $skill_description);
        $conn=mysql_connect("52.221.163.108","root",'');
        if(!$conn) die("error : mysql connect failed");
        mysql_select_db("resumeDB",$conn);
        $mailname=$_COOKIE['mailname'];
        $timeinfo=time();
        $dateinfo=date("Y-m-d H:i:s",$timeinfo);
        $datetail=time();
        $sql="select uniqinfo from hrinfo where mailname = "."'".$_COOKIE['mailname']."'";
        $query=mysql_query($sql,$conn);
        $row = mysql_fetch_array($query,MYSQL_ASSOC);
        $tail='_'.$row['uniqinfo'].$datetail;
        //$sql="insert into hrrequest (mailname,jobname,joblocation,skilldescription,dateinfo,tailinfo,degree,status) values ('$mailname','$job_name','$job_location','$skill_description','$dateinfo','$tail','$degree',0)";
        //$query=mysql_query($sql,$conn);
        $i=1;
        $condition_sql = "";
        $condition_py = "";
        $special_character=array(" ","　","\t","\n","\r");

	foreach ($skills_pair as $pair) {
                if ($i%2==1) {
                        $k = $pair;
			$k=str_replace($special_character, '', $k);
                } else {
                        $v = $pair;
			$v=str_replace($special_character, '', $v);
			$condition_sql=$condition_sql . $k . '>=' . $v . " and ";
			$condition_py=$condition_py . '"' . $k . '"' . ':' . $v . ',';
		}
		$i++;
	}

	$condition_sql_ = substr($condition_sql,0,strlen($condition_sql)-4); 
	$condition_py_ = substr($condition_py,0,strlen($condition_py)-1); 
	$condition_sql=$condition_sql_;
	$condition_py="'{".$condition_py_."}'";
	$condition_py_sql="\'{".$condition_py_."}\'";
	//print($condition_sql);
	//print($condition_py);

	//call py

        //$conn=mysql_connect("52.221.163.108","root",'');
        //if(!$conn) die("error : mysql connect failed");
        //mysql_select_db("resumeDB",$conn);
        //$sql="insert into tailinfo (mailname,tailname) values ('$mailname','$tail')";
	//$sql="insert into hrrequest (tailinfo) values ('$tail')";
        //$query=mysql_query($sql,$conn);
	//mysql_close($conn);

	$timeinfo=time();

	$pycmd="/usr/local/bin/python3 ../do.py "."-t ".$tail." -r ".$condition_py." -l ".$job_location." -m ".'"'.$mailsuffix.'"'." -d ".'"'.$degree.'"'." > ../tmpfiles/".$tail."";
	$py="/usr/local/bin/python3 ../do.py "."-t ".$tail." -r ".$condition_py_sql." -l ".$job_location." -m ".'"'.$mailsuffix.'"'." -d ".'"'.$degree.'"'." > ../tmpfiles/".$tail."";

        $sql="insert into hrrequest (mailname,jobname,joblocation,skilldescription,dateinfo,tailinfo,degree,status,py,time) values ('$mailname','$job_name','$job_location','$skill_description','$dateinfo','$tail','$degree',0,'$py','$timeinfo')";
        $query=mysql_query($sql,$conn);

	//print($pycmd);
	$result=system($pycmd,$ret);

        echo"<script type='text/javascript'>alert('提交成功，稍后推荐简历！');history.go(-1)</script>";
	$file="/Users/weixiaowenhao/Sites/tmpfiles/".$tail;
	$timeflag = True; 
	while($timeflag) {
	sleep(2);
		if (file_exists($file)) {
		    $timeflag = False;
		    break;
		}
	}
        echo"<script type='text/javascript'>alert('推荐成功！');window.loacation.reload();</script>";
	$sql0="drop table if exists resumeinfoPositive".$tail;	
	$sql1="drop table if exists resumeinfoPositiveMseME".$tail;	
	$sql2="drop table if exists resumeinfoPositiveMseMESortMse".$tail;	
	$sql4="drop table if exists resumeinfoNegative".$tail;	
	$sql5="drop table if exists resumeinfoNegativeMseME".$tail;	
	$sql6="drop table if exists resumeinfoNegativeMseMESortMse".$tail;	
        mysql_query($sql0,$conn);
        mysql_query($sql1,$conn);
        mysql_query($sql2,$conn);
        mysql_query($sql4,$conn);
        mysql_query($sql5,$conn);
        mysql_query($sql6,$conn);
	mysql_close($conn);
} else {
        echo"<script type='text/javascript'>alert('提交出错，请稍后再试！');history.go(-1)</script>";
}

?>
