$(document).ready(function() {
    $('#adminLoginForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var errorDiv = $('#adminLoginError');
        errorDiv.hide();
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect;
                } else {
                    errorDiv.text(response.message).show();
                }
            }
        });
    });
});
