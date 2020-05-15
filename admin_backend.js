$(document).ready(function() {
    // delete a user
    $(".deleteUser").on("click", function(){
        $user_id = $(this).data("id");
        $username = $(this).data("username");
        
        $.ajax({
            url: "admin_delete_user.php",
            type: "DELETE",
            contentType: "json",
            data: {user_id: $user_id,
                   username: $username}
        })
        .done(function(res){
            $.ajax({
                url: "admin_delete_goal.php",
                type: "DELETE",
                contentType: "json",
                data: {username: $username}
            })
            .done(function(res){
                $.ajax({
                    url: "admin_delete_activity.php",
                    type: "DELETE",
                    contentType: "json",
                    data: {username: $username}
                })
                .done(function(res){
                    // reload the page
                    location.reload();
            
                })
            })
        });
    });
    
    // delete a goal
    $(".deleteGoal").on("click", function(){
        $.ajax({
            url: "admin_delete_goal.php",
            type: "DELETE",
            contentType: "json",
            data: {goal_id: $(this).data("id"),
                   username: $(this).data("username")}
        })
        .done(function(res){
            // reload the page
            location.reload();
        });
    });
    
    // delete a activity
    $(".deleteActivity").on("click", function(){
        $.ajax({
            url: "admin_delete_activity.php",
            type: "DELETE",
            contentType: "json",
            data: {activity_id: $(this).data("id"),
                   username: $(this).data("username")}
        })
        .done(function(res){
            // reload the page
            location.reload();
        });
    });
    
    // dismiss report
    $(".dismiss").on("click", function(){
        $.ajax({
            url: "dismiss_report.php",
            type: "PUT",
            contentType: "json",
            data: {report_id: $(this).data("id")}
        })
        .done(function(res){
            // reload the page
            location.reload();
        });
    });
    
    // resolve report
    $(".resolve").on("click", function(){
        $.ajax({
            url: "resolve_report.php",
            type: "PUT",
            contentType: "json",
            data: {report_id: $(this).data("id")}
        })
        .done(function(res){
            // reload the page
            location.reload();
        });
    });
});
    