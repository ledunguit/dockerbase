<?php
$chart = array(0, 0, 0, 0, 0);
foreach($this->attempts as $key){
    if($key['rank'] == 'Xuất sắc'){
        $chart[0]++;
        continue;
    }
    if($key['rank'] == 'Giỏi'){
        $chart[1]++;
        continue;
    }
    if($key['rank'] == 'Khá'){
        $chart[2]++;
        continue;
    }
    if($key['rank'] == 'Trung bình'){
        $chart[3]++;
        continue;
    }
    if($key['rank'] == 'Yếu'){
        $chart[4]++;
        continue;
    }
}
$timeopen = $this->quiz['timeopen'];
$timeclose = $this->quiz['timeclose'];
$today = date('Y-m-d H:i:s');
if ($timeopen == 'null' && $timeclose == 'null') {
    $classBadge = 'success';
    $status = 'Đang diễn ra';
} else if (($timeopen == null && $timeclose > $today) || (($timeclose == null && $today > $timeopen))) {
    $classBadge = 'success';
    $status = 'Đang diễn ra';
} else if ($today < $timeopen) {
    $classBadge = 'primary';
    $status = 'Chưa diễn ra';
} else if ($timeopen < $today && $today < $timeclose) {
    $classBadge = 'success';
    $status = 'Đang diễn ra';
} else {
    $classBadge = 'danger';
    $status = 'Đã kết thúc';
}
if ($timeopen != null) {
    $timeopen = date_format(date_create($this->quiz['timeopen']), 'd/m/yy H:i:s');
} else {
    $timeopen = 'Không có';
}
if ($timeclose != null) {
    $timeclose = date_format(date_create($this->quiz['timeclose']), 'd/m/yy H:i:s');
} else {
    $timeclose = 'Không có';
}
$attempt = ($this->quiz['attempt']) ? $this->quiz['attempt'] : 'Không giới hạn';
if (isset($this->myAttempts) && count($this->myAttempts) > 0) {
    $average = ($this->myAttempts[0]['average']) ? number_format(round($this->myAttempts[0]['average'], 2), 4) : null;
} else {
    $average = null;
}
if ($this->quiz['password']) {
    $password = '*********';
} else {
    $password = 'Không có';
}
?>
<div class="container-fluid">
    <div class="main-content">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>/dashboard">Bảng điều khiển</a></li>
            <li class="breadcrumb-item active"><a href="<?= \Venus\Venus::$baseUrl ?>/dashboard/myquiz">Quản lý đề
                    thi</a></li>
            <li class="breadcrumb-item active"><a href=""><?php echo $this->quiz['name'] ?></a></li>
        </ul>
        <div class="row d-flex justify-content-center text-danger mt-4"><h2>Quản lý đề thi</h2></div>
        <p class="h4 text-center">Mã đề: <?php echo $this->quiz['code'] ?></p>
        <div class="d-flex justify-content-end mb-3">
            <?php
            if ($this->quiz['visible'] == 1) {
                echo '<div class="p-2"><a href="javascript:setVisible(0,' . $this->quiz['id'] . ')"><button type="button" class="btn btn-danger"><i class="fas fa-eye-slash mr-2"></i>Ẩn đề thi</button></a></div>';
            } else {
                echo '<div class="p-2"><a href="javascript:setVisible(1,' . $this->quiz['id'] . ')"><button type="button" class="btn btn-success"><i class="fas fa-eye mr-2"></i>Hiện đề thi</button></a></div>';
            }
            ?>
            <div class="p-2"><a href="<?= \Venus\Venus::$baseUrl.'/quiz/'.$this->quiz['id'] ?>">
                    <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt mr-2"></i>Câu hỏi</button>
                </a></div>
            <div class="p-2">
                    <button type="button" class="btn btn-danger" id="btnDeleteQuiz"><i class="fas fa-trash-alt mr-2"></i>Xóa đề thi
                    </button>
                </div>
        </div>
        <div class="h4 text-uppercase mt-2"><?php echo $this->quiz['name'] ?></div>
        <?php if ($this->quiz['visible'] == 0) {
            echo '<div class="alert alert-danger"><b><u>Lưu ý</u>: </b>Đề thi đang bị ẩn. Vui lòng cài đặt lại để người khác có thể thấy đề thi của bạn.</div>';
        } ?>
        <div class="row">
            <div class="col-sm-4">
                    <table class="table table-bordered mt-4">
                        <tr>
                            <td class="font-weight-bold">Tổng số câu hỏi:</td>
                            <td><?php echo $this->quiz['numberOfQuestions']; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Thời gian làm bài:</td>
                            <td><?php echo $this->quiz['timeLimitConverted']; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Thời gian mở đề:</td>
                            <td><?php echo $timeopen; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Thời gian đóng đề:</td>
                            <td><?php echo $timeclose; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Trạng thái:</td>
                            <td><span class="badge badge-<?php echo $classBadge . '">' . $status; ?></span></td>
                    </tr>
                    <tr>
                        <td class=" font-weight-bold">Phương thức nộp bài khi hết thời gian:
                            </td>
                            <td><?php echo ($this->quiz['overduehandling'] == 1) ? "Tự động nộp bài" : "Hủy kết quả thi"; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Số lần thử tối đa:</td>
                            <td><?php echo $attempt; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Cách tính điểm:</td>
                            <td><?php
                                if ($this->quiz['grademethod'] == 1) {
                                    echo 'Lần đầu tiên';
                                } else if ($this->quiz['grademethod'] == 2) {
                                    echo 'Lần cuối cùng';
                                } else if ($this->quiz['grademethod'] == 3) {
                                    echo 'Lần cao nhất';
                                } else if ($this->quiz['grademethod'] == 4) {
                                    echo 'Lần thấp nhất';
                                } else if ($this->quiz['grademethod'] == 5) {
                                    echo 'Tính trung bình';
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Cho phép xem đáp án và giải thích:</td>
                            <td><?php echo ($this->quiz['review'] == 1) ? "Cho phép" : "Không cho phép"; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Số câu hỏi mỗi trang:</td>
                            <td><?php echo $this->quiz['questionsperpage']; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Đảo câu:</td>
                            <td><?php echo ($this->quiz['sufflequestion'] == 1) ? "Cho phép" : "Không cho phép"; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Đảo thứ tự đáp án:</td>
                            <td><?php echo ($this->quiz['shuffleanswer'] == 1) ? "Cho phép" : "Không cho phép"; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Mật khẩu:</td>
                            <td><?php echo $password; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Cho phép xem chi tiết:</td>
                            <td><?php echo ($this->quiz['showdetails'] == 1) ? "Cho phép" : "Không cho phép"; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Cho phép khách truy cập:</td>
                            <td><?php echo ($this->quiz['acceptguest'] == 1) ? "Cho phép" : "Không cho phép"; ?></td>
                        </tr>
                    </table>
                </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header font-weight-bold"><i class="fas fa-cog mr-2"></i> Cài đặt</div>
                    <div class="toast" data-autohide="true" id="toast-error">
                        <div class="toast-header">
                            <strong class="mr-auto text-primary" id="toast-error-title">Hệ thống</strong>
                            <small class="text-muted" id="toast-error-time">Now</small>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
                        </div>
                        <div class="toast-body" id="toast-error-message"></div>
                    </div>
                    <div class="toast" data-autohide="true" id="toast-success">
                        <div class="toast-header">
                            <strong class="mr-auto text-primary" id="toast-success-title">Hệ thống</strong>
                            <small class="text-muted" id="toast-success-time">Now</small>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
                        </div>
                        <div class="toast-body" id="toast-success-message"></div>
                    </div>
                    <div class="toast" data-autohide="true" id="toast-primary">
                        <div class="toast-header">
                            <strong class="mr-auto text-primary" id="toast-primary-title">Hệ thống</strong>
                            <small class="text-muted" id="toast-primary-time">Now</small>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
                        </div>
                        <div class="toast-body" id="toast-primary-message"></div>
                    </div>
                    <div class="card-body">
                        <i class="fas fa-pencil-alt mr-2 text-info"></i><a href="<?php echo \Venus\Venus::$baseUrl.'/quiz/'.$this->quiz['id'] ?>">Chỉnh sửa các câu hỏi</a><br>
                        <i class="fas fa-hourglass-half mr-2 text-danger"></i><a href="javascript:changeTimeLimit()">Sửa thời gian làm bài</a><br>
                        <i class="far fa-calendar-plus mr-2 text-secondary"></i><a
                                href="javascript:changeTimeOpenClose()">Thời gian đóng mở đề thi</a><br/>
                        <i class="fas fa-hand-holding mr-2 text-warning"></i><a
                                href="javascript:changeOverduehandling()">Sửa phương thức nộp bài</a><br>
                        <i class="fas fa-user-clock mr-2 text-info"></i><a href="javascript:changeAttemptTimes()">Sửa số
                            lần thử</a><br>
                        <i class="fas fa-graduation-cap mr-2 text-danger"></i><a href="javascript:changeGradeMethod()">Sửa
                            cách tính điểm</a><br>
                        <i class="fas fa-poll-h mr-2 text-warning"></i><a href="javascript:changeReview()">Xem đáp án và
                            giải thích</a><br>
                        <i class="fas fa-sort-numeric-up-alt mr-2 text-secondary"></i><a
                                href="javascript:changeQuestionsPerpage()">Số câu hỏi mỗi trang</a><br>
                        <i class="fas fa-random mr-2 text-danger"></i><a href="javascript:changeShuffleQuestion()">Tính
                            năng trộn câu hỏi</a><br>
                        <i class="fas fa-random mr-2 text-info"></i><a href="javascript:changeShuffleAnswer()">Tính năng
                            trộn đáp án</a><br>
                        <i class="fas fa-lock mr-2 text-danger"></i><a href="javascript:changeQuizPassword()">Cài đặt
                            mật khẩu</a><br>
                        <i class="fas fa-info-circle mr-2 text-warning"></i><a href="javascript:changeShowDetails()">Cho
                            phép xem thống kê đề thi</a><br>
                        <i class="fas fa-user-check mr-2 text-secondary"></i><a href="javascript:changeAcceptGuest()">Cho
                            phép khách truy cập</a><br>
                        <i class="fas fa-inbox mr-2 text-info"></i><a href="javascript:changeNavMethod()">Phương thức
                            nhận kết quả làm bài</a><br>
                    </div>
                    <div class="card-footer">Việc sửa đổi một số chức năng có thể không khả dụng nếu đề thi của bạn đã
                        có người làm.
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header font-weight-bold"><i class="fas fa-award mr-2"></i>Bảng xếp hạng</div>
                    <div class="card-body">
                        <ol>
                            <?php if (isset($this->rank)) {
                                foreach ($this->rank as $key => $value){
                                    foreach ($value as $key2 => $item) {
                                        echo '<li><a href="'.\Venus\Venus::$baseUrl.'/account/'.$key2.'">'.$item[0]. '</a> - <b>'.$item[1].'</b> </li>';
                                    }
                                }
                            } else {
                                echo "Chưa có xếp hạng!";
                            }
                            ?>
                        </ol>
                    </div>
                    <div class="card-footer">Bảng xếp hạng được tính theo điểm của tất cả các lần thử.</div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card mt-4">
                    <div class="card-header font-weight-bold"><i class="fas fa-wrench mr-2"></i>Chức năng khác</div>
                    <div class="card-body">
                        <i class="fas fa-file-pdf mr-2 text-danger"></i><a href="<?=Venus\Venus::$baseUrl?>/quiz/exportresult/<?php echo $this->quiz['id']?>?method=I">Xuất bảng điểm PDF</a><br>
                        <i class="fas fa-download mr-2 text-info"></i><a href="<?=Venus\Venus::$baseUrl?>/quiz/exportresult/<?php echo $this->quiz['id']?>?method=D">Tải xuống bảng điểm</a><br>
                        <i class="fas fa-print mr-2 text-secondary"></i><a href="<?=Venus\Venus::$baseUrl?>/quiz/exportquiz/<?php echo $this->quiz['id']?>?method=I">In đề thi</a><br>
                        <i class="fas fa-arrow-alt-circle-up mr-2 text-primary"></i><a href="javascript:requestPublic(<?=$this->quiz['id']?>)">Yêu cầu công khai đề thi</a><br>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header font-weight-bold"><i class="fas fa-chart-area mr-2"></i>Thống kê</div>
                    <div class="card-body">
                        <i class="fas fa-star mr-2 text-warning"></i><b>Điểm cao nhất: </b> <?=isset($this->grade['highestGrade'])?number_format($this->grade['highestGrade'],2):"0";?> <br>
                        <i class="far fa-star mr-2 text-warning"></i><b>Điểm thấp nhất: </b> <?=isset($this->grade['lowestGrade'])?number_format($this->grade['lowestGrade'],2):"0"?> <br>
                        <i class="fas fa-star-half-alt mr-2 text-warning"></i><b>Điểm trung bình: </b> <?=isset($this->grade['averageGrade'])?number_format($this->grade['averageGrade'],2):"0"?> <br>
                        <canvas id="chart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php
    $n = count($this->attempts);
    $numberOfPage = $n / 20 + 1;
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        $pg = $_GET['page'];
        if ((int)$page > $numberOfPage || (int)$page < 1) {
            $page = 1;
            $pg = 1;
        }
    } else {
        $page = 1;
        $pg = 1;
    }
    $str = '';
    if ($page == 1) {
        $from = 0;
        $end = 20;
    } else {
        $from = 20 * ($page - 1);
        $end = $from + 20;
    }
    ?>
    <div class="main-content">
        <h2>Bảng điểm</h2>
        <p>Danh sách này gồm có <?php echo $n . ' lượt thi' ?></p>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Họ và tên thí sinh / Địa chỉ Email</th>
                    <th>Thời điểm</th>
                    <th>Số điểm</th>
                    <th>Thang điểm 10</th>
                    <th>Xếp loại</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $pageUrl = \Venus\Venus::$baseUrl . '/dashboard/managequiz/' . $this->quiz['id'];
                $str = '';
                $i = 0;
                foreach ($this->attempts as $key) {
                    $i++;
                    if ($i <= $from)
                        continue;
                    if ($i > $end)
                        break;

                    $str .= '<tr id="row-'. $key['id'] .'">
                <td>' . $i . '</td>
                <td>';
                    if ($key['userid'] != null) {
                        $str .= '<a href="' . \Venus\Venus::$baseUrl . '/account/' . $key['userid'] . '">' . $key['fullname'] . '</a>';
                    } else {
                        $str .= $key['guest'] . ' (Khách)';
                    }
                    $str .= '</td>
                <td>' . date_format(date_create($key['timesubmitted']), 'H:i:s d/m/yy') . '</td>
                <td>' . number_format(round($key['grade'], 2), 2) . ' / ' . number_format(round($key['sumgrade'], 2), 2) . '</td>
                <td>' . number_format(round($key['gradeByBaseGrade'], 2), 2) . '</td>
                <td>' . $key['rank'] . '</td>
                <td>
                    <a href="' . \Venus\Venus::$baseUrl . '/attempts/review/' . $key['id'] . '"><button type="button" class="btn btn-info mr-2">Chi tiết</button></a>
                    <a href="javascript:deleteAttempt(' . $key['id'] . ')"><button type="button" class="btn btn-danger">Xóa</button></a>
                </td>
            </tr>';
                }
                echo $str;
                ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-center">
            <ul class="pagination mt-4">
                <li class="page-item"><a class="page-link" href="<?php echo $pageUrl . '?page=';
                    echo ($page <= 1) ? 1 : --$page; ?>">Previous</a></li>
                <?php
                for ($i = 1; $i <= $numberOfPage; $i++) {
                    $active = '';
                    if ($i == $pg) {
                        $active = ' active';
                    }
                    echo '<li class="page-item' . $active . '"><a class="page-link" href="' . $pageUrl . '?page=' . $i . '">' . $i . '</a></li>';
                }
                ?>
                <li class="page-item"><a class="page-link" href="<?php echo $pageUrl . '?page=';
                    echo ($page >= $numberOfPage) ? $numberOfPage : ++$page; ?>">Next</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div id="dialog-timelimit" title="Cài đặt đề thi" style="display: none">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Nhập thời gian làm bài
        (giờ:phút)</p>
    <input type="text" class="form-control" placeholder="Thời gian..." id="quiz-time-limit" name="quiz-time-limit"
           style="width: 30%">
</div>
<div id="dialog-timeopenclose" title="Cài đặt đề thi" style="display: none">
    <div class="form-group">
        <label for="quiz-time-open">Thời gian mở đề:</label>
        <input type="text" class="form-control" placeholder="yyyy-mm-dd HH:mm:ss" id="quiz-time-open"
               name="Quiz[quiz-time-open]"
               style="width: 50%" <?php echo ($this->quiz['timeopen'] == null) ? 'disabled' : ''; ?> value="">
    </div>
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="quiz-enable-timeopen" name="Quiz[quiz-enable-timeopen]"
               onclick="enableSwitch('quiz-enable-timeopen', 'quiz-time-open');"<?php echo ($this->quiz['timeopen'] == null) ? '' : ' checked'; ?>>
        <label class="custom-control-label" for="quiz-enable-timeopen">Mở</label>
    </div>
    <div class="form-group mt-3">
        <label for="quiz-time-close">Thời gian đóng đề:</label>
        <input type="text" class="form-control" placeholder="yyyy-mm-dd HH:mm:ss" id="quiz-time-close"
               name="Quiz[quiz-time-close]"
               style="width: 50%" <?php echo ($this->quiz['timeclose'] == null) ? 'disabled' : ''; ?> value="">
    </div>
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="quiz-enable-timeclose"
               name="Quiz[quiz-enable-timeclose]"
               onclick="enableSwitch('quiz-enable-timeclose', 'quiz-time-close');"<?php echo ($this->quiz['timeclose'] == null) ? '' : ' checked'; ?>>
        <label class="custom-control-label" for="quiz-enable-timeclose">Mở</label>
    </div>
</div>
<div id="dialog-overduehandling" title="Cài đặt đề thi" style="display: none">
    <div class="form-group mt-3">
        <label for="quiz-overduehandling">Sau khi thời gian làm bài kết thúc:</label>
        <select class="form-control" id="overduehandling" name="Quiz[overduehandling]" style="width: 50%">
            <option value="1" <?php echo ($this->quiz['overduehandling'] == 1) ? 'selected' : ''; ?>>Nộp bài tự động
            </option>
            <option value="2"<?php echo ($this->quiz['overduehandling'] == 2) ? 'selected' : ''; ?>>Hủy kết quả làm
                bài
            </option>
        </select>
    </div>
</div>
<div id="dialog-attempttimes" title="Cài đặt đề thi" style="display: none">
    <div class="form-group">
        <label for="quiz-time-attempts">Số lần thử (số lần làm bài tối đa là 10):</label>
        <input type="text" class="form-control" placeholder="Số lần thử..." id="attempts-limit"
               name="Quiz[attempts-limit]" style="width: 30%" value="<?php echo $this->quiz['attempt']; ?>">
    </div>
</div>
<div id="dialog-grademethod" title="Cài đặt đề thi" style="display: none">
    <div class="form-group mt-3">
        <label for="quiz-overduehandling">Cách tính điểm chung của đề thi:</label>
        <select class="form-control" id="quiz-grade-method" name="Quiz[quiz-grade-method]" style="width: 50%">
            <option value="1" <?php echo ($this->quiz['grademethod'] == 1) ? 'selected' : ''; ?>>Lần đầu tiên</option>
            <option value="2" <?php echo ($this->quiz['grademethod'] == 2) ? 'selected' : ''; ?>>Lần cuối cùng</option>
            <option value="3" <?php echo ($this->quiz['grademethod'] == 3) ? 'selected' : ''; ?>>Lần cao nhất</option>
            <option value="4" <?php echo ($this->quiz['grademethod'] == 4) ? 'selected' : ''; ?>>Lần thấp nhất</option>
            <option value="5" <?php echo ($this->quiz['grademethod'] == 5) ? 'selected' : ''; ?>>Điểm trung bình
            </option>
        </select>
    </div>
</div>
<div id="dialog-review" title="Cài đặt đề thi" style="display: none">
    <div class="form-group mt-3">
        <label for="quiz-review">Cho phép xem đáp án và giải thích:</label>
        <select class="form-control" id="quiz-review" name="Quiz[quiz-review]" style="width: 30%">
            <option value="1" <?php echo ($this->quiz['review'] == 1) ? 'selected' : ''; ?>>Có</option>
            <option value="0" <?php echo ($this->quiz['grademethod'] == 0) ? 'selected' : ''; ?>>Không</option>
        </select>
    </div>
</div>
<div id="dialog-questionsperpage" title="Cài đặt đề thi" style="display: none">
    <div class="form-group mt-3">
        <label for="quiz-grade-method">Số câu hỏi mỗi trang:</label>
        <select class="form-control" id="quiz-questions-per-page" name="Quiz[quiz-question-per-page]"
                style="width: 20%">
            <?php for ($i = 1; $i <= 50; $i++) {
                if ($this->quiz['questionsperpage'] == $i) {
                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                } else {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                }
            }
            ?>
        </select>
    </div>
</div>
<div id="dialog-shufflequestion" title="Cài đặt đề thi" style="display: none">
    <div class="form-check mb-3">
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" id="quiz-random-question"
                   name="Quiz[quiz-random-question]"<?php echo ($this->quiz['sufflequestion'] == 1) ? ' checked' : ''; ?>>Cho
            phép xáo trộn vị trí câu hỏi
        </label>
    </div>
</div>
<div id="dialog-shuffleanswer" title="Cài đặt đề thi" style="display: none">
    <div class="form-check mb-3">
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" id="quiz-random-question-answer"
                   name="Quiz[quiz-random-question-answer]"<?php echo ($this->quiz['shuffleanswer'] == 1) ? ' checked' : ''; ?>>Cho
            phép đảo vị trí đáp án trong câu hỏi
        </label>
    </div>
</div>
<div id="dialog-quizpassword" title="Cài đặt đề thi" style="display: none">
    <div class="form-group mt-3">
        <label for="quiz-password">Mật khẩu truy cập:</label>
        <input type="password" class="form-control" placeholder="Nhập mật khẩu truy cập..." id="quiz-password"
               name="Quiz[quiz-password]" style="width: 80%" <?php echo ($password == 'Không có') ? 'disabled' : ''; ?>
               value="<?php echo ($password == 'Không có') ? '' : '******'; ?>">
    </div>
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="quiz-enable-pwd" name="Quiz[quiz-enable-pwd]"
               onclick="enableSwitch('quiz-enable-pwd', 'quiz-password');"<?php echo ($password == 'Không có') ? '' : 'checked'; ?>>
        <label class="custom-control-label" for="quiz-enable-pwd">Bảo vệ đề bằng mật khẩu ?</label>
    </div>
</div>
<div id="dialog-showdetails" title="Cài đặt đề thi" style="display: none">
    <div class="form-check mb-3">
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" id="quiz-showdetails"
                   name="Quiz[quiz-showdetails]"<?php echo ($this->quiz['showdetails'] == 1) ? ' checked' : ''; ?>>Cho
            phép người dùng và khách xem chi tiết thống kê của đề thi
        </label>
    </div>
</div>
<div id="dialog-acceptguest" title="Cài đặt đề thi" style="display: none">
    <div class="form-check mb-3">
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input" id="quiz-accept-guest"
                   name="Quiz[quiz-accept-guest]"<?php echo ($this->quiz['acceptguest'] == 1) ? ' checked' : ''; ?>>Cho
            phép khách truy cập
        </label>
    </div>
</div>
<div id="dialog-navmethod" title="Cài đặt đề thi" style="display: none">
    <div class="form-group mt-3">
        <label for="quiz-result-method">Phương thức nhận kết quả:</label>
        <select class="form-control" id="quiz-result-method" name="Quiz[quiz-result-method]" style="width: 80%">
            <option value="1"<?php echo ($this->quiz['navmethod'] == 1) ? ' selected' : ''; ?>>Ngay sau khi làm bài
            </option>
            <option value="2"<?php echo ($this->quiz['navmethod'] == 2) ? ' selected' : ''; ?>>Khi đề thi đóng</option>
            <option value="3"<?php echo ($this->quiz['navmethod'] == 3) ? ' selected' : ''; ?>>Nhận qua email khi làm
                bài xong
            </option>
        </select>
    </div>
</div>
<div id="dialog-delete-quiz" title="Cài đặt đề thi" style="display: none">
    <p>Bạn có chắc chắn muốn <b style="color: red;">xóa</b> đề thi này?</p>
</div>
<div id="dialog-hide" title="Cài đặt đề thi" style="display: none">
    <p>Bạn có chắc chắn muốn <b>ẩn</b> đề thi này?</p>
</div>
<div id="dialog-unhide" title="Cài đặt đề thi" style="display: none">
    <p>Bạn có chắc chắn muốn <b>hiển thị</b> đề thi này?</p>
</div>
<script>
    function deleteAttempt(id) {
        var dataInput = {};
        dataInput['attemptid'] = id;
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/ajax/deleteAttempt',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function (response) {
                var res = JSON.parse(response);
                if (res.status == 'success') {
                    $("#row-" + id).hide(500);
                } else {
                    alert('Không thể xóa attempt vừa chọn do lỗi hệ thống. Vui lòng liên hệ quản trị.');
                }
            },
            fail: function() {

            }
        });
    }

    function changeTimeLimit() {
        $("#dialog-timelimit").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    //call ajax to update time limit
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    dataInput['newtime'] = $("#quiz-time-limit").val();
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateTimeLimit',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response);
                            if (res.status != "Cập nhật thành công!") {
                                $("#toast-error-message").html(res.status);
                                $("#toast-error").toast({ delay: 5000});
                                $("#toast-error").toast('show');
                            } else {
                                $("#toast-success-message").html(res.status + " Vui lòng reset trang để cập nhật thông tin!");
                                $("#toast-success").toast({ delay: 5000});
                                $("#toast-success").toast('show');
                                $("#dialog-timelimit").dialog("close");
                            }
                        }
                    });
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeTimeOpenClose() {
        $("#dialog-timeopenclose").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    if ($("#quiz-enable-timeopen").prop("checked") == true) {
                        dataInput['timeopen'] = $("#quiz-time-open").val();
                    }
                    if ($("#quiz-enable-timeclose").prop("checked") == true) {
                        dataInput['timeclose'] = $("#quiz-time-close").val();
                    }
                    if (dataInput['timeopen'] == undefined && dataInput['timeclose'] == undefined) {
                        dataInput['delete'] = 'Yes';
                    }
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateTimeOpenAndClose',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                            $("#dialog-timeopenclose").dialog("close");
                        }
                    });
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeOverduehandling() {
        $("#dialog-overduehandling").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    dataInput['method'] = $("#overduehandling").val();
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateOverdueHandleMethod',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                            $("#dialog-overduehandling").dialog("close");
                        }
                    });
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeAttemptTimes() {
        $("#dialog-attempttimes").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    dataInput['numberOfAttempt'] = $("#attempts-limit").val();
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateNumberOfAttempts',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeGradeMethod() {
        $("#dialog-grademethod").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    dataInput['gradeMethod'] = $("#quiz-grade-method").val();
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateGradeMethod',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeReview() {
        $("#dialog-review").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    dataInput['quizReview'] = $("#quiz-review").val();
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateReviewSetting',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeQuestionsPerpage() {
        $("#dialog-questionsperpage").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    dataInput['quesPerPage'] = $("#quiz-questions-per-page").val();
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateQuestionPerpage',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeShuffleQuestion() {
        $("#dialog-shufflequestion").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    if ($("#quiz-random-question").prop("checked") == true) {
                        dataInput['suffleQues'] = 1;
                    } else {
                        dataInput['suffleQues'] = 0;
                    }
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateSuffleQuestionSetting',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeShuffleAnswer() {
        $("#dialog-shuffleanswer").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    if ($("#quiz-random-question-answer").prop("checked") == true) {
                        dataInput['suffleAnswer'] = 1;
                    } else {
                        dataInput['suffleAnswer'] = 0;
                    }
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateSuffleAnswerSetting',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeQuizPassword() {
        $("#dialog-quizpassword").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    if ($("#quiz-enable-pwd").prop("checked") == true) {
                        dataInput['password'] = $("#quiz-password").val();
                    } else {
                        dataInput['password'] = "remove";
                    }
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateQuizPassword',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeShowDetails() {
        $("#dialog-showdetails").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    if ($("#quiz-showdetails").prop("checked") == true) {
                        dataInput['showDetails'] = 1;
                    } else {
                        dataInput['showDetails'] = 0;
                    }
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateDetailSetting',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeAcceptGuest() {
        $("#dialog-acceptguest").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    if ($("#quiz-accept-guest").prop("checked") == true) {
                        dataInput['acceptGuest'] = 1;
                    } else {
                        dataInput['acceptGuest'] = 0;
                    }
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateAcceptGuestSetting',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function changeNavMethod() {
        $("#dialog-navmethod").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Lưu": function () {
                    var dataInput = {};
                    dataInput['quizid'] = <?=$this->quiz['id']?>;
                    dataInput['navMethod'] = $("#quiz-result-method").val();
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/updateReceiveReviewSetting',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function (response) {
                            var res = JSON.parse(response).status;
                            $("#toast-primary-message").html(res);
                            $("#toast-primary").toast({ delay: 5000});
                            $("#toast-primary").toast('show');
                        }
                    });
                    $(this).dialog("close");
                },
                "Hủy bỏ": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function setVisible(visible, quizId) {
        if (visible == 0) {
            $("#dialog-hide").dialog({
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "Lưu": function () {
                        $.post("/ajax/setVisibleForQuiz", {'id': quizId, 'visible': 0}, function (data) {
                            if (data == "ok") {
                                location.reload();
                            } else {
                                alert('Đã xảy ra lỗi. Vui lòng thử lại !')
                            }
                            $('#dialog-hide').dialog("close");
                        })
                    },
                    "Hủy bỏ": function () {
                        $('#dialog-hide').dialog("close");
                    }
                }
            });
        } else {
            $("#dialog-unhide").dialog({
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "Lưu": function () {
                        $.post("/ajax/setVisibleForQuiz", {'id': quizId, 'visible': 1}, function (data) {
                            if (data == "ok") {
                                location.reload();
                            } else {
                                alert('Đã xảy ra lỗi. Vui lòng thử lại !')
                            }
                            $('#dialog-unhide').dialog("close");
                        })
                    },
                    "Hủy bỏ": function () {
                        $('#dialog-unhide').dialog("close");
                    }
                }
            });
        }
    }

    $("#btnDeleteQuiz").click(function() {
        $("#dialog-delete-quiz").dialog({
            resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "Xóa": function () {
                        dataInput = {};
                        dataInput['quizid'] = window.location.pathname.split('/')[3];
                        $.ajax({
                            type: 'POST',
                            cache: false,
                            url: '/ajax/deleteQuizById',
                            data: {dataAjax: JSON.stringify(dataInput)},
                            success: function (res) {
                                var resj = JSON.parse(res).status;
                                if (resj == 'success') {
                                    window.location.href = '/dashboard/myquiz';
                                } else {
                                    alert("Lỗi hệ thống, vui lòng thử lại sau!");
                                }
                            }
                        });
                        $('#dialog-delete-quiz').dialog("close");
                    },
                    "Hủy bỏ": function () {
                        $('#dialog-delete-quiz').dialog("close");
                    }
                }
        });
    })

    function enableSwitch(switchTag, inputTag) {
        var tag = document.getElementById(inputTag);
        if (document.getElementById(switchTag).checked)
            tag.removeAttribute('disabled');
        else
            tag.disabled = true;
    }

    function currentDate() {
        var currentdate = new Date();
        var datetime = currentdate.getFullYear() + "/"
                    + (currentdate.getMonth()+1)  + "/"
                    + currentdate.getDate();
        return datetime;
    }

    function currentDateClose() {
        var currentdate = new Date();
        var datetime = currentdate.getFullYear() + "/"
                    + (currentdate.getMonth()+1)  + "/"
                    + (currentdate.getDate()+7);
        return datetime;
    }

    function currentTime() {
        var currentdate = new Date();
        var datetime = currentdate.getHours() + ":"
                    + currentdate.getMinutes();
        return datetime;
    }

    function currentDateTime() {
        var currentdate = new Date();
        var datetime = currentdate.getFullYear() + "/"
                    + (currentdate.getMonth()+1)  + "/"
                    + currentdate.getDate() + ' '
                    + currentdate.getHours() + ":"
                    + currentdate.getMinutes();
        return datetime;
    }

    function currentCloseDateTime() {
        var currentdate = new Date();
        var datetime = currentdate.getFullYear() + "/"
                    + (currentdate.getMonth()+1)  + "/"
                    + (currentdate.getDate()) + ' '
                    + currentdate.getHours() + ":"
                    + currentdate.getMinutes();
        return datetime;
    }

    function convertTimeDatabaseOpen() {
        var time = '<?=isset($this->quiz['timeopen']) && $this->quiz['timeopen'] !== '' ? $this->quiz['timeopen'] : (new \DateTime())->format('Y-m-d H:i:s') ?>';
        if (time !== undefined && time !== null) {
            time = time.split(' ');
            time[1] = time[1].split(':')
            return time[0] + ' ' + time[1][0] + ':' + time[1][1];
        }
        return null;
    }

    function convertTimeDatabaseClose() {
        var time = '<?=isset($this->quiz['timeclose']) && $this->quiz['timeclose'] !== '' ? $this->quiz['timeclose'] : (new \DateTime())->format('Y-m-d H:i:s') ?>';
        if (time !== undefined && time !== null) {
            time = time.split(' ');
            time[1] = time[1].split(':')
            return time[0] + ' ' + time[1][0] + ':' + time[1][1];
        }
        return null;
    }

    $('#quiz-time-open').datetimepicker({
        formatTime:'H:i',
        formatDate:'Y/m/d',
        step: 10,
        minDate: currentDate(),
        maxDate: currentDateClose(),
        minTime: currentTime(),
        value: convertTimeDatabaseOpen()
    });
    $('#quiz-time-close').datetimepicker({
        formatTime:'H:i',
        formatDate:'Y/m/d',
        step: 10,
        minDate: currentDate(),
        minTime: currentTime(),
        value: convertTimeDatabaseClose()
    });
    $('#quiz-time-limit').datetimepicker({
        datepicker:false,
        format:'H:i',
        step:5,
        minTime: '00:05',
        maxTime: '02:05',
        value: '00:30'
    });
    var ctx = document.getElementById('chart');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Xuất sắc', 'Giỏi', 'Khá', 'Trung bình', 'Yếu'],
            datasets: [{
                label: 'Tỉ lệ',
                data: <?php echo json_encode($chart);?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                ],
            }]
        },
    });

    function requestPublic(id) {
        dataInput = {};
        dataInput['quizid'] = id;
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/ajax/requestPublic',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function (res) {
                var resj = JSON.parse(res).status;
                if (resj == 'success') {
                    alert("Đã yêu cầu công khai đề thi thành công.");
                } else {
                    alert("Lỗi hệ thống, vui lòng thử lại sau!");
                }
            }
        });
    }
</script>