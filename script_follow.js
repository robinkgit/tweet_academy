$(document).ready(function() {
    $('#follow-button').click(function() {
        let button = document.getElementById("follow-button");
        let userId = button.getAttribute('data-userConnected'); 
        let followId = button.getAttribute('data-user'); 
        $.post('./php1/addFollower.php', {
            userId: followId,
            followId: userId
        }, function(data) {
            alert(data);
        });
    });
});