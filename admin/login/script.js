$(function () {
    $('#login').click(function (e) {
        e.preventDefault();
        let user_name = $('#user_name').val();
        let user_password = $('#user_password').val();
        $.ajax({
            type: "post",
            url: "action.php",
            data: {
                fn: "login",
                user_name: user_name,
                user_password: user_password
            },
            dataType: "json",
            success: function (response) {
                if (response.title == 't') {
                    location.reload();
                } else {
                    Swal.fire(res.title, res.message, res.icon);
                }
            }
        });
    });
});