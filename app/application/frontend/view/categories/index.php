<div class="container-fluid">
    <div class="main-content">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
            <li class="breadcrumb-item active"><a href="<?= \Venus\Venus::$baseUrl ?>/categories">Danh sách các danh mục</a></li>
        </ul>
        <h2>Danh sách các danh mục</h2>
        <ul class="list-group">
            <?php
            $pageUrl = \Venus\Venus::$baseUrl . '/categories';
            $n = count($this->list);
            $numberOfPage = $n / 5 + 1;
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                if((int) $page > $numberOfPage || (int) $page < 1)
                    $page = 1;
            }
            else {
                $page = 1;
            }
            $str = '';
            if($page == 1){
                $from = 1;
                $end = 5;
            }
            else {
                $from = 5*($page - 1) + 1;
                $end = $from + 5;
            }
            for ($i = $from - 1; $i < $end; $i++) {
                if($i >= $n)
                    break;
                $path = './publics/images/categories/'.$this->list[$i]['shortname'].'.png';
                if(file_exists($path) && getimagesize($path)) {
                }
                else {
                    $path = './publics/images/categories/default.png';
                }
                $class = 'even';
                if($i % 2 == 0)
                    $class = 'odd';
                $str .= '<li class="list-group-item d-flex justify-content-between align-items-center '.$class.'">
                <div class="left"><img src="'.$path.'"/></div>
                <div class="right">
                    <a href="'.\Venus\Venus::$baseUrl.'/categories/'.$this->list[$i]['shortname'].'" class="h5">' . $this->list[$i]['name'] . '</a>
                    <div class="row">
                        <div class="col-sm-12">
                            <b>Mô tả:</b> ' . $this->list[$i]['description'] . '
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-sm-3">
                            <i class="far fa-sticky-note mr-2"></i><b>Số đề thi:</b> ' . $this->list[$i]['numberOfQuizzes'] . '
                        </div>
                        <div class="col-sm-3">
                            <i class="fas fa-users mr-2"></i><b>Số người tham gia:</b> ' . $this->list[$i]['numberOfParticipants'] . '
                        </div>
                        <div class="col-sm-3">
                            <i class="fas fa-question-circle mr-2"></i><b>Tổng số câu hỏi:</b> ' . $this->list[$i]['numberOfQuestions'] . '
                        </div>
                        <div class="col-sm-3">
                            <i class="fas fa-pencil-alt mr-2"></i><b>Số lượt làm bài:</b> ' . $this->list[$i]['numberOfAttempts'] . '
                        </div>
                    </div>
                </div>
            </li>';
            }
            if($str == '')
                echo '<div class="alert alert-warning">Chưa có danh mục nào !</div>';
            else
                echo $str;
            ?>
        </ul>
        <div class="mt-4 d-flex justify-content-center">
            <ul class="pagination mt-4">
                <li class="page-item active"><a class="page-link" href="<?php echo $pageUrl . '?page='; echo ($page <= 1)?1:--$page; ?>">Previous</a></li>
                <?php
                for ($i = 1; $i <= $numberOfPage; $i++)
                    echo '<li class="page-item"><a class="page-link" href="' . $pageUrl . '?page=' . $i . '">'.$i.'</a></li>';
                ?>
                <li class="page-item"><a class="page-link" href="<?php echo $pageUrl . '?page='; echo ($page >= $numberOfPage)?$numberOfPage:++$page; ?>">Next</a>
                </li>
            </ul>
        </div>
    </div>
</div>