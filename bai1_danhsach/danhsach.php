<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Ajax-Danh sách lớp</title>
<link href="style/style.css" type="text/css" rel="stylesheet"></link>
<style>
body{font-size:11pt; margin: 25px; font-family: Arial, sans-serif;}
h3{color: #333; margin-bottom: 15px;}
div{padding: 3px 5px;font:normal 13pt Arial;}
table{background-color:#eaeaea; width: 600px; border-collapse: collapse; margin-top: 10px;}
th, td{font:normal 11pt Arial; padding: 6px 12px; border: 1px solid #bbb;}
.hd{background-color:navy; color:white; font-weight: bold;}
select{padding: 4px 8px; font-size: 11pt;}
</style>
<script>
function sendajax(){
    var lop = document.getElementById("lop").value;
    var objds = document.getElementById("ds");
    if(!lop){
        objds.innerHTML = "Danh sách";
        return;
    }
    var xml = new XMLHttpRequest();
    xml.onreadystatechange = function(){
        if(xml.readyState == 4){
            objds.innerHTML = xml.responseText;
        }
    };
    var url = "ds.php?lop=" + encodeURIComponent(lop);
    xml.open("GET", url, true);
    xml.send();
}

window.addEventListener('DOMContentLoaded', function(){
    var urlParams = new URLSearchParams(window.location.search);
    var lopParam = urlParams.get('lop');
    if(lopParam){
        var sel = document.getElementById("lop");
        sel.value = lopParam;
        sendajax();
    }
});
</script>
</head>
<body>
<h3>In danh sách theo từng lớp</h3>
<?php
include("inc/connect.inc");

function initClass($conn){
    $sql = "Select distinct lop from sinhvien";
    $rs = mysqli_query($conn, $sql);
    $str = "Chọn lớp: <select id='lop' onChange='sendajax();'>";
    $str .= "<option value=''>-- Chọn lớp --</option>";
    while($row = mysqli_fetch_array($rs)){
        $str .= "<option value='{$row['lop']}'>{$row['lop']}</option>";
    }
    $str .= "</select>";
    echo $str;
}

initClass($con);
?>
<hr>
<div id="ds">Danh sách</div>
</body>
</html>
