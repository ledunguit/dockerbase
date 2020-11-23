<?php
$n = count($this->users);
$numberOfPage = $n / 25 + 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $pg = $_GET['page'];
    if ((int)$page > $numberOfPage || (int)$page < 1) {
        $page = 1;
        $pg = 1;
    }
} else {
    $page = 1;
    $pg = 1;
}
$str = '';
if ($page == 1) {
    $from = 0;
    $end = 25;
} else {
    $from = 25 * ($page - 1);
    $end = $from + 25;
}
$pageUrl = \Venus\Venus::$baseUrl . '/user';
?>
<div class="container-fluid"></br>
    <?php if (isset($this->noti['success'])) : ?>
        <div style="text-align: center;" class="alert alert-success"><?=$this->noti['success']?></div>
    <?php elseif (isset($this->noti['failed'])): ?>
        <div style="text-align: center;" class="alert alert-danger"><?=$this->noti['failed']?></div>
    <?php endif; ?>
    <div class="main-content border-none pt-3">
        <h2 class="text-center">Quản lý người dùng</h2>
        <div class="row d-flex justify-content-center"><input class="form-control mb-4 col-sm-5" id="myInput" type="text" placeholder="Nhập từ khóa.."></div>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fas fa-plus mr-2"></i> Thêm người dùng</button>
        <div class="row">
            <div class="col-sm-6">
                <h4 class="text-uppercase mt-4 mb-4 text-center">Danh sách người dùng</h4>
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Họ và tên</th>
                            <th>Địa chỉ Email</th>
                            <th>Trạng thái</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $str = '';
                        $i = 0;
                        foreach ($this->users as $key) {
                            $i++;
                            if ($i <= $from)
                                continue;
                            if ($i > $end)
                                break;
                            $class = ($key->privilege == 0) ? 'danger' : 'success';
                            $status = ($key->privilege == 0) ? 'Đã bị khóa' : 'Đang hoạt động';
                            $str .= '
                        <tr>
                        <td>' . $i . '</td>
                        <td><a href="javascript:viewInfo('.$i.')">' . $key->lastname . ' ' . $key->firstname . '</a></td>
                        <td>' . $key->email . '</td>
                        <td><span class="badge badge-' . $class . '">' . $status . '</span></td>
                        </tr>';
                        }
                        echo $str;
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    <ul class="pagination mt-4">
                        <li class="page-item"><a class="page-link" href="<?php echo $pageUrl . '?page=';
                            echo ($page <= 1) ? 1 : --$page; ?>">Previous</a></li>
                        <?php
                        for ($i = 1; $i <= $numberOfPage; $i++) {
                            $active = '';
                            if ($i == $pg) {
                                $active = ' active';
                            }
                            echo '<li class="page-item' . $active . '"><a class="page-link" href="' . $pageUrl . '?page=' . $i . '">' . $i . '</a></li>';
                        }
                        ?>
                        <li class="page-item"><a class="page-link" href="<?php echo $pageUrl . '?page=';
                            echo ($page >= $numberOfPage) ? $numberOfPage : ++$page; ?>">Next</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6">
                <h3 class="text-uppercase mt-4 mb-4 text-center text-primary">Hồ sơ chi tiết</h3>
                <div style="display:none" id="div-action">
                    <div class="row d-flex">
                        <a class="ml-auto" id="btn-ban"><button type="button" class="btn btn-warning">Vô hiệu hóa</button></a>
                        <a class="ml-3" id="btn-delete"><button type="button" class="btn btn-danger"><i class="fa fa-trash-alt mr-2"></i>Xóa</button></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="text">Họ và tên đệm:</label>
                            <input type="lastname" class="form-control" id="lastname" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="text">Tên:</label>
                            <input type="firstname" class="form-control" id="firstname" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="text">Địa chỉ Email:</label>
                            <input type="email" class="form-control" id="email" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone">Số điện thoại di động:</label>
                            <input type="text" id="phone" name="phone" class="form-control" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="gender">Giới tính:</label>
                            <input type="text" id="gender" name="gender" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="birthday">Ngày tháng năm sinh:</label>
                            <input type="text" id="birthday" name="birthday" class="form-control" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="User[address]">Địa chỉ:</label>
                    <textarea class="form-control" rows="2" id="address" name="address" disabled></textarea>
                </div>
                <div class="form-group">
                    <label for="User[organization]">Tổ chức / Trường học / Nơi làm việc:</label>
                    <input type="text" id="organization" name="organization" class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label for="User[department]">Phòng / Ban / Khoa:</label>
                    <input type="text" id="department" name="department" class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label for="description">Giới thiệu bản thân:</label>
                    <hr>
                    <p id="description" class="questiontext"></p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div id="dialog-delete" title="Xóa tài khoản người dùng?" style="display: none">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Bạn có chắc chắn muốn <b style="color: red;">xóa</b>
        người dùng này không?</p>
</div>
<div id="dialog-ban" title="Vô hiệu hóa tài khoản" style="display: none">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Bạn có chắc chắn muốn <b style="color: red;">vô hiệu hóa</b> người dùng này không?</p>
</div>
<div id="dialog-unban" title="Vô hiệu hóa tài khoản" style="display: none">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Bạn có chắc chắn muốn <b style="color: green;">mở khóa</b> người dùng này không?</p>
</div>
<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thêm người dùng mới</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" id="form-register">
            <!--==========HTML TẠO USER=============-->
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="User[email]">Địa chỉ Email:</label>
                        <input type="text" id="signupEmail" name="User[email]" class="form-control" placeholder="Nhập địa chỉ Email" value="<?=isset($this->userDataPost['email']) && $this->userDataPost['email'] != null ? $this->userDataPost['email'] : '' ?>" required>
                        <span id="error-email" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="User[password]">Mật khẩu:</label>
                        <input type="password" id="signupPassword" name="User[password]" class="form-control" placeholder="Nhập mật khẩu..." required>
                        <i id="showPasswordSignup" class="fas fa-eye"></i>
                    </div>
                    <span id="error-password" class="text-danger"></span>
                    <div class="form-group">
                        <label for="User[repassword]">Nhập lại mật khẩu:</label>
                        <input type="password" id="signupRePassword" name="User[repassword]" class="form-control" placeholder="Nhập lại mật khẩu..." required>
                        <i id="showRePasswordSignup" class="fas fa-eye"></i>
                    </div>
                    <span id="error-repassword" class="text-danger"></span>
                    <div class="form-group">
                        <label for="User[lastname]">Nhập họ và tên đệm:</label>
                        <input type="text" id="signupLastname" name="User[lastname]" class="form-control" placeholder="Nhập họ và tên đệm..." value="<?=isset($this->userDataPost['lastname']) && $this->userDataPost['lastname'] != null ? $this->userDataPost['lastname'] : '' ?>" required>
                        <span id="error-lastname" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="User[firstname]">Tên</label>
                        <input type="text" id="signupFirstname" name="User[firstname]" class="form-control" placeholder="Nhập tên..." value="<?=isset($this->userDataPost['firstname']) && $this->userDataPost['firstname'] != null ? $this->userDataPost['firstname'] : '' ?>" required>
                        <span id="error-firstname" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="User[gender]">Giới tính:</label>
                        <select class="form-control" id="signupGender" name="User[gender]">
                            <option value="1" selected="selected">Nam</option>
                            <option value="0">Nữ</option>
                            <option value="3">Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="User[birthday]">Ngày tháng năm sinh:</label>
                        <input type="text" id="signupDatepicker" name="User[birthday]" class="form-control" placeholder="mm/dd/yyyy" value="<?=isset($this->userDataPost['birthday']) && $this->userDataPost['birthday'] != null ? $this->userDataPost['birthday'] : '' ?>" required>
                        <span id="error-datepicker" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="User[phone]">Số điện thoại di động:</label>
                        <input type="text" id="signupPhone" name="User[phone]" class="form-control" placeholder="Nhập số điện thoại..." value="<?=isset($this->userDataPost['phone']) && $this->userDataPost['phone'] != null ? $this->userDataPost['phone'] : '' ?>">
                        <span id="error-phone" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="User[address]">Địa chỉ:</label>
                        <textarea class="form-control" rows="2" id="signupAddress" name="User[address]" placeholder="Địa chỉ..."><?=isset($this->userDataPost['address']) && $this->userDataPost['address'] != null ? $this->userDataPost['address'] : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="User[organization]">Tổ chức / Trường học / Nơi làm việc:</label>
                        <input type="text" id="signupOrganization" name="User[organization]" class="form-control" placeholder="Nhập tên cơ quan, trường học của bạn..." value="<?=isset($this->userDataPost['organization']) && $this->userDataPost['organization'] != null ? $this->userDataPost['organization'] : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="User[department]">Phòng / Ban / Khoa:</label>
                        <input type="text" id="signupDepartment" name="User[department]" class="form-control" placeholder="Nhập phòng/ban/khoa/lớp..." value="<?=isset($this->userDataPost['department']) && $this->userDataPost['department'] != null ? $this->userDataPost['department'] : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="User[description]">Giới thiệu bản thân:</label>
                        <textarea class="form-control" rows="5" id="User[description]" name="User[description]" placeholder="Địa chỉ..."><?=isset($this->userDataPost['description']) && $this->userDataPost['description'] != null ? $this->userDataPost['description'] : '' ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="create-user">Xác nhận</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>
        </form>
            <!--========================================-->
        </div>
    </div>
</div>
<?php $json = json_encode($this->users);?>
<script>
    CKEDITOR.replace('User[description]', {
        extraPlugins: 'uploadimage',
    });
    $(document).ready(function () {
        $("#myInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    function ban(id){
        var currentUrl = window.location.href;
        $("#dialog-ban").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Vô hiệu hóa": function () {
                    var dataInput = {};
                    dataInput['userid'] = id;
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "/admin/ajax/banUser",
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function(res) {
                            var ress = JSON.parse(res).status;
                            if (ress == "Thành công!") {
                                window.location.href = currentUrl;
                            } else {
                                window.location.href = currentUrl;
                            }
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }
    function unBan(id){
        var currentUrl = window.location.href;
        $("#dialog-unban").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Mở khóa": function () {
                    var dataInput = {};
                    dataInput['userid'] = id;
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "/admin/ajax/unbanUser",
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function(res) {
                            var ress = JSON.parse(res).status;
                            if (ress == "Thành công!") {
                                window.location.href = currentUrl;
                            } else {
                                window.location.href = currentUrl;
                            }
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }
    function deleteUser(id) {
        var currentUrl = window.location.href;
        $("#dialog-delete").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Xóa": function () {
                    var dataInput = {};
                    dataInput['userid'] = id;
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: "/admin/ajax/deleteUser",
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function(res) {
                            var ress = JSON.parse(res).status;
                            if (ress == "Thành công!") {
                                window.location.href = currentUrl;
                            } else {
                                window.location.href = currentUrl;
                            }
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function viewInfo(id){
        var a = Object.assign({}, <?=json_encode($this->users);?>);
        $('#lastname').val(a[id-1].lastname);
        $('#firstname').val(a[id-1].firstname);
        $('#email').val(a[id-1].email);
        $('#phone').val(a[id-1].phone);
        var gender = a[id-1].gender;
        if(gender == 0)
            gender = "Nữ";
        else if(gender == 1)
            gender = "Nam";
        else gender = "Khác";
        $('#gender').val(gender);
        $('#birthday').val(a[id-1].birthday);
        $('#address').val(a[id-1].address);
        $('#organization').val(a[id-1].organization);
        $('#department').val(a[id-1].department);
        $('#description').html(rhtmlspecialchars(a[id-1].description));
        $('#div-action').css('display', 'block');
        if(a[id-1].privilege == 0) {
            $('#btn-ban').children().html('<i class="fas fa-unlock mr-2"></i>Mở khóa');
            $('#btn-ban').children().removeClass('btn-warning bg-warning').addClass('btn-success bg-success');
            $('#btn-ban').attr('href', 'javascript:unBan(' + a[id-1].id + ')');
        }
        else {
            $('#btn-ban').children().html('<i class="fas fa-lock mr-2"></i>Vô hiệu hóa');
            $('#btn-ban').children().removeClass('btn-success bg-success').addClass('btn-warning bg-warning');
            $('#btn-ban').attr('href', 'javascript:ban(' + a[id-1].id + ')');
        }
        $('#btn-delete').attr('href', 'javascript:deleteUser(' + a[id-1].id + ')');
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

    $("#signupDatepicker").datepicker();
    $("#anim").on("change", function () {
        $("#signupDatepicker").datepicker("option", "showAnim", $(this).val());
    });

    function checkEmail(emailValue) {
        var regularExpression = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return emailValue.match(regularExpression);
    }

    function checkPassword(passwordValue) {
        var regularExpression = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
        return passwordValue.match(regularExpression);
    }

    var formSignup = document.getElementById("form-register");
    var emailSignup = document.getElementById("signupEmail");
    var passwordSignup = document.getElementById("signupPassword");
    var repasswordSignup = document.getElementById("signupRePassword");
    var lastnameSignup = document.getElementById("signupLastname");
    var firstnameSignup = document.getElementById("signupFirstname");
    var datepickerSignup = document.getElementById("signupDatepicker");
    var phoneSignup = document.getElementById("signupPhone");

    var signupBtn = document.getElementById("create-user");
    if (signupBtn !== null) {
        signupBtn.addEventListener('click', (e) => {
            e.preventDefault();
            validateSignup();
        })
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

    function setError(holder, message) {
        holder.style.display = "block";
        holder.innerHTML = message;
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
        var errorEmail = document.getElementById("error-email");
        var errorPassword = document.getElementById("error-password");
        var errorRePassword = document.getElementById("error-repassword");
        var errorLastname = document.getElementById("error-lastname");
        var errorFirstname = document.getElementById("error-firstname");
        var errorDatepicker = document.getElementById("error-datepicker");
        var errorPhone = document.getElementById("error-phone");
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
        if (errorCount == 0) {
            formSignup.submit();
        }
    }

</script>