<div class="container-fluid">
    <div class="main-content" style="border:none">
        <h2 class="d-flex justify-content-center text-light">Quản trị hệ thống</h2>
        <div class="card-deck card-columns font-weight-bold mt-5">
            <div class="card alert-secondary">
                <div class="card-body text-center">
                    <img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/icon/user.png" alt="" width="64">
                    <p class="card-text mt-2"><a href="<?= \Venus\Venus::$baseUrl ?>/user" class="stretched-link"></a>Quản
                        lý người dùng</p>
                </div>
            </div>
            <div class="card alert-secondary">
                <div class="card-body text-center">
                    <img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/icon/listing.png" alt="" width="64">
                    <p class="card-text mt-2"><a href="<?= \Venus\Venus::$baseUrl ?>/categories"
                                                 class="stretched-link"></a>Danh mục</p></p>
                </div>
            </div>
            <div class="card alert-secondary">
                <div class="card-body text-center">
                    <img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/icon/learning.png" alt="" width="64">
                    <p class="card-text mt-2"><a href="<?= \Venus\Venus::$baseUrl ?>/quiz" class="stretched-link"></a>Các
                        đề thi</p></p>
                </div>
            </div>
            <div class="card alert-secondary">
                <div class="card-body text-center">
                    <img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/icon/bank.png" alt="" width="64">
                    <p class="card-text mt-2"><a href="<?= \Venus\Venus::$baseUrl ?>/questionbank"
                                                 class="stretched-link"></a>Ngân hàng câu hỏi</p></p>
                </div>
            </div>
            <div class="card alert-secondary">
                <div class="card-body text-center">
                    <img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/icon/gradetable.png" alt="" width="64">
                    <p class="card-text mt-2"><a href="<?= \Venus\Venus::$baseUrl ?>/result" class="stretched-link"></a>Kết
                        quả thi</p></p>
                </div>
            </div>
        </div>
        <div class="card-deck card-columns font-weight-bold mt-5">
            <div class="card alert-secondary">
                <div class="card-body text-center">
                    <img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/icon/notification.png" alt="" width="64">
                    <p class="card-text mt-2"><a href="<?= \Venus\Venus::$baseUrl ?>/notification"
                                                 class="stretched-link"></a>Quản lý thông báo</p>
                </div>
            </div>
            <div class="card alert-secondary">
                <div class="card-body text-center">
                    <img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/icon/statistics.png" alt="" width="64">
                    <p class="card-text mt-2"><a href="<?= \Venus\Venus::$baseUrl ?>/statistics"
                                                 class="stretched-link"></a>Thống kê, báo cáo</p></p>
                </div>
            </div>
            <div class="card alert-secondary">
                <div class="card-body text-center">
                    <img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/icon/to-do-list.png" alt="" width="64">
                    <p class="card-text mt-2"><a href="<?= \Venus\Venus::$baseUrl ?>/requirement"
                                                 class="stretched-link"></a>Các yêu cầu</p></p>
                </div>
            </div>
            <div class="card alert-secondary">
                <div class="card-body text-center">
                    <img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/icon/gears.png" alt="" width="64">
                    <p class="card-text mt-2"><a href="<?= \Venus\Venus::$baseUrl ?>/setting"
                                                 class="stretched-link"></a>Cài đặt</p></p>
                </div>
            </div>
            <div class="card alert-secondary">
                <div class="card-body text-center">
                    <img src="<?= \Venus\Venus::$adminUrl ?>/publics/images/icon/log-out.png" alt="" width="64">
                    <p class="card-text mt-2"><a href="<?= \Venus\Venus::$baseUrl ?>/logout"
                                                 class="stretched-link"></a>Đăng xuất</p>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .card:hover {
        background: #cce5ff;
        color: #004085;
        border-color: #b8daff;
    }
     body {
         background: #24324a;
     }
</style>