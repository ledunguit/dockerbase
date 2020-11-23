<?php
$user = $this->userInfo;
?>
<div class="container-fluid">
    <div class="main-content p-3">
        <h2>Chỉnh sửa hồ sơ</h2>
        <div class="content row">
            <?php if (isset($this->error['avt'])): ?>
                <div class="alert alert-danger"><?=$this->error['avt']?></div>
            <?php elseif (isset($this->success['avt'])): ?>
                <div class="alert alert-success"><?=$this->success['avt']?></div>
            <?php endif; ?>
        </div>
        <div class="content row">
            <?php if (isset($this->error['status'])): ?>
                <div class="alert alert-danger"><?=$this->error['status']?></div>
            <?php elseif (isset($this->success['status'])): ?>
                <div class="alert alert-success"><?=$this->success['status']?></div>
            <?php endif; ?>
        </div>
        <div class="content row">
            <div class="col-sm-8">
                <form id="form-edit-account" method="post" class="needs-validation" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="User[email]">Hình ảnh đại diện:</label>
                        <div id="avt" style="margin: 10px 0px 20px 0px;" width="50px">
                        <?php if (getimagesize(\Venus\Venus::$baseUrl . '/publics/images/avatar/' . $user->id . '.jpg')): ?>
                            <img src="<?=\Venus\Venus::$baseUrl . '/publics/images/avatar/' . $user->id . '.jpg'?>" />
                        <?php elseif (getimagesize(\Venus\Venus::$baseUrl . '/publics/images/avatar/' . $user->id . '.png')): ?>
                            <img src="<?=\Venus\Venus::$baseUrl . '/publics/images/avatar/' . $user->id . '.png'?>" />
                        <?php else: ?>
                            <img src="<?=\Venus\Venus::$baseUrl?>/publics/images/avatar/default.png" width='180' />
                        <?php endif; ?>
                        </div>
                        <button type="button" id="btnDeleteAvt">Xóa Avt</button>
                        <div class="alert alert-primary" id="deleteStatus" style="display: none;"></div>
                        <div class="custom-file">
                            <input type="file" name="fileUpload" class="custom-file-input" id="fileUpload" accept=".png, .jpg" value="">
                            <label class="custom-file-label" for="fileToUpload">Choose file</label>
                        </div>
                    </div>

                    <script>
                        $(".custom-file-input").on("change", function () {
                            var fileName = $(this).val().split("\\").pop();
                            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                        });
                    </script>
                    <div class="form-group">
                        <label for="User[email]">Địa chỉ Email:</label>
                        <input type="text" id="editEmail" name="User[email]" class="form-control"
                               placeholder="Nhập địa chỉ Email"
                               value="<?= isset($user->email) && $user->email != null ? $user->email : '' ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="User[lastname]">Nhập họ và tên đệm:</label>
                        <input type="text" id="editLastname" name="User[lastname]" class="form-control"
                               placeholder="Nhập họ và tên đệm..."
                               value="<?= isset($user->lastname) && $user->lastname != null ? $user->lastname : '' ?>"
                               required>
                        <span id="error-lastname" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="User[firstname]">Tên</label>
                        <input type="text" id="editFirstname" name="User[firstname]" class="form-control"
                               placeholder="Nhập tên..."
                               value="<?= isset($user->firstname) && $user->firstname != null ? $user->firstname : '' ?>"
                               required>
                        <span id="error-firstname" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="User[gender]">Giới tính:</label>
                        <select class="form-control" id="editGender" name="User[gender]">
                            <option value="1" <?php echo ($user->gender==1)?'selected':'';?>>Nam</option>
                            <option value="0" <?php echo ($user->gender==0)?'selected':'';?>>Nữ</option>
                            <option value="2" <?php echo ($user->gender==2)?'selected':'';?>>Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="User[birthday]">Ngày tháng năm sinh:</label>
                        <input type="text" id="editDatepicker" name="User[birthday]" class="form-control"
                               placeholder="yyyy-mm-dd"
                               value="<?= isset($user->birthday) && $user->birthday != null ? $user->birthday : '' ?>"
                               required>
                        <span id="error-datepicker" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="User[phone]">Số điện thoại di động:</label>
                        <input type="text" id="editPhone" name="User[phone]" class="form-control"
                               placeholder="Nhập số điện thoại..."
                               value="<?= isset($user->phone) && $user->phone != null ? $user->phone : '' ?>">
                        <span id="error-phone" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="User[address]">Địa chỉ:</label>
                        <textarea class="form-control" rows="2" id="editAddress" name="User[address]"
                                  placeholder="Địa chỉ..."><?= isset($user->address) && $user->address != null ? $user->address : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="User[organization]">Tổ chức / Trường học / Nơi làm việc:</label>
                        <input type="text" id="editOrganization" name="User[organization]" class="form-control"
                               placeholder="Nhập tên cơ quan, trường học của bạn..."
                               value="<?= isset($user->organization) && $user->organization != null ? $user->organization : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="User[department]">Phòng / Ban / Khoa:</label>
                        <input type="text" id="editDepartment" name="User[department]" class="form-control"
                               placeholder="Nhập phòng/ban/khoa/lớp..."
                               value="<?= isset($user->department) && $user->department != null ? $user->department : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="User[description]">Giới thiệu bản thân:</label>
                        <textarea class="form-control" rows="5" id="description" name="User[description]" placeholder="Giới thiệu bản thân..."><?= isset($user->description) && $user->description != null ? $user->description : '' ?></textarea>
                    </div>
                    <div class="form-button">
                        <input name="btnUpdate" id="editBtn" type="submit" class="btn btn-success" value="Cập nhật"></input>
                    </div>
                </form>
            </div>
        </div>
        <div id="dialog-delete-avt" title="Thông báo xác nhận" style="display: none">
            <p>Bạn có chắc chắn muốn xóa avatar hiện tại?</p>
        </div>
    </div>
</div>
    <script>
    $("#btnDeleteAvt").click(function() {
        $("#dialog-delete-avt").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Xóa": function() {
                    var dataInput = {};
                    dataInput['userid'] = <?=\Venus\User::getInfo()->id ?>;
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/deleteAvt',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response);;
                            if (res.status == 'deleted') {
                                window.location.href = '/account/edit';
                            } else if (res.status == 'none') {
                                $("#deleteStatus").css('display', 'block');
                                $("#deleteStatus").html("Không thể xóa avatar do avatar đang trống!");
                            }
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function() {
                    $(this).dialog("close");
                }
            }
        });
    })
        CKEDITOR.replace('description', {
            extraPlugins: 'uploadimage'
        });
        function validateDateEdit(date) {
            var tempDate = date.split('-');
            if (tempDate.length !== 3) {
                return false;
            } else {
                const year = tempDate[0];
                const month = tempDate[1];
                const day = tempDate[2];
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

        var isValid;
        $("#editBtn").click(function(e) {
            isValid = true;
            var lastname = $("#editLastname").val();
            var firstname = $("#editFirstname").val();
            var gender = $("#editGender").val();
            var birthday = $("#editDatepicker").val();
            var phone = $("#editPhone").val();

            //validate start
            if (!checkName(lastname)) {
                isValid = false;
                message = "Vui lòng nhập họ và tên đệm hợp lệ!";
                setErrorEdit($("#error-lastname"), message);
            }
            if (!checkName(firstname)) {
                isValid = false;
                message = "Vui lòng nhập tên hợp lệ!";
                setErrorEdit($("#error-firstname"), message);
            }
            if (!validateDateEdit(birthday)) {
                isValid = false;
                message = "Vui lòng chọn ngày hợp lệ!";
                setErrorEdit($("#error-datepicker"), message);
            }
            if (!checkPhone(phone)) {
                isValid = false;
                message = "Vui lòng kiểm tra lại số điện thoại!";
                setErrorEdit($("#error-phone"), message);
            }
            //validate end
            if (isValid === true) {
                $("#form-edit-account").submit();
            } else {
                e.preventDefault();
            }
        })

        function setErrorEdit(handle, message) {
            handle.html(message);
            handle.css("display", "block");
        }
    </script>