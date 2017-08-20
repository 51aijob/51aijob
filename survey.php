<?php 

//if (!(empty($_COOKIE['file_location']))) {
//	print($_COOKIE['file_location']);
//}
session_start();
$file_location = $_SESSION['file_location'];
//print_r($_COOKIE);
//print_r($_REQUEST);

if(isset($_REQUEST)) 
{
	//var_dump($_REQUEST);
	//print_r($_COOKIE);
	$expect_place=$_REQUEST['btn_select0'];  
	$industry=$_REQUEST['btn_select1'];  
	$degree=$_REQUEST['btn_select2'];  

        if($degree == "Doctor") {$degree=5;}
        if($degree == "Master") {$degree=4;}
        if($degree == "Bachelor") {$degree=3;}
        if($degree == "College") {$degree=2;}
        if($degree == "Other") {$degree=1;}

	$current_salary=$_REQUEST['currentsalary'];  
	$expect_salary=$_REQUEST['expectsalary'];  
	$character_string=$_REQUEST['character'];  
	$skill_description=$_REQUEST['skilldescription'];  
	$shield_company=$_REQUEST['shieldcompany'];
        $special_character=array("\r\n"," ","　","\t+","\n+","\r+","\s+",":",",",";","\r+\n+");
        $shield_company=strtolower(str_replace($special_character, ' ', $shield_company));
  	//print($expect_place);
  	//print($skill_description);
	//$special_character=array(" ","　","\t","\n","\r");
	//$skill_description=str_replace($special_character, '', $skill_description);
	//$skill_description=strtolower($skill_description);
  	//$skills_pair = split("[:|;|,]", $skill_description);
	//print_r($skills_pair);

	$special_character=array("\r\n"," ","　","\t+","\n+","\r+","\s+",":",",",";","\r+\n+");
	$skill_description_orig=str_replace($special_character, ' ', $skill_description);
	$skill_description_orig=strtolower($skill_description_orig);

	$skill_description=str_replace($special_character, '|', $skill_description);
	$skill_description=strtolower($skill_description);
	//print($skill_description_orig);
	//print($skill_description);
  	$skills_pair = split("\|+", $skill_description);
	//print_r($skills_pair);

	$count = count($skills_pair);

	//connect database, get columns info at this time 
	$conn=mysql_connect("localhost","root",'');
	if(!$conn) die("error : mysql connect failed");
	mysql_select_db("resumeDB",$conn);
	$sql = "select column_name from information_schema.columns where table_name = 't1'";
	$query=mysql_query($sql,$conn);
	$currentFields=array();
	while ($currentField=mysql_fetch_array($query,MYSQL_ASSOC)) {
		$currentFields[] = $currentField;
	}

	//print_r($currentFields);
	mysql_close($conn);

	$i = 1;
	foreach ($skills_pair as $pair) {
		if ($i%2==1) {
			$k = $pair;
		} else {
			$v = $pair;
			//print($k);
			//print($v);
			if (!(in_array($k,$currentFields))) {
				//add new column
				$k = preg_replace("/[\s]+/", '_', $k);
				$sql = "alter table resumeinfo add column $k varchar(100) default 0";
				$conn=mysql_connect("localhost","root",'');
				if(!$conn) die("error : mysql connect failed");
				mysql_select_db("resumeDB",$conn);
				$result=mysql_query($sql,$conn);
				
				//update value for new column
				$sql="update resumeinfo set $k=$v where file_location = '$file_location'";
				$result1=mysql_query($sql,$conn);
				mysql_close($conn);
			} else {
				//update value for old column
				$k = preg_replace("/[\s]+/", '_', $k);
				$sql="update resumeinfo set $k=$v where file_location = '$file_location'";
				$result1=mysql_query($sql,$conn);
				mysql_close($conn);
			}
		}
		$i++;
	}

	//connect database, and save info into db
	$conn=mysql_connect("localhost","root",'');
	if(!$conn) die("error : mysql connect failed");
	mysql_select_db("resumeDB",$conn);
	$sql="update resumeinfo set expect_place='$expect_place',current_salary='$current_salary',expect_salary='$expect_salary',industry='$industry',character_string='$character_string',skill_description='$skill_description_orig',degree='$degree',shield_company='$shield_company' where file_location = '$file_location'";
	$result=mysql_query($sql,$conn);
	//print($sql);
	mysql_close($conn);
	echo"<script type='text/javascript'>alert('提交完成，请静候佳音！')</script>";  
	echo '<script>location.href="'.'../index/HTML/index.html'.'"</script>';

}
else 
{
	echo"<script type='text/javascript'>alert('提交出错，请稍后再试！');history.go(-1)</script>";  
}
?>  
