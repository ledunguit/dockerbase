<div class="container-fluid">
    <div class="main-content pt-4 pb-4">
        <div class="h2 text-uppercase"><i class="fas fa-lock mr-3 text-warning"></i>Đổi mật khẩu</div>
        <?php if (isset($this->changePassStatus)): ?>
            <?php if ($this->changePassStatus == '1'): ?>
                <div class="row justify-content-center">
                    <span class="alert alert-success" id="change-pass-status"">Bạn đã đổi mật khẩu thành công!</span>
                </div>
            <?php elseif ($this->changePassStatus == '2'): ?>
            <div class="row justify-content-center">
                <span class="alert alert-danger" id="change-pass-status">Lỗi hệ thống! Vui lòng liên hệ quản trị của bạn!</span>
            </div>
            <?php elseif ($this->changePassStatus == '3'): ?>
            <div class="row justify-content-center">
                <span class="alert alert-danger" id="change-pass-status">Mật khẩu cũ không đúng, không thể xác minh danh tính!</span>
            </div>
            <?php endif; ?>
        <?php endif;?>
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div id="dialog-change-pwd" title="Chỉnh sửa mật khẩu" style="display: none;">
                    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Bạn có
                        chắc chắn muốn chỉnh sửa mật khẩu?</p>
                </div>
                <form method="post" id="form-change-pwd">
                    <div class="alert alert-success" id="change-pwd-result" style="display:none;"></div>
                    <div class="form-group">
                        <label for="old-password">Mật khẩu cũ:</label>
                        <input type="password" class="form-control" placeholder="Nhập mật khẩu cũ" id="old-password" name="User[old-password]">
                        <span class="change-pass-alert alert-danger" id="error-for-oldpass">Alert pass cũ nè</span>
                    </div>
                    <div class="form-group">
                        <label for="new-password">Mật khẩu mới:</label>
                        <input type="password" class="form-control" placeholder="Nhập mật khẩu mới" id="new-password" name="User[new-password]">
                        <span class="change-pass-alert alert-danger" id="error-for-newpass">Alert pass mới nè</span>
                    </div>
                    <div class="form-group">
                        <label for="re-new-password">Nhập lại mật khẩu mới:</label>
                        <input type="password" class="form-control" placeholder="Nhập lại mật khẩu mới" id="re-new-password" name="User[re-new-password]">
                        <span class="change-pass-alert alert-danger" id="error-for-re-newpass">Alert pass nhập lại nè</span>
                    </div>
                    <button type="submit" id="btnChangePass" class="btn btn-success">Xác nhận</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var isValid;
    $("#btnChangePass").click(function(e) {
        isValid = true;
        if ($("#old-password").val() == null || $("#old-password").val() == "") {
            $("#error-for-oldpass").html("Mật khẩu cũ không được để trống!");
            $("#error-for-oldpass").css("display", "block");
            isValid = false;
        }
        if ($("#new-password").val() == null || $("#new-password").val() == "") {
            $("#error-for-newpass").html("Mật khẩu mới không được để trống!");
            $("#error-for-newpass").css("display", "block");
            isValid = false;
        }
        if ($("#re-new-password").val() != $("#new-password").val()) {
            $("#error-for-re-newpass").html("Mật khẩu nhập lại không trùng!");
            $("#error-for-re-newpass").css("display", "block");
            isValid = false;
        }
        if (isValid) {
            $("#dialog-change-pwd").dialog({
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "Xác nhận": function () {
                        if (isValid) {
                            $("#form-change-pwd").submit();
                        } else {
                            e.preventDefault();
                        }
                    },
                    "Hủy bỏ": function () {
                        $(this).dialog("close");
                        e.preventDefault();
                    }
                }
            });
        }
        e.preventDefault();
    })
</script>