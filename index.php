<?php
$flag=0;
//var_dump($_GET);
if(isset($_GET["out"])){
    if($_GET["out"]){
        setcookie('mailname','',time()-1);
        $flag=1;//防止服务器接收到getout操作时已经认为该用户有cookie，然后下面的COOKIE[NAME]已经有了，服务器返回给他的才是空的
    }
}
?>
