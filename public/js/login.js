function showRegisterForm() {
    $("#recuperar-form").hide();
    $("#login-form").fadeOut(200, function () {
        $("#register-form").fadeIn(200);
    });
}

function showLoginForm() {
    $("#recuperar-form").hide();
    $("#empresa-form").hide();
    $("#cliente-form").hide();
    $("#register-form").fadeOut(200, function () {
        $("#login-form").fadeIn(200);
    });
}

function showRegisterEmpresa() {
    $("#register-form").fadeOut(200, function () {
        $("#empresa-form").fadeIn(200);
    });
}

function showRegisterCliente() {
    $("#register-form").fadeOut(200, function () {
        $("#cliente-form").fadeIn(200);
    });
}

function showRecuperarPassword() {
    $("#login-form").fadeOut(200, function () {
        $("#recuperar-form").fadeIn(200);
    });
}
