
<!DOCTYPE html>  
<html lang="en">  
<head>  
    <link rel="stylesheet" href="style.css" /> 
    <meta charset="UTF-8">  
    <title>HR招聘发布系统</title>  
    <style type="text/css">  
        @import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300);  
        * {  
          box-sizing: border-box;  
          margin: 0;  
          padding: 0;  
          font-weight: 300;  
        }  
        body {  
          font-family: 'Source Sans Pro', sans-serif;  
          color: white;  
          font-weight: 300;  
        }  
        body ::-webkit-input-placeholder {  
          /* WebKit browsers */  
          font-family: 'Source Sans Pro', sans-serif;  
          color: white;  
          font-weight: 300;  
        }  
        body :-moz-placeholder {  
          /* Mozilla Firefox 4 to 18 */  
          font-family: 'Source Sans Pro', sans-serif;  
          color: white;  
          opacity: 1;  
          font-weight: 300;  
        }  
        body ::-moz-placeholder {  
          /* Mozilla Firefox 19+ */  
          font-family: 'Source Sans Pro', sans-serif;  
          color: white;  
          opacity: 1;  
          font-weight: 300;  
        }  
        body :-ms-input-placeholder {  
          /* Internet Explorer 10+ */  
          font-family: 'Source Sans Pro', sans-serif;  
          color: white;  
          font-weight: 300;  
        }  
        .wrapper {  
          background: #3a2;  
          background: -webkit-linear-gradient(top left, #50a3a2 0%, #53e3a6 100%);  
          background: linear-gradient(to bottom right, #50a3a2 0%, #53e3a6 100%);  
          position: absolute;  
          left: 0;  
          width: 100%;  
	  min-height:100%;
        /*  margin-top: -200px;*/  
          overflow: auto;  
        }  
        .wrapper.form-success .container h1 {  
          -webkit-transform: translateY(85px);  
                  transform: translateY(85px);  
        }  
        .container {  
          max-width: 800px;  
          margin: 0 auto;  
          padding: 60px 0;  
          padding-top:60px;  
          text-align: center;  
          overflow: auto;  
          position: relative;  
	  z-index:3;
        }  
        .container h1 {  
          font-size: 40px;  
          -webkit-transition-duration: 1s;  
                  transition-duration: 1s;  
          -webkit-transition-timing-function: ease-in-put;  
                  transition-timing-function: ease-in-put;  
          font-weight: 200;  
        }  
        form {  
          padding: 20px 0;  
          position: relative;  
	  z-index:2;
        }  
        form input {  
          -webkit-appearance: none;  
             -moz-appearance: none;  
                  appearance: none;  
          outline: 0;  
          border: 1px solid rgba(255, 255, 255, 0.4);  
          background-color: rgba(255, 255, 255, 0.2);  
          width: 250px;  
          border-radius: 3px;  
          padding: 10px 15px;  
          margin: 0 auto 10px auto;  
          display: block;  
          text-align: center;  
          font-size: 18px;  
          color: white;  
          -webkit-transition-duration: 0.25s;  
                  transition-duration: 0.25s;  
          font-weight: 300;  
        }  
        form input:hover {  
          background-color: rgba(255, 255, 255, 0.4);  
        }  
        form input:focus {  
          background-color: white;  
          width: 300px;  
          color: #53e3a6;  
        }  
        form button {  
          -webkit-appearance: none;  
             -moz-appearance: none;  
                  appearance: none;  
          outline: 0;  
          background-color: white;  
          border: 0;  
          padding: 10px 15px;  
          color: #53e3a6;  
          border-radius: 3px;  
          width: 125px;  
          cursor: pointer;  
          font-size: 18px;  
          -webkit-transition-duration: 0.25s;  
                  transition-duration: 0.25s;  
        }  
        form button:hover {  
          background-color: #f5f7f9;  
        }  
        .bg-bubbles {  
          position: absolute;  
          top: 0;  
          left: 0;  
          width: 100%;  
          height: 100%;  
          z-index: 1;  
        }  
        .bg-bubbles li {  
          position: absolute;  
          list-style: none;  
          display: block;  
          width: 40px;  
          height: 40px;  
          background-color: rgba(255, 255, 255, 0.15);  
          bottom: -160px;  
          -webkit-animation: square 25s infinite;  
          animation: square 25s infinite;  
          -webkit-transition-timing-function: linear;  
          transition-timing-function: linear;  
        }  
        .bg-bubbles li:nth-child(1) {  
          left: 10%;  
        }  
        .bg-bubbles li:nth-child(2) {  
          left: 20%;  
          width: 80px;  
          height: 80px;  
          -webkit-animation-delay: 2s;  
                  animation-delay: 2s;  
          -webkit-animation-duration: 17s;  
                  animation-duration: 17s;  
        }  
        .bg-bubbles li:nth-child(3) {  
          left: 25%;  
          -webkit-animation-delay: 4s;  
                  animation-delay: 4s;  
        }  
        .bg-bubbles li:nth-child(4) {  
          left: 40%;  
          width: 60px;  
          height: 60px;  
          -webkit-animation-duration: 22s;  
                  animation-duration: 22s;  
          background-color: rgba(255, 255, 255, 0.25);  
        }  
        .bg-bubbles li:nth-child(5) {  
          left: 70%;  
        }  
        .bg-bubbles li:nth-child(6) {  
          left: 80%;  
          width: 120px;  
          height: 120px;  
          -webkit-animation-delay: 3s;  
                  animation-delay: 3s;  
          background-color: rgba(255, 255, 255, 0.2);  
        }  
        .bg-bubbles li:nth-child(7) {  
          left: 32%;  
          width: 160px;  
          height: 160px;  
          -webkit-animation-delay: 7s;  
                  animation-delay: 7s;  
        }  
        .bg-bubbles li:nth-child(8) {  
          left: 55%;  
          width: 20px;  
          height: 20px;  
          -webkit-animation-delay: 15s;  
                  animation-delay: 15s;  
          -webkit-animation-duration: 40s;  
                  animation-duration: 40s;  
        }  
        .bg-bubbles li:nth-child(9) {  
          left: 25%;  
          width: 10px;  
          height: 10px;  
          -webkit-animation-delay: 2s;  
                  animation-delay: 2s;  
          -webkit-animation-duration: 40s;  
                  animation-duration: 40s;  
          background-color: rgba(255, 255, 255, 0.3);  
        }  
        .bg-bubbles li:nth-child(10) {  
          left: 90%;  
          width: 160px;  
          height: 160px;  
          -webkit-animation-delay: 11s;  
                  animation-delay: 11s;  
        }  
        @-webkit-keyframes square {  
          0% {  
            -webkit-transform: translateY(0);  
                    transform: translateY(0);  
          }  
          100% {  
            -webkit-transform: translateY(-700px) rotate(600deg);  
                    transform: translateY(-700px) rotate(600deg);  
          }  
        }  
        @keyframes square {  
          0% {  
            -webkit-transform: translateY(0);  
                    transform: translateY(0);  
          }  
          100% {  
            -webkit-transform: translateY(-700px) rotate(600deg);  
                    transform: translateY(-700px) rotate(600deg);  
          }  
        }  
        .cc{  
            text-decoration: none;  
            color: #53e3a6;  
        }  
        .logo-img {
          display: inline-block;
          width: 76px;
          height: auto;
          max-width: 100%;
          max-height: 100%;
          vertical-align: middle;
        }
        .logo-wrap {
          display: inline-block;
          padding: 15px 0 0 15px;
          position: relative;  
	  z-index:3;
        }
    </style>  
    <script type="text/javascript">  
          
         $("#login-button").click(function(event){  
                 event.preventDefault();  
               
             $('form').fadeOut(500);  
             $('.wrapper').addClass('form-success');  
        });  
    </script>  

<script type="text/javascript">
function verify(){
//获取form标签元素
        var form=document.getElementById('form');
        //获取form下元素下所有input标签
        var inputArray=form.getElementsByTagName("input");
        var inputArrayLength=inputArray.length;
        //循环input元素数组
        for(var int=0;int<inputArrayLength;int++){
                //判断每个input元素的值是否为空
                if( inputArray[int].value==null || inputArray[int].value==''){
                        alert('第'+(int+1)+'个输入值为空.');
                        return false;
                }
        }
        var textAreaValue = document.getElementById("skilldescription").value
        if( textAreaValue==null || textAreaValue==''){
                alert('请输入所需技能及打分，以便系统推荐合适候选人!');
                return false;
        }
//如果所有Input标签的值都不为空的话
return true;
}
</script>


</head>  
<body>  

    <div class="wrapper">  
	 <div class="logo-wrap">
            <a href="../index/HTML/index.html"> <img class="logo-img" src="../index/HTML/img/logo.png"></a>
        </div>
	
        <div class="container">  
            <h1>欢迎访问51AIjob</h1>  

		<link rel="stylesheet" href="table.css" /> 
		<fieldset id=candidateListPN>
		<legend>优秀简历集</legend>
		</br>
		<center><table width=500 id = 'hrrequestrecordp'>
		<!--<caption>优秀简历推荐</caption>-->
		<tr><th>编号</th><th>优秀简历</th></tr>

		<script type="text/javascript" src="jquery.media.js"></script>
		<script type="text/javascript">
		    $(function() {
			$('a.media').media({width:800, height:600});
		    });
		</script>

		<?php

		$tail=$_GET['tail'];
		$tableP="resumeinfoPositiveMseMESortMseMe".$tail;
		$tableN="resumeinfoNegativeMseMESortMseMe".$tail;

		$conn=mysql_connect("52.221.163.108","root",'');
		if(!$conn) die("error : mysql connect failed");
		mysql_select_db("resumeDB",$conn);
		$sql="select file_location from ".$tableP;
		$query=mysql_query($sql,$conn);
		$id=1;
		while ($resumeinfo=mysql_fetch_assoc($query)) {
			echo"<tr><td>".$id."</td><td><a class=media href=../upload/".$resumeinfo['file_location'].">优秀简历</a></td></tr>";
			$id=$id+1;
		}
		?>

		</table>
		<span id="spanFirst">第一页</span> <span id="spanPre">上一页</span> <span id="spanNext">下一页</span> <span id="spanLast">最后一页</span> 第<span id="spanPageNum"></span>页/共<span id="spanTotalPage"></span>页

		</fieldset>

		<fieldset id=candidateListPN>
		<legend>合格简历集</legend>
		</br>
		<center><table width=500 id = 'hrrequestrecordn'>
		<tr><th>编号</th><th>合适简历</th></tr>

		<?php
		$sql="select file_location from ".$tableN;
		$query=mysql_query($sql,$conn);
		$id=1;
		while ($resumeinfo=mysql_fetch_assoc($query)) {
			echo"<tr><td>".$id."</td><td><a class=media href=../upload/".$resumeinfo['file_location'].">合适简历</a></td></tr>";
			$id=$id+1;
		}
		?>
		</table>
<span id="spanFirst">第一页</span> <span id="spanPre">上一页</span> <span id="spanNext">下一页</span> <span id="spanLast">最后一页</span> 第<span id="spanPageNum"></span>页/共<span id="spanTotalPage"></span>页

<script>
     var theTable = document.getElementById("hrrequestrecordn");
     var totalPage = document.getElementById("spanTotalPage");
     var pageNum = document.getElementById("spanPageNum");


     var spanPre = document.getElementById("spanPre");
     var spanNext = document.getElementById("spanNext");
     var spanFirst = document.getElementById("spanFirst");
     var spanLast = document.getElementById("spanLast");


     var numberRowsInTable = theTable.rows.length;
     var pageSize = 3;
     var page = 1;


     //下一页
     function next() {


         hideTable();


         currentRow = pageSize * page;
         maxRow = currentRow + pageSize;
         if (maxRow > numberRowsInTable) maxRow = numberRowsInTable;
         for (var i = currentRow; i < maxRow; i++) {
             theTable.rows[i].style.display = '';
         }
         page++;


         if (maxRow == numberRowsInTable) { nextText(); lastText(); }
         showPage();
         preLink();
         firstLink();
     }


     //上一页
     function pre() {


         hideTable();


         page--;


         currentRow = pageSize * page;
         maxRow = currentRow - pageSize;
         if (currentRow > numberRowsInTable) currentRow = numberRowsInTable;
         for (var i = maxRow; i < currentRow; i++) {
             theTable.rows[i].style.display = '';
         }




         if (maxRow == 0) { preText(); firstText(); }
         showPage();
         nextLink();
         lastLink();
     }


     //第一页
     function first() {
         hideTable();
         page = 1;
         for (var i = 0; i < pageSize; i++) {
             theTable.rows[i].style.display = '';
         }
         showPage();


         preText();
         nextLink();
         lastLink();
     }


     //最后一页
     function last() {
         hideTable();
         page = pageCount();
         currentRow = pageSize * (page - 1);
         for (var i = currentRow; i < numberRowsInTable; i++) {
             theTable.rows[i].style.display = '';
         }
         showPage();


         preLink();
         nextText();
         firstLink();
     }


     function hideTable() {
         for (var i = 0; i < numberRowsInTable; i++) {
             theTable.rows[i].style.display = 'none';
         }
     }


     function showPage() {
         pageNum.innerHTML = page;
     }


     //总共页数
     function pageCount() {
         var count = 0;
         if (numberRowsInTable % pageSize != 0) count = 1;
         return parseInt(numberRowsInTable / pageSize) + count;
     }


     //显示链接
     function preLink() { spanPre.innerHTML = "<a href='javascript:pre();'>上一页</a>"; }
     function preText() { spanPre.innerHTML = "上一页"; }


     function nextLink() { spanNext.innerHTML = "<a href='javascript:next();'>下一页</a>"; }
     function nextText() { spanNext.innerHTML = "下一页"; }


     function firstLink() { spanFirst.innerHTML = "<a href='javascript:first();'>第一页</a>"; }
     function firstText() { spanFirst.innerHTML = "第一页"; }


     function lastLink() { spanLast.innerHTML = "<a href='javascript:last();'>最后一页</a>"; }
     function lastText() { spanLast.innerHTML = "最后一页"; }


     //隐藏表格
     function hide() {
         for (var i = pageSize; i < numberRowsInTable; i++) {
             theTable.rows[i].style.display = 'none';
         }


         totalPage.innerHTML = pageCount();
         pageNum.innerHTML = '1';


         nextLink();
         lastLink();
     }


     hide();
</script>

		</fieldset>
        </div>  
          
        <ul class="bg-bubbles">  
            <li></li>  
            <li></li>  
            <li></li>  
            <li></li>  
            <li></li>  
            <li></li>  
            <li></li>  
            <li></li>  
            <li></li>  
            <li></li>  
        </ul>  
    </div>  
</body>  
</html>  
