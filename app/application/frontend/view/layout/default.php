<?php

use Venus\Helper;
use Venus\User;
use Venus\Venus;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->title ?></title>
    <script type="text/javascript" src="<?= Venus::$baseUrl ?>/publics/js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="<?= Venus::$baseUrl ?>/publics/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?= Venus::$baseUrl ?>/publics/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= Venus::$baseUrl ?>/publics/fontawesome/js/all.min.js"></script>
    <script type="text/javascript" src="<?= Venus::$baseUrl ?>/publics/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="<?= Venus::$baseUrl ?>/publics/datetime/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="<?= Venus::$baseUrl ?>/publics/js/Chart.min.js"></script>
    <link rel="stylesheet" href="<?= Venus::$baseUrl ?>/publics/css/jquery-ui.min.css">
    <link rel="stylesheet" href="<?= Venus::$baseUrl ?>/publics/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= Venus::$baseUrl ?>/publics/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= Venus::$baseUrl ?>/publics/css/style.css">
    <link rel="stylesheet" href="<?= Venus::$baseUrl ?>/publics/datetime/jquery.datetimepicker.css">
    <link rel="stylesheet" href="<?= Venus::$baseUrl ?>/publics/css/Chart.min.css">
    <link rel="shortcut icon" type="image/ico" href="<?= Venus::$baseUrl ?>/publics/images/favicon.ico"/>
</head>
<body>
<div class="wrap" id="wrapMenu">
    <div class="container-fluid">
        <div class="row justify-content-center" id="header">
            <div class="col-md-3" id="logo">
                <a href="<?= Venus::$baseUrl ?>">
                    <img src="<?= Venus::$baseUrl ?>/publics/images/logo.png" width="64" height="64"/>
                </a>
            </div>
            <div class="col-md-4 d-flex" id="contact-header">
                <div class="d-flex">
                    <span class="icon-copyright align-items-center justify-content-center" style="padding: 13px"><i
                                class="fas fa-location-arrow" style="color:#01d28e;margin:auto;"></i></span></div>
                <div class="header-text">
                    <div class="text-uppercase gray"><?php echo Helper::webInfo()->headerone?></div>
                    <div><?php echo Helper::webInfo()->headertwo?></div>
                </div>
            </div>
            <?php if (!User::logged()): ?>
                <div class="col-md-4" id="user-operator">
                    <ul class="user-control">
                        <li>
                            <a href="<?= Venus::$baseUrl ?>/login">
                                <span class="btn-header btn-login" id="btnLogin">Đăng nhập</span>
                                <span class="btn-header btn-login" id="btnLoginSmall"><i class="fas fa-sign-in-alt"></i></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Venus::$baseUrl ?>/signup">
                                <span class="btn-header btn-register" id="btnSignup">Đăng ký</span>
                                <span class="btn-header btn-register" id="btnSignupSmall"><i
                                            class="fas fa-user-plus"></i></span>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="col-md-4 d-flex justify-content-center" id="userControl">
                    <?php $path = './publics/images/avatar/' . User::getInfo()->id . '.jpg';
                        $path1 = './publics/images/avatar/' . User::getInfo()->id . '.png';
                    if (file_exists($path) && getimagesize('./publics/images/avatar/' . User::getInfo()->id . '.jpg')): ?>
                        <img src="<?=Venus::$baseUrl . '/publics/images/avatar/' . User::getInfo()->id . '.jpg' ?>"
                             id="btnAccount"/>
                    <?php elseif (file_exists($path1) && getimagesize(Venus::$baseUrl . '/publics/images/avatar/' . User::getInfo()->id . '.png')): ?>
                        <img src="<?= Venus::$baseUrl . '/publics/images/avatar/' . User::getInfo()->id . '.png' ?>"
                             id="btnAccount"/>
                    <?php else: ?>
                        <img src="<?= Venus::$baseUrl ?>/publics/images/avatar/default.png" id="btnAccount"/>
                    <?php endif; ?>
                    <div class="user-info">
                        <span class="user-info-name">&nbsp;<?= User::getInfo()->lastname . ' ' . User::getInfo()->firstname ?></span></br>
                        <span class="user-info-email">&nbsp;<?= User::getInfo()->email ?></span></br>
                    </div>
                    <div class="user-extend" id="user-extend">
                        <ul class="list-extend">
                            <li><a href="/account"><i class="fas fa-address-card"></i>&nbsp;Xem hồ sơ</a></li>
                            <li><a href="/account/changepassword"><i class="fas fa-key"></i>&nbsp;Chỉnh sửa mật khẩu</a>
                            </li>
                            <li><a href="/logout"><i class="fas fa-sign-out-alt"></i>&nbsp;Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="expand" id="expand"><i class="fas fa-bars"></i></div>
    </div>
    <div class="nav-1" id="navbar">
        <div class="container">
            <ul id="menu">
                <li><a href="<?= Venus::$baseUrl ?>">Trang chủ</a></li>
                <li><a href="<?= Venus::$baseUrl ?>/introduction">Giới thiệu</a></li>
                <li><a href="<?= Venus::$baseUrl ?>/categories">Các danh mục</a></li>
                <?php echo (User::logged())?'<li><a href="'.Venus::$baseUrl.'/dashboard">'.'Bảng điều khiển</a></li>':''?>
                <li><a href="<?= Venus::$baseUrl ?>/quiz/enter">Nhập mã</a></li>
                <li><a href="<?= Venus::$baseUrl ?>/help">Trợ giúp</a></li>
            </ul>
        </div>
    </div>
</div>
<?= $this->placeholder() ?>
<div class="footer shadow-top pb-5">
    <div class="container white-text justify-content-center">
        <div class="row">
            <div class="col-sm-4">
                <div class="h5">About site</div>
                <p><?php echo Helper::webInfo()->aboutsite?><br>
                    <b>Powered by:</b> <?php echo Helper::webInfo()->poweredby?></p>
                <div class="d-flex justify-content-center">
                    <a href="<?php echo Helper::webInfo()->facebook?>"><span class="p-2"><i class="fab fa-facebook-f mr-2"></i></span</a>
                    <a href="<?php echo Helper::webInfo()->instagram?>"><span class="p-2"><i class="fab fa-instagram mr-2"></i></span></a>
                    <a href="<?php echo Helper::webInfo()->twitter?>"><span class="p-2"><i class="fab fa-twitter mr-2"></i></span></a>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="h5">Services</div>
                <p>
                    <i class="fas fa-check mr-2"></i>Đề thi chuẩn mực<br/>
                    <i class="fas fa-check mr-2"></i>Đáp án và giải thích chi tiết<br/>
                    <i class="fas fa-check mr-2"></i>Quản lý đề thi chuyên nghiệp<br/>
                </p>
            </div>
            <div class="col-sm-5">
                <div class="h5">Contact Us</div>
                <p><i class="fas fa-map-marker-alt mr-2"></i><?php echo Helper::webInfo()->address?> <br>
                    <i class="fas fa-phone-alt mr-2"></i><?php echo Helper::webInfo()->phone?><br>
                    <i class="fas fa-paper-plane mr-2"></i><?php echo Helper::webInfo()->email?><br>
                </p>
            </div>
        </div>
        <div class="row"><p class="col-sm-12 text-center">© 2020 nt208.l11.mmcl - Made with love.</p></div>
    </div>
</div>
<script src="<?= Venus::$baseUrl ?>/publics/js/app.js"></script>
<script src="<?= Venus::$baseUrl ?>/publics/js/md5.js"></script>
</body>
</html>