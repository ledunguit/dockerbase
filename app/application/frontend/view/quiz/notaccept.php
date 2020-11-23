<div class="container-fluid">
    <div class="main-content">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>/quiz">Các đề thi</a></li>
            <?php
            if(isset($this->quizDetails)) : ?>
                <li class="breadcrumb-item"><a href=""><?=$this->quizDetails['name']; ?></a></li>
            <?php endif; ?>
            <li class="breadcrumb-item"><a href="">Lỗi</a></li>
        </ul>
        <h2>Truy cập:</h2>
        <?php
        $str = 'Bạn không có quyền truy cập vào mục này. Vui lòng kiểm tra lại.';
        if(isset($this->quizDetails)){
            if ($this->quizDetails['acceptguest'] == 0) {
                $str = 'Bạn cần đăng nhập để ghi danh vào đề thi !';
            };
            if ($this->quizDetails['visible'] == 0 || $this->quizDetails['status'] == -1) {
                $str = 'Đề thi không tồn tại hoặc bạn không có quyền truy cập. Vui lòng kiểm tra lại!';
            }
        }
        ?>
        <div class="alert alert-danger"><b>Lỗi:</b> <?php echo $str; ?></div>
    </div>
</div>