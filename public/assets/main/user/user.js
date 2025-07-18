/*
* Script pour la verification du user
*/

$('#register-user').click(function () {
    var firstname = $('#firstname').val();
    var lastname = $('#lastname').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var password_confirm = $('#password-confirm').val();
    var passwordLength = password.length;
    let agreeTerms = $("agreeterms");

    if (firstname != "" && /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(firstname)) {
        $('#firstname').removeClass('is-invalid');
        $('#firstname').addClass('is-valid');
        $('#error-register-firstname').text("");

        if (lastname != "" && /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(lastname)) {
            $('#lastname').removeClass('is-invalid');
            $('#lastname').addClass('is-valid');
            $('#error-register-lastname').text("");

            if (email != "" && /^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,6}$/.test(email)) {
                $('#email').removeClass('is-invalid');
                $('#email').addClass('is-valid');
                $('#error-register-email').text("");

                if (passwordLength >= 8) {
                    $('#password').removeClass('is-invalid');
                    $('#password').addClass('is-valid');
                    $('#error-register-password').text("");

                    if (password == password_confirm) {
                        $('#password-confirm').removeClass('is-invalid');
                        $('#password-confirm').addClass('is-valid');
                        $('#error-register-password-confirm').text("");

                        if ($("input[id='agreeterms']").is(":checked")) {
                            $('#agreeterms').removeClass('is-invalid');
                            $('#error-register-agreeterms').text("");


                            //envoi du formulaire
                            // alert('data-send');

                            var res = emailExistjs(email);
                            if (res != "exist") {
                                $('#form-register').submit()
                            } else {
                                $('#email').addClass('is-invalid');
                                $('#email').removeClass('is-valid');
                                $('#error-register-email').text("this email address is already used!");
                            }
                        } else {
                            $('#agreeterms').addClass('is-invalid');
                            $('#error-register-agreeterms').text("Your should agree to our terms and conditions");
                        }

                    } else {
                        $('#password-confirm').addClass('is-invalid');
                        $('#password-confirm').removeClass('is-valid');
                        $('#error-register-password-confirm').text("Your passwords most be identical!");
                    }

                } else {
                    $('#password').addClass('is-invalid');
                    $('#password').removeClass('is-valid');
                    $('#error-register-password').text("Your password most be at 8 chraraters!");
                }

            } else {
                $('#email').addClass('is-invalid');
                $('#email').removeClass('is-valid');
                $('#error-register-email').text("Email is not valid");
            }

        } else {
            $('#lastname').addClass('is-invalid');
            $('#lastname').removeClass('is-valid');
            $('#error-register-lastname').text("Last Name is not valid");
        }
    } else {
        $('#firstname').addClass('is-invalid');
        $('#firstname').removeClass('is-valid');
        $('#error-register-firstname').text("First Name is not valid");
    }
});


// checked changer
$('#agreeterms').change(function () {
    var agreeterms = $('#agreeterms');

    if (agreeterms.is(":checked")) {
        $('#agreeterms').removeClass('is-invalid');
        $('#error-register-agreeterms').text("");

    } else {
        $('#agreeterms').addClass('is-invalid');
        $('#error-register-agreeterms').text("Your should agree to our terms and conditions");
    }
});


function emailExistjs(email) {
    var url = $('#email').attr('url-existEmail');
    var token = $('#email').attr('token');
    var responseJs = "";

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            '_token': token,
            email: email,
        },
        success: function (result) {
            responseJs = result.response;
        },
        async: false
    });

    return responseJs;
}