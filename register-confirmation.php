<html>
<body>
<?php

$conn = mysql_connect('52.221.163.108','root','');
if(!$conn) die("error : mysql connect failed");
mysql_select_db("resumeDB",$conn);

if(isset($_GET['confirmation_code']) && isset($_GET['mailname']))
{
	$code=$_GET['confirmation_code'];
	$mailname=$_GET['mailname'];
	$query=mysql_query("select * from hrinfo where mailname='$mailname' AND confirmid='$code' ");
	$row=mysql_num_rows($query);
	if($row == 1)
	{
		$query1=mysql_query("update hrinfo set verified='1' where mailname='$mailname' AND confirmid='$code'");
		if($query1)
		{
			echo "<script>alert('注册资料审核中，审核通过后将邮件告知您！');window.location= '../index/HTML/index.html';</script>";
		}
	}
}
mysql_close($conn);
?>
</body>
</html>
