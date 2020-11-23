<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hệ thống</title>
    <script type="text/javascript" src="<?= \Venus\Venus::$adminUrl ?>/publics/js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="<?= \Venus\Venus::$adminUrl ?>/publics/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?= \Venus\Venus::$adminUrl ?>/publics/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= \Venus\Venus::$adminUrl ?>/publics/fontawesome/js/all.min.js"></script>
    <script type="text/javascript" src="<?= \Venus\Venus::$adminUrl ?>/publics/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="<?= \Venus\Venus::$adminUrl ?>/publics/datetime/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="<?= \Venus\Venus::$adminUrl ?>/publics/js/Chart.min.js"></script>
    <link rel="stylesheet" href="<?= \Venus\Venus::$adminUrl ?>/publics/css/jquery-ui.min.css">
    <link rel="stylesheet" href="<?= \Venus\Venus::$adminUrl ?>/publics/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= \Venus\Venus::$adminUrl ?>/publics/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= \Venus\Venus::$adminUrl ?>/publics/css/style.css">
    <link rel="stylesheet" href="<?= \Venus\Venus::$adminUrl ?>/publics/admin/css/style.css">
    <link rel="stylesheet" href="<?= \Venus\Venus::$adminUrl ?>/publics/datetime/jquery.datetimepicker.css">
    <link rel="stylesheet" href="<?= \Venus\Venus::$adminUrl ?>/publics/css/Chart.min.css">
    <link rel="shortcut icon" type="image/ico" href="<?= \Venus\Venus::$adminUrl ?>/publics/images/favicon.ico"/>
</head>
<body>
<nav class="navbar bg-footer navbar-expand-sm bg-dark navbar-dark" id="main-nav">
    <ul class="navbar-nav" id="admin-nav">
        <li class="nav-item p-2">
            <a class="nav-link text-light" href="<?= \Venus\Venus::$adminUrl ?>">Trang chủ</a>
        </li>
        <li class="nav-item p-2">
            <a class="nav-link text-light" href="<?= \Venus\Venus::$baseUrl ?>">Bảng điều khiển</a>
        </li>
        <li class="nav-item dropdown p-2">
            <a class="nav-link text-light dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">Chức năng</a>
            <div class="dropdown-menu"  id="drop-control">
                <a class="dropdown-item" href="<?= \Venus\Venus::$baseUrl ?>/user">Quản lý người dùng</a>
                <a class="dropdown-item" href="<?= \Venus\Venus::$baseUrl ?>/categories">Quản lý danh mục</a>
                <a class="dropdown-item" href="<?= \Venus\Venus::$baseUrl ?>/quiz">Quản lý đề thi</a>
                <a class="dropdown-item" href="<?= \Venus\Venus::$baseUrl ?>/questionbank">Ngân hàng câu hỏi</a>
                <a class="dropdown-item" href="<?= \Venus\Venus::$baseUrl ?>/result">Kết quả thi</a>
                <a class="dropdown-item" href="<?= \Venus\Venus::$baseUrl ?>/notification">Viết thông báo</a>
                <a class="dropdown-item" href="<?= \Venus\Venus::$baseUrl ?>/statistics">Thống kê, báo cáo</a>
                <a class="dropdown-item" href="<?= \Venus\Venus::$baseUrl ?>/requirement">Các yêu cầu</a>
                <a class="dropdown-item" href="<?= \Venus\Venus::$baseUrl ?>/setting">Cài đặt</a>
                <a class="dropdown-item" href="<?= \Venus\Venus::$baseUrl ?>/logout">Đăng xuất</a>
            </div>
        </li>
        <li class="nav-item p-2">
            <a class="nav-link text-light" href="#">Trợ giúp</a>
        </li>
    </ul>
    <ul id="showNavContainer"><li id="btnShowNav"><i class="fas fa-bars"></i></li></ul>
</nav>
<?= $this->placeholder() ?>
<div class="footer bg-footer text-center text-light pb-2 pt-2">© 2020 UitHcm. Made with love.</div>
<script type="text/javascript" src="<?= \Venus\Venus::$adminUrl ?>/publics/admin/js/app.js"></script>
</body>
</html>