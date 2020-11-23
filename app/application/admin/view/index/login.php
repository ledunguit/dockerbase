<div class="skew-color1">
</div>
<div class="login-container">
    <?php if (isset($this->loginError)): ?>
    <span class="login-status" id="loginStatus"><?=$this->loginError?></span>
    <?php endif;?>
    <form method="post" id="adminFormLogin" autocomplete="off">
        <h2 class="login-title">Đăng nhập</h2>
        <div class="admin-form-control">
            <label for="Admin[email]">Email:</label>
            <input type="email" name="Admin[email]" id="adminEmail" placeholder="email@domain.com">
            <span class="error" id="error-email"></span>
        </div>
        <div class="admin-form-control">
            <label for="Admin[password]">Password:</label>
            <input type="password" name="Admin[password]" id="adminPassword" placeholder="Mật khẩu...">
            <span class="error" id="error-password"></span>
        </div>
        <input type="submit" value="Đăng nhập" id="btnLogin">
    </form>
</div>

<script>
    $(document).ready(function() {
        var isValid;
        $("#btnLogin").click(function(e) {
            isValid = true;
            if ($("#adminEmail").val().length == 0) {
                $("#error-email").html("Vui lòng nhập email!");
                isValid = false;
            }
            if ($("#adminPassword").val().length == 0) {
                $("#error-password").html("Vui lòng nhập password!");
                isValid = false;
            }
            if (isValid) {
                $("#adminFormLogin").submit();
            }
            e.preventDefault();
        })
    })
</script>
