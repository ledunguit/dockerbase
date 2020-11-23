<?php
$quiz = $this->quizDetails;
$attempt = $this->attempt;
$questions = $this->questions;
$myChoice = $this->chose;
?>
<div class="container-fluid">
    <div class="main-content">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a
                        href="<?= \Venus\Venus::$baseUrl . '/categories/' . $quiz['categoryShortName'] ?>"><?php echo $this->quizDetails['categoryName']; ?></a>
            </li>
            <li class="breadcrumb-item"><a
                        href="<?= \Venus\Venus::$baseUrl . '/quiz/enroll/' . $quiz['id'] ?>"><?php echo $quiz['name'] ?></a>
            </li>
            <li class="breadcrumb-item active"><a href="">Xem kết quả</a></li>
        </ul>
        <h2>Kết quả thi</h2>
        <div class="row d-flex justify-content-center col-sm-6">
            <table class="table table-bordered mt-4">
                <tr class="alert-primary">
                    <td class="font-weight-bold">Thí sinh:</td>
                    <?php if (isset($attempt['userid'])) {
                        echo '<td><a href="' . \Venus\Venus::$baseUrl . '/account/' . $attempt['userid'] . '">' . $attempt['fullname'] . '</a></td>';
                    } else {
                        echo '<td>' . $attempt['guest'] . ' (khách)</td>';
                    } ?>
                </tr>
                <tr>
                    <td class="font-weight-bold" style="width: 30%">Tên đề thi:</td>
                    <td class="font-weight-bold text-uppercase"><?php echo $quiz['name']; ?></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Tạo bởi:</td>
                    <td>
                        <a href="<?= \Venus\Venus::$baseUrl . '/account/' . $quiz['createdby'] ?>"><?php echo $quiz['createdByName'] ?></a>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Danh mục:</td>
                    <td class="font-weight-bold text-uppercase"><a
                                href="<?= \Venus\Venus::$baseUrl . '/categories/' . $quiz['categoryShortName'] ?>"><?php echo $this->quizDetails['categoryName']; ?></a>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Lần thi thứ:</td>
                    <td class=""><?php echo $attempt['attemptSequence']; ?></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Bắt đầu làm bài:</td>
                    <td class=""><?php echo date_format(date_create($attempt['timestarted']), 'd/m/Y H:i:s'); ?></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Thời gian nộp bài:</td>
                    <td class=""><?php echo date_format(date_create($attempt['timesubmitted']), 'd/m/Y H:i:s'); ?></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Tổng thời gian:</td>
                    <td><?php
                        if ($attempt['duration']['hours'] >= 0) {
                            echo $attempt['duration']['hours'] . ' giờ ';
                        }
                        if ($attempt['duration']['minutes'] >= 0) {
                            echo $attempt['duration']['minutes'] . ' phút ';
                        }
                        if ($attempt['duration']['seconds'] >= 0) {
                            echo $attempt['duration']['seconds'] . ' giây ';
                        } ?>
                    </td>
                </tr>
                <tr <?php echo ($attempt['percentage'] < 50) ? 'class="alert-danger"' : 'class="alert-info"' ?>>
                    <td class="font-weight-bold">Điểm:</td>
                    <td class=""><?php echo number_format($attempt['grade'], 2) . ' / ' . number_format($attempt['sumgrade'], 2); ?></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Tỉ lệ đúng:</td>
                    <td class=""><?php echo $attempt['percentage'] . ' %'; ?></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Xếp loại:</td>
                    <td class=""><?php echo $attempt['rank']; ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="main-content">
        <h2>Đáp án và giải thích</h2>
        <?php
        if ($quiz['review'] == 0) {
            echo '<div class="alert alert-warning">Đề thi không cho phép xem đáp án!</div>';
        } else {
            if (!isset($attempt['userid'])) {
                if ($quiz['navmethod'] == 3) {
                    echo '<div class="alert alert-warning">Vui lòng kiểm tra hộp thư Email của bạn để nhận kết quả !</div>';
                    return;
                }
            }
            $n = 0;
            $str = '';
            foreach ($questions as $key) {
                $n++;
                $t = count($key['answers']);
                for ($i = 0; $i < $t; $i++) {
                    if ($key['answers'][$i]['fraction'] == 0)
                        continue;
                    if (!is_array($myChoice[$n - 1]['chose'])) {
                        if ($key['answers'][$i]['id'] == $myChoice[$n - 1]['chose']) {
                            $stt = 'Đúng';
                            $classText = 'success';
                        } else {
                            $stt = 'Sai';
                            $classText = 'danger';
                        }
                    } else {
                        $flag = false;
                        foreach ($myChoice[$n - 1]['chose'] as $item) {
                            if ($item == $key['answers'][$i]['id']) {
                                $flag = true;
                                continue;
                            }
                            $flag = false;
                        }
                        if ($flag == true) {
                            $stt = 'Đúng';
                            $classText = 'success';
                        } else {
                            $stt = 'Sai';
                            $classText = 'danger';
                        }
                    }

                }
                $mark = $myChoice[$n - 1]['grade'];
                $str .= '
            <div class="row">
            <div class="col-sm-2 text-center">
                <div class="h5 alert-' . $classText . ' p-2">Câu hỏi số ' . $n . '</div><p class="text-' . $classText . ' font-weight-bold">' . $stt . '</p>
                <p><b>Điểm: </b> ' . number_format(round($mark, 2), 2) . '</b></p>
                <hr>';
                $str .= '</div>
            <div class="col-sm-10 question-text">
                <p>' . htmlspecialchars_decode($key['questiontext']) . '</p>';
                $result = '';
                $i = 0;
                $type = 'radio';
                if ($key['type'] == 2) {
                    $type = 'checkbox';
                }
                foreach ($key['answers'] as $answer) {
                    $answer['answer'] = htmlspecialchars_decode($answer['answer']);
                    $str .= '<div class="form-check">
                        <label class="form-check-label">
                            <input type="' . $type . '" class="form-check-input" name="optradio-' . $answer['id'] . '"';
                    if (!is_array($myChoice[$n - 1]['chose'])) {
                        if ($answer['id'] == $myChoice[$n - 1]['chose']) {
                            $str .= ' checked';
                        }
                    } else {
                        foreach ($myChoice[$n - 1]['chose'] as $item) {
                            if ($answer['id'] == $item) {
                                $str .= ' checked';
                            }
                        }
                    }
                    $str .= ' disabled><span class="';
                    if ($answer['fraction'] == 0 && $answer['id'] == $myChoice[$n - 1]['chose']) {
                        $str .= 'text-danger">' . $answer['answer'] . '</span></label></div>';
                    } else
                        if ($answer['fraction'] > 0) {
                            if ($type == 'radio') {
                                $result = $answer['answer'];
                            } else {
                                $result .= $answer['answer'];
                            }
                            $temp = $answer['id'];
                            $str .= 'text-success">' . $answer['answer'] . '</span>
                        </label></div>';
                        } else {
                            $str .= 'text-body">' . $answer['answer'] . '</span>
                        </label></div>';
                        }
                    $i++;
                }
                $str .= '<p class="mt-3"><b>Đáp án: </b><div class="alert-secondary p-2 col-sm-6">' . htmlspecialchars_decode($result) . '</div></p>
                <p><b>Giải thích:</b> ' . htmlspecialchars_decode($key['feedback']) . '</p><hr>
            </div>
        </div>';
            }
            echo $str;
        }
        ?>
    </div>
</div>
