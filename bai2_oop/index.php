<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OOP with PHP</title>
<link href="style.css" rel="stylesheet">
<script>
function ajax(){
    var obj = document.getElementById("info");
    var value = document.getElementById("chon").value;
    if(value === ""){
        obj.style.display = "none";
        return;
    }
    obj.style.display = "block";
    var xml = new XMLHttpRequest();
    xml.onreadystatechange = function(){
        if(xml.readyState == 4){
            obj.innerHTML = xml.responseText;
        }
    };
    var url = "showTable.php?chon=" + encodeURIComponent(value);
    xml.open("GET", url, true);
    xml.send();
}

window.addEventListener('DOMContentLoaded', function(){
    var urlParams = new URLSearchParams(window.location.search);
    var chonParam = urlParams.get('chon');
    if(chonParam){
        var sel = document.getElementById("chon");
        sel.value = chonParam;
        ajax();
    }
});
</script>
</head>
<body>
<h1>OOP with PHP</h1>
<select id="chon" onChange="ajax();">
    <option value="">-->Chọn<--</option>
    <option value="giaovien">Giáo viên</option>
    <option value="sinhvien">Sinh viên</option>
    <option value="hocphan">Học phần</option>
</select>
<hr>
<div id="info" style="display:none;"></div>
</body>
</html>
