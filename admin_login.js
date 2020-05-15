$(document).ready(function() {
    // send username and password to server
    $("#login").submit(function(e){
        e.preventDefault();
        $.post($("#login").attr("action"), 
            {username: $("input").eq(0).val(),
            pwd: $("input").eq(1).val()}, 
            function(res){
                if(res == "success"){
                    window.location.href = "admin_index.php";
                }
                else{
                    // prompt error message
                    $("#errorMsg").html(res);
                    $("#errorMsg").css("margin-bottom", "1em");
                }
        });
    });
        
    // remove error message
    $("input").on("keypress", function(){
        $("#errorMsg").html("");
        $("#errorMsg").css("margin-bottom", "");
    });
});
    