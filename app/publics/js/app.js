// Datepicker JqueryUI
$(function () {
    $("#signupDatepicker").datepicker();
    $("#anim").on("change", function () {
        $("#signupDatepicker").datepicker("option", "showAnim", $(this).val());
    });
});

var formLogin = document.getElementById("form-login");
var submitLogin = document.getElementById("loginButton");
var emailLogin = document.getElementById("email");
var passwordLogin = document.getElementById("password");
var message = "";
if (submitLogin !== null) {
    submitLogin.addEventListener('click', (e) => {
        e.preventDefault();
        validateSignIn();
    })
}

function checkEmail(emailValue) {
    var regularExpression = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return emailValue.match(regularExpression);
}

function checkPassword(passwordValue) {
    var regularExpression = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
    return passwordValue.match(regularExpression);
}

function validateSignIn() {
    var countError = 0;
    const emailValue = emailLogin.value;
    const passwordValue = passwordLogin.value;
    if (emailValue == "") {
        message = "Vui lòng nhập email!";
        setErrorForEmail(message);
        countError++;
    } else if (!checkEmail(emailValue)) {
        message = "Email không hợp lệ, vui lòng kiểm tra lại!";
        setErrorForEmail(message);
        countError++;
    } else {
        setSuccessForEmail();
    }
    if (passwordValue == "" || !checkPassword(passwordValue)) {
        message = "Mật khẩu không đúng định dạng!";
        setErrorForPassword(message);
        countError++;
    } else {
        setSuccessForPassword();
    }
    if (countError == 0) {
        formLogin.submit();
    }
}

function setErrorForEmail(message) {
    var emailError = document.getElementById("email-error");
    emailError.style.display = "block";
    emailError.innerHTML = message;
}

function setSuccessForEmail() {
    var emailError = document.getElementById("email-error");
    emailError.style.display = "none";
}

function setErrorForPassword(message) {
    var passwordError = document.getElementById("password-error");
    passwordError.style.display = "block";
    passwordError.innerHTML = message;
}

function setSuccessForPassword() {
    var passwordError = document.getElementById("password-error");
    passwordError.style.display = "none";
}

var formSignup = document.getElementById("form-register");
var emailSignup = document.getElementById("signupEmail");
var passwordSignup = document.getElementById("signupPassword");
var repasswordSignup = document.getElementById("signupRePassword");
var lastnameSignup = document.getElementById("signupLastname");
var firstnameSignup = document.getElementById("signupFirstname");
var datepickerSignup = document.getElementById("signupDatepicker");
var phoneSignup = document.getElementById("signupPhone");
var faqSignup = document.getElementById("signupCheckFaq");

var signupBtn = document.getElementById("signupBtn");
if (signupBtn !== null) {
    signupBtn.addEventListener('click', (e) => {
        e.preventDefault();
        validateSignup();
    })
}

function removeAscent(str) {
    if (str === null || str === undefined) return str;
    str = str.replace(/\s+/g, '');
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    return str;
}

function checkName(name) {
    var re = /^[a-zA-Z!@#\$%\^\&*\)\(+=._-]{2,}$/g;
    return re.test(removeAscent(name));
}

function checkPhone(phoneNumber) {
    var regularExpression = /((09|03|07|08|05)+([0-9]{8})\b)/g;
    return phoneNumber.match(regularExpression);
}

function isLeapYear(year) {
    return ((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0);
}

function validateDate(date) {
    var tempDate = date.split('/');
    if (tempDate.length !== 3) {
        return false;
    } else {
        const year = tempDate[2];
        const month = tempDate[0];
        const day = tempDate[1];
        if (year <= 1910 || year > 2015) {
            return false;
        } else {
            if (month < 1 || month > 12) {
                return false;
            } else {
                if (day < 1 || day > 31) {
                    return false;
                } else {
                    if (isLeapYear(year)) {
                        if (month == 2) {
                            if (day > 29) {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return true;
                        }
                    } else {
                        if (month == 2) {
                            if (day > 28) {
                                return false;
                            } else {
                                return true;
                            }
                        } else {
                            return true;
                        }
                    }
                }
            }
        }
    }
}

function validateSignup() {
    var errorCount = 0;
    const email = emailSignup.value;
    const password = passwordSignup.value;
    const rePassword = repasswordSignup.value;
    const lastname = lastnameSignup.value;
    const firstname = firstnameSignup.value;
    const birthday = datepickerSignup.value;
    const phone = phoneSignup.value;
    const faq = faqSignup.checked;
    var errorEmail = document.getElementById("error-email");
    var errorPassword = document.getElementById("error-password");
    var errorRePassword = document.getElementById("error-repassword");
    var errorLastname = document.getElementById("error-lastname");
    var errorFirstname = document.getElementById("error-firstname");
    var errorDatepicker = document.getElementById("error-datepicker");
    var errorPhone = document.getElementById("error-phone");
    var errorCheckFaq = document.getElementById("error-checkfaq");
    if (email == "" || !checkEmail(email)) {
        errorCount++;
        message = "Email không hợp lệ!";
        setError(errorEmail, message);
        emailSignup.style.border = "solid red 1px";
    }
    if (password == "" || !checkPassword(password)) {
        errorCount++;
        message = "Password phải từ 6-20 kí tự bao gồm ít nhất một chữ hoa, 1 chữ thường và 1 số.";
        setError(errorPassword, message);
        passwordSignup.style.border = "solid red 1px";
    }
    if (rePassword == "" || rePassword !== password) {
        errorCount++;
        message = "Password không khớp!";
        setError(errorRePassword, message);
        repasswordSignup.style.border = "solid red 1px";
    }
    if (!checkName(lastname)) {
        errorCount++;
        message = "Vui lòng nhập họ và tên đệm hợp lệ!";
        setError(errorLastname, message);
        lastnameSignup.style.border = "solid red 1px";
    }
    if (!checkName(firstname)) {
        errorCount++;
        message = "Vui lòng nhập tên hợp lệ!";
        setError(errorFirstname, message);
        firstnameSignup.style.border = "solid red 1px";
    }
    if (!validateDate(birthday)) {
        errorCount++;
        message = "Vui lòng chọn ngày hợp lệ!";
        setError(errorDatepicker, message);
        datepickerSignup.style.border = "solid red 1px";
    }
    if (!checkPhone(phone)) {
        errorCount++;
        message = "Vui lòng nhập số điện thoại hợp lệ!";
        setError(errorPhone, message);
        phoneSignup.style.border = "solid red 1px";
    }
    if (faq !== true) {
        errorCount++;
        message = "Vui lòng đồng ý với điều khoản trước khi đăng ký!";
        setError(errorCheckFaq, message);
    }
    if (errorCount == 0) {
        formSignup.submit();
    }
}

function setError(holder, message) {
    holder.style.display = "block";
    holder.innerHTML = message;
}

$(document).ready(function () {
    var url = window.location.pathname;
    if (url == "/") {
        $("#navbar ul li").first().addClass('active');
        return;
    }
    urlRegExp = new RegExp(url.replace(/\/$/, '') + "$");
    $("#navbar li a").each(function () {
        if (urlRegExp.test(this.href.replace(/\/$/, ''))) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    })
})

$("#signupEmail").keyup(function () {
    var inputEmail = $(this).val().trim();
    $.post("./ajax/checkEmail", {email: inputEmail}, function (data) {
        if (data == "true") {
            $("#error-email").html("Email này đã được sử dụng, vui lòng thử email khác!");
        } else {
            $("#error-email").html("");
        }
    })
})

$("#showPasswordLogin").click(function () {
    if ($("input#password").attr("type") == "password") {
        $("input#password").attr("type", "text");
    } else {
        $("input#password").attr("type", "password");
    }
})

$("#showPasswordSignup").click(function () {
    if ($("input#signupPassword").attr("type") == "password") {
        $("input#signupPassword").attr("type", "text");
    } else {
        $("input#signupPassword").attr("type", "password");
    }
})

$("#showRePasswordSignup").click(function () {
    if ($("input#signupRePassword").attr("type") == "password") {
        $("input#signupRePassword").attr("type", "text");
    } else {
        $("input#signupRePassword").attr("type", "password");
    }
})

$("#formCode").submit(function (e) {
    if ($("#code-box").val() == "") {
        $(".code-error").show();
        $(".code-error").html("Vui lòng nhập code!");
        e.preventDefault();
    } else {
        $("#formCode").submit();
    }
})

$("#expand").click(function () {
    $("#menu").toggleClass("menuActive", function () {
        if ($(document).width() > 1024) {
            $("#wrapMenu").toggleClass("wrapActive");
        }
    });
})


var isUserExtend = false;
$("#btnAccount").click(function () {
    if (!isUserExtend) {
        $(".user-extend").css("display", "block");
        $("#userControl img").css("box-shadow", "0 0 6px 3px #6AA9FF");
        isUserExtend = true;
    } else {
        $(".user-extend").css("display", "none");
        $("#userControl img").css("box-shadow", "unset");
        isUserExtend = false;
    }
})

function isValidCreateDate(dateString) {
    if (!/^\d{4}\/\d{1,2}\/\d{1,2}$/.test(dateString))
        return false;
    var parts = dateString.split("/");
    var day = parseInt(parts[2], 10);
    var month = parseInt(parts[1], 10);
    var year = parseInt(parts[0], 10);
    if (year < 1500 || year > 2050 || month == 0 || month > 12)
        return false;
    var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
        monthLength[1] = 29;
    return day > 0 && day <= monthLength[month - 1];
}

$('#continue').click(function (e) {
    var isValid = true; // default is valid
    //Validate here
    if ($('#quiz-name').val().length < 1 || $('#quiz-name').val().length > 255) {
        isValid = false;
        showError($('#name-error'), 'Tên đề không hợp lệ!');
    }
    if ($("#quiz-enable-timeopen").prop('checked') == true) {
        var datetime = $("#quiz-time-open").datetimepicker('validate')[0].value;
        if (datetime.split(' ').length !== 2) {
            isValid = false;
            showError($('#time-open-error'), 'Thời gian mở đề không đúng định dạng!');
        } else {
            var date = datetime.split(' ')[0];
            var time = datetime.split(' ')[1];
            console.log(date);
            if (isValidCreateDate(date) === false) {
                isValid = false;
                showError($('#time-open-error'), 'Thời gian mở đề không hợp lệ!');
            }
        }
    }
    if ($("#quiz-enable-timeclose").prop('checked') == true) {
        var datetime = $("#quiz-time-close").datetimepicker('validate')[0].value;
        var date = datetime.split(' ')[0];
        var time = datetime.split(' ')[1];
        if (!isValidCreateDate(date)) {
            isValid = false;
            showError($('#time-close-error'), 'Thời gian đóng đề không hợp lệ!');
        }
    }
    if ($("#quiz-enable-timelimit").prop('checked') == true) {
        var time = $('#quiz-time-limit').datetimepicker('validate')[0].value;
        var h = time.split(':')[0];
        var m = time.split(':')[1];
        if (h < 0 || h > 24) {
            isValid = false;
            showError($("#time-limit-error"), 'Thời gian làm bài phải lớn hơn 0 và nhỏ hơn 24 tiếng!');
        } else {
            if (h == 0) {
                if (m < 0 || m > 60) {
                    isValid = false;
                    showError($("#time-limit-error"), 'Vui lòng kiểm tra lại giá trị thời gian bạn vừa nhập!');
                }
            } else if (h == 24) {
                if (m !== 0) {
                    isValid = false;
                    showError($("#time-limit-error"), 'Thời gian làm bài nhỏ hơn hoặc bằng 24 tiếng!');
                }
            }
        }
    }
    if ($('#attempts-limit').val() == '' || $('#attempts-limit').val() == null) {
        isValid = false;
        showError($("#attempts-limit-error"), 'Số lần thử tối đa không được để trống!');

    }
    if ($('#attempts-limit').val() < 0 || $('#attempts-limit').val() > 10) {
        isValid = false;
        showError($("#attempts-limit-error"), 'Số lần thử tối đa là 10 và nhỏ nhất là 0');
    }
    if ($("#quiz-enable-pwd").prop('checked') == true) {
        var password = $('#quiz-password').val();
        if (password == "" || password == null) {
            isValid = false;
            showError($("#quiz-password-error"), 'Mật khẩu không được để trống!');
        }
    }
    //End validate
    if (isValid) { // check if valid
        $('#form-create-quiz').submit;
        return;
    }
    e.preventDefault();
})

function showError(handle, message) {
    handle.html(message);
    handle.css('display', 'block');
}

function currentDate() {
    var currentdate = new Date();
    var datetime = currentdate.getFullYear() + "/"
        + (currentdate.getMonth() + 1) + "/"
        + currentdate.getDate();
    return datetime;
}

function currentTime() {
    var currentdate = new Date();
    var datetime = currentdate.getHours() + ":"
        + currentdate.getMinutes();
    return datetime;
}

function currentDateTime() {
    var currentdate = new Date();
    var datetime = currentdate.getFullYear() + "/"
        + (currentdate.getMonth() + 1) + "/"
        + currentdate.getDate() + ' '
        + currentdate.getHours() + ":"
        + currentdate.getMinutes();
    return datetime;
}

function currentCloseDateTime() {
    var currentdate = new Date();
    var datetime = currentdate.getFullYear() + "/"
        + (currentdate.getMonth() + 1) + "/"
        + (currentdate.getDate() + 7) + ' '
        + currentdate.getHours() + ":"
        + currentdate.getMinutes();
    return datetime;
}

function rhtmlspecialchars(str) {
    if (typeof (str) == "string") {
        str = str.replace(/&gt;/ig, ">");
        str = str.replace(/&lt;/ig, "<");
        str = str.replace(/&#039;/g, "'");
        str = str.replace(/&quot;/ig, '"');
        str = str.replace(/&amp;/ig, '&');
    }
    return str;
}
