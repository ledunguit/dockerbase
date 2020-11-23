<?php
$details = $this->categoryDetail;
$quiz = $this->quizzes;
$users = $this->users;
?>
<div class="container-fluid">
    <div class="main-content">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>/categories">Các danh mục</a></li>
            <li class="breadcrumb-item active"><a href=""><?php echo $details['name']; ?></a></li>
        </ul>
        <h2><?php echo $details['name']; ?></h2>
        <?php echo $details['description']; ?>
        <div class="row d-flex justify-content-center col-sm-10">
            <table class="table table-bordered mt-4">
                <tr>
                    <td class="font-weight-bold" style="width: 30%">Tên danh mục:</td>
                    <td class="font-weight-bold text-uppercase"><?php echo $details['name']; ?></td>
                </tr>
                <tr>
                    <td class="font-weight-bold" style="width: 30%">Tổng số đề thi:</td>
                    <td class=""><?php echo $details['numberOfQuizzes']; ?></td>
                </tr>
                <tr>
                    <td class="font-weight-bold" style="width: 30%">Tổng số câu hỏi:</td>
                    <td class=""><?php echo $details['numberOfQuestions']; ?></td>
                </tr>
                <tr>
                    <td class="font-weight-bold" style="width: 30%">Tổng số lượt thi:</td>
                    <td class=""><?php echo $details['numberOfAttempts']; ?></td>
                </tr>
            </table>
        </div>

    </div>
    <div class="main-content">
        <?php $pageUrl = \Venus\Venus::$baseUrl . '/categories/' . $details['shortname'];
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        else {
            $page = 1;
        }
        $numberOfPage = count($quiz) / 10 + 1; ?>
        <div class="left">
            <h2>Danh sách đề thi</h2>
            <div class="list-group">
                <?php
                $i = 0;
                if($page == 1){
                    $from = 1;
                    $end = 10;
                }
                else {
                    $from = 10*($page - 1) + 1;
                    $end = $from + 10;
                }
                $str = '';
                foreach ($quiz as $key) {
                    $i++;
                    if($i < $from)
                        continue;
                    if($i > $end)
                        break;
                    if ($i % 2 == 0)
                        $class = 'odd';
                    else $class = 'even';
                    $str .= '
                <div class="list-group-item list-group-item-action ' . $class . '">
                    <div class="row ml-1"><a href="'.\Venus\Venus::$baseUrl.'/quiz/enroll/'.$key['id'].'" class="h5 list-group-item-action">' . $key['name'] . '</a></div>
                    <div class="row ml-1">' . $key['summary'] . '</div>
                    <div class="row ml-1 mt-2">
                        <div class="col-sm-3"><i class="fas fa-user text-info mr-2"></i>' . $key['createdByName'] . '</div>
                        <div class="col-sm-3"><i class="fas fa-hourglass-start text-danger mr-2"></i>' . $key['timeLimitConverted'] . '</div>
                        <div class="col-sm-3"><i class="fas fa-pencil-alt text-warning mr-2"></i>' . $key['numberOfAttempts'] . ' lượt làm' . '</div>
                        <div class="col-sm-3"><i class="fas fa-question-circle text-success mr-2"></i>' . $key['numberOfQuestions'] . ' câu hỏi' . '</div>
                    </div>
                </div>';
                };
                if ($str == '')
                    echo '<div class="alert alert-warning">Chưa có đề thi nào trong mục này !</div>';
                else echo $str;
                ?>
            </div>
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
        <div class="right">
            <h4 class="text-uppercase">Thí sinh nổi bật</h4>
            <?php
            if($users){
                echo '<ol>';
                foreach($users as $key){
                    echo '<li><a href="'.Venus\Venus::$baseUrl.'/account/'.$key->id.'">'.$key->lastname.' '.$key->firstname.'</a> ('.$key->number.' lượt)</li>';
                }
                echo '</ol>';
            }
            else {
                echo '<div class="alert alert-info">Chưa có thí sinh nào !</div>';
            }
            ?>
        </div>

    </div>
</div>