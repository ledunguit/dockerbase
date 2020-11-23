<div class="register-container">
    <div class="title text-footer h3">Đăng ký tài khoản</div>
    <?php if (isset($this->fail)):?>
        <div class="alert alert-danger" id="login-status" data-for="login-status">
            <?=$this->fail; ?>
        </div>
    <?php endif;?>
    <?php if (isset($this->success)):?>
        <div class="alert alert-success" id="login-status" data-for="login-status">
            <?=$this->success; ?>
        </div>
    <?php endif;?>
    <form id="form-register" method="post" class="needs-validation">
        <div class="p-2 mb-2 text-light font-weight-bold bg-footer">Thông tin tài khoản</div>
        <div class="form-group">
            <label for="User[email]">Địa chỉ Email: <span class="text-red">*</span></label>
            <input type="text" id="signupEmail" name="User[email]" class="form-control" placeholder="Nhập địa chỉ Email" value="<?=isset($this->userDataPost['email']) && $this->userDataPost['email'] != null ? $this->userDataPost['email'] : '' ?>" required>
            <span id="error-email" class="text-danger"></span>
        </div>
        <div class="form-group">
            <label for="User[password]">Mật khẩu: <span class="text-red">*</span></label>
            <input type="password" id="signupPassword" name="User[password]" class="form-control" placeholder="Nhập mật khẩu..." required>
            <i id="showPasswordSignup" class="fas fa-eye"></i>
        </div>
        <span id="error-password" class="text-danger"></span>
        <div class="form-group">
            <label for="User[repassword]">Nhập lại mật khẩu: <span class="text-red">*</span></label>
            <input type="password" id="signupRePassword" name="User[repassword]" class="form-control" placeholder="Nhập lại mật khẩu..." required>
            <i id="showRePasswordSignup" class="fas fa-eye"></i>
        </div>
        <span id="error-repassword" class="text-danger"></span>
        <div class="p-2 mb-2 text-light font-weight-bold bg-footer">Thông tin cá nhân</div>
        <div class="form-group">
            <label for="User[lastname]">Nhập họ và tên đệm: <span class="text-red">*</span></label>
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
            <label for="User[birthday]">Ngày tháng năm sinh: <span class="text-red">*</span></label>
            <input type="text" id="signupDatepicker" name="User[birthday]" class="form-control" placeholder="mm/dd/yyyy" value="<?=isset($this->userDataPost['birthday']) && $this->userDataPost['birthday'] != null ? $this->userDataPost['birthday'] : '' ?>" required>
            <span id="error-datepicker" class="text-danger"></span>
        </div>
        <div class="form-group">
            <label for="User[phone]">Số điện thoại di động: <span class="text-red">*</span></label>
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
            <textarea class="form-control" rows="5" id="description" name="User[description]" placeholder="Địa chỉ..."><?=isset($this->userDataPost['description']) && $this->userDataPost['description'] != null ? $this->userDataPost['description'] : '' ?></textarea>
        </div>
        <div class="form-group form-check">
            <label class="form-check-label">
                <input class="form-check-input" id="signupCheckFaq" type="checkbox"> <span class="text-red">*</span> Tôi đồng ý với các điều khoản của website...
            </label>
            <span id="error-checkfaq" class="text-danger"></span>
        </div>
        <div class="form-button">
            <button id="signupBtn" type="submit" class="btn btn-success">Đăng ký</button>
        </div>
    </form>
    <script>CKEDITOR.replace('description', {
            extraPlugins: 'uploadimage',
        });</script>
</div>