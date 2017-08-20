
dy>
<?php

$conn = mysql_connect('localhost','root','');
if(!$conn) die("error : mysql connect failed");
mysql_select_db("resumeDB",$conn);

if(isset($_GET['phone']) && isset($_GET['email']))
{
        $phone=$_GET['phone'];
        $email=$_GET['email'];
        $query=mysql_query("select * from resumeinfo where email='$email' AND phone='$phone' ");
        $row=mysql_num_rows($query);
        if($row == 1)
        {
                $query1=mysql_query("update resumeinfo set status='1' where email='$email' AND phone='$phone'");
                if($query1)
                {
                        echo "<script>alert('Done!');window.location= '../index/HTML/index.html';</script>";
                }
        }
}
mysql_close($conn);
?>
</body>
</html>
