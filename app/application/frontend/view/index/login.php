<div class="login-container">
    <div class="title text-info h4">Đăng nhập</div>
        <?php if (isset($this->error['authentication'])):?>
            <div class="alert alert-danger" id="login-status" data-for="login-status">
            <?=$this->error['authentication']; ?>
            </div>
        <?php endif; ?>
    <form id="form-login" method="post">
        <div class="form-group">
            <label for="email">Địa chỉ email:</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Nhập email..." required>
        </div>
        <span class="text-danger" id="email-error"></span>
        <div class="form-group">
            <label for="pwd">Mật khẩu:</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu..." required>
            <i id="showPasswordLogin" class="fas fa-eye"></i>
        </div>
        <span class="text-danger" id="password-error"></span>
        <div class="form-group form-check">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" id="rememberLogin" name="rememberLogin"> Ghi nhớ đăng nhập
            </label>
        </div>
        <div class="form-button">
            <button type="submit" class="btn btn-success" id="loginButton">Đăng nhập</button>
        </div>
    </form>
</div>