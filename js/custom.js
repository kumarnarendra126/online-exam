
$(document).ready(function() {
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    window.location.href = 'account.php?q=1';
                } else {
                    $('#loginForm').before('<div class="alert alert-danger" role="alert">' + data.message + '</div>');
                }
            }
        });
    });
});
