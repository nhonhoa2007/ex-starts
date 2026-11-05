<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Chức năng Live Search bằng PHP và AJAX</title>
<style type="text/css">
body{
    font-family: Arial, sans-serif;
    margin: 50px;
}
h2{
    color: #333;
    margin-bottom: 20px;
}
.search-box{
    width: 350px;
    position: relative;
    display: inline-block;
    font-size: 14px;
}
.search-box input[type="text"]{
    height: 36px;
    padding: 5px 10px;
    border: 1px solid #CCCCCC;
    font-size: 14px;
    border-radius: 4px;
}
.result{
    position: absolute;
    z-index: 999;
    top: 100%;
    left: 0;
    background-color: #ffffff;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.search-box input[type="text"], .result{
    width: 100%;
    box-sizing: border-box;
}
.result p{
    margin: 0;
    padding: 8px 10px;
    border: 1px solid #CCCCCC;
    border-top: none;
    cursor: pointer;
}
.result p:hover{
    background: #f2f2f2;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    function doSearch(val){
        var resultDropdown = $(".search-box .result");
        if(val.length){
            $.get("backend-search.php", {term: val}).done(function(data){
                resultDropdown.html(data);
            });
        } else {
            resultDropdown.empty();
        }
    }

    $('.search-box input[type="text"]').on("keyup input", function(){
        var inputVal = $(this).val();
        doSearch(inputVal);
    });

    $(document).on("click", ".result p", function(){
        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        $(this).parent(".result").empty();
    });

    var urlParams = new URLSearchParams(window.location.search);
    var term = urlParams.get('term');
    if(term){
        $('.search-box input[type="text"]').val(term);
        doSearch(term);
    }
});
</script>
</head>
<body>
<h2>Tìm kiếm tên học phần (Live Search)</h2>
<div class="search-box">
    <input type="text" autocomplete="off" placeholder="Search tên học phần..." />
    <div class="result"></div>
</div>
</body>
</html>
