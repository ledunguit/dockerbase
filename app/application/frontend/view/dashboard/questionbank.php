<?php
$n = count($this->questions);
$numberOfPage = $n / 50 + 1;
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
    $end = 50;
} else {
    $from = 50 * ($page - 1);
    $end = $from + 50;
}
$pageUrl = \Venus\Venus::$baseUrl . '/dashboard/questionbank';
$str = '';
$i = 0;
?>
<div class="container-fluid">
    <div class="main-content">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>/dashboard">Bảng điều khiển</a></li>
            <li class="breadcrumb-item active"><a href="">Ngân hàng câu hỏi cá nhân</a></li>
        </ul>
        <h2 class="d-flex justify-content-center text-danger">Ngân hàng câu hỏi</h2>
        <p>Tổng hợp tất cả các câu hỏi từ các đề thi của bạn.</p>
        <div class="row d-flex justify-content-center"><input class="form-control mb-4 col-sm-5" id="myInput"
                                                              type="text" placeholder="Nhập từ khóa.."></div>
        <?php if (isset($this->success)): ?>
            <div class="alert alert-success"><?=$this->success?></div></br>
        <?php elseif (isset($this->failed)): ?>
            <div class="alert alert-danger"><?=$this->failed?></div></br>
        <?php endif; ?>
        <a>
            <button type="button" class="btn btn-success mr-3" data-toggle="modal" data-target="#myModal"><i class="fas fa-plus mr-2"></i>Thêm câu hỏi</button>
        </a>
        <a href="javascript:deleteManyQues()">
            <button type="button" class="btn btn-danger"><i class="fas fa-trash-alt mr-2"></i>Xóa</button>
        </a>
        <div class="table-responsive">
            <table id="myTable" class="table table-bordered mt-3">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Tên câu hỏi</th>
                    <th>Loại</th>
                    <th>Điểm số</th>
                    <th>Ghi chú</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $str = '';
                $i = 0;
                foreach ($this->questions as $key) {
                    $i++;
                    if ($i <= $from)
                        continue;
                    if ($i > $end)
                        break;
                    $class = 'odd';
                    if ($i % 2 == 0) {
                        $class = 'even';
                    }
                    $str .= '<tr class="' . $class . '" id="row-'. $key['id'] .'">
                <td>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="checkbox" id="ques-'. $i .'" class="form-check-input" value="'. $key['id'] . '">' . $i . '
                        </label>
                    </div>
                </td>
                <td>' . $key['name'] . '
                </td>
                <td>';
                    if ($key['type'] == 1) {
                        $str .= '<i class="far fa-check-circle"></i>';
                    } else {
                        $str .= '<i class="far fa-check-square"></i>';
                    }
                    $str .= '</td>
                <td>' . number_format(round($key['defaultgrade'], 2), 2) . '</td><td>';
                    if ($key['isused'] == 'X') {
                        $str .= '<span class="badge badge-danger">Đã sử dụng</span>';
                    }
                    else {
                        $str .= '<span class="badge badge-success" id="isuse-'. $i .'">Chưa sử dụng</span>';
                    }
                    $str .= '</td>
                <td>';
                    if ($key['canBeChanged']) {
                        $str .= '<a href="javascript:editQuestion('.$i.')">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editQuestion">Chỉnh sửa</button>
                    </a><a href="javascript:deleteQuestion(' . $key['id'] . ')">
                        <button type="button" class="btn btn-danger ml-2">Xóa</button>
                    </a>';
                    }
                    else {
                        $str .= '<button type="button" class="btn btn-info" disabled>Chỉnh sửa</button>
                        <button type="button" class="btn btn-danger ml-2" disabled>Xóa</button>';
                    }

                    $str .= '</td>
            </tr>';
                }
                echo $str; ?>
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
        <p class="font-weight-bold">Thêm các câu hỏi được chọn vào đề thi của bạn:</p>
        <div class="alert alert-danger" id="assign-error" style="display: none;">Alo alo</div>
        <div class="alert alert-success" id="assign-success" style="display: none;">Alo alo</div>
        <div class="form-group">
            <label for="sel1" class="text-danger"><b>Lưu ý:</b> Chỉ thêm được vào những đề thi chưa có người nào thực
                hiện !</label>
            <select class="form-control" id="sel1" style="width:60%">
                <?php foreach ($this->simpleList as $key) {
                    echo '<option value="' . $key->id . '">' . $key->name . '</option>';
                }
                ?>
            </select>
            <div class="form-group mt-3">
                <label for="usr">Điểm số (để trống nếu lấy mặc định):</label>
                <input type="text" class="form-control" id="grade" style="width:20%" placeholder="1.00" value="1.00">
            </div>
        </div>
        <div><a>
            <button type="button" class="btn btn-info ml-2" id="btnAssignQues">Thêm</button>
        </a></div></br>
    </div>
</div>
<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thêm câu hỏi mới</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <form method="post" id="form-add-answer">
                    <div class="form-group">
                        <label for="q-name">Tên câu hỏi (tối đa 255 ký tự - có thể để trống):</label>
                        <textarea class="form-control" rows="2" id="q-name" name="Ques[name]"
                                  placeholder="Nhập tên của câu hỏi..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="q-text">Nội dung câu hỏi:</label>
                        <textarea class="form-control" rows="5" id="q-text" name="Ques[text]"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="q-feedback">Giải thích:</label>
                        <textarea class="form-control" rows="5" id="q-feedback" name="Ques[feedback]"></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label for="q-grade">Điểm số của câu hỏi:</label>
                        <input type="text" class="form-control" value="1.00000" id="q-grade"
                               name="Ques[grade]" style="width: 30%">
                    </div>
                    <div class="form-group mt-3">
                        <label for="q-num-answers">Số đáp án:</label>
                        <select class="form-control" id="q-num-answers" name="Ques[num-answers]" style="width: 30%"
                                onchange="checkNumberOfAnswers()">
                            <?php for ($i = 2; $i <= 8; $i++)
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            ?>
                        </select>
                    </div>

                    <?php
                    for ($i = 1; $i <= 8; $i++) {
                        $html = '<div id="ans-container-' . $i . '" ';
                        if ($i > 2)
                            $html .= 'style="display:none"';
                        $html .= '><div class="form-group">
                        <label for="q-text">Đáp án ' . $i . '</label>
                        <textarea class="form-control" rows="5" id="answer-' . $i . '" name="Ques[answer-' . $i . ']"></textarea>
                    </div><div class="form-check mb-3">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="ans-grade-' . $i . '"
                                   name="Ques[ans-grade-' . $i . ']">Đây là đáp án đúng
                        </label>
                    </div></div>';
                        echo $html;
                    }
                    ?>
                    <button type="submit" class="btn btn-primary" id="btnAddQues">Thêm</button>
                    <button type="submit" class="btn btn-danger ml-4" onclick="resetForm();">Xóa bỏ</button>
                </form>
                <div class="toast" data-autohide="true" id="toast-quiz-error">
                    <div class="toast-header">
                        <strong class="mr-auto text-primary" id="toast-error-title">Hệ thống</strong>
                        <small class="text-muted" id="toast-error-time">Now</small>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
                    </div>
                    <div class="toast-body" id="toast-quiz-error-message"></div>
                </div>
                <div class="toast" data-autohide="true" id="toast-quiz-success">
                    <div class="toast-header">
                        <strong class="mr-auto text-primary" id="toast-success-title">Hệ thống</strong>
                        <small class="text-muted" id="toast-success-time">Now</small>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
                    </div>
                    <div class="toast-body" id="toast-quiz-success-message"></div>
                </div>
                <div class="toast" data-autohide="true" id="toast-quiz-primary">
                    <div class="toast-header">
                        <strong class="mr-auto text-primary" id="toast-primary-title">Hệ thống</strong>
                        <small class="text-muted" id="toast-primary-time">Now</small>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
                    </div>
                    <div class="toast-body" id="toast-quiz-primary-message"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="editQuestion">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chỉnh sửa câu hỏi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <form action="#" method="post" id="form-edit-answer">
                    <div class="form-group">
                        <label for="edit-q-name">Tên câu hỏi (tối đa 255 ký tự - có thể để trống):</label>
                        <textarea class="form-control" rows="2" id="edit-q-name" name="QuizEdit[name]"
                                  placeholder="Nhập tên của câu hỏi..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-q-text">Nội dung câu hỏi:</label>
                        <textarea class="form-control" rows="5" id="edit-q-text" name="QuizEdit[text]"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-q-feedback">Giải thích:</label>
                        <textarea class="form-control" rows="5" id="edit-q-feedback" name="QuizEdit[feedback]"></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label for="edit-q-grade">Điểm số của câu hỏi:</label>
                        <input type="text" class="form-control" id="edit-q-grade"
                               name="QuizEdit[grade]" style="width: 30%">
                    </div>
                    <div class="form-group mt-3">
                        <label for="edit-q-num-answers">Số đáp án:</label>
                        <select class="form-control" id="edit-q-num-answers" name="QuizEdit[num-answers]"
                                style="width: 30%"
                                onchange="checkNumberOfAnswersToEdit()">
                            <?php for ($i = 2; $i <= 8; $i++)
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            ?>
                        </select>
                    </div>
                    <?php
                    for ($i = 1; $i <= 8; $i++) {
                        $html = '<div id="edit-ans-container-' . $i . '" ';
                        if ($i > 2)
                            $html .= 'style="display:none"';
                        $html .= '><div class="form-group">
                        <label for="q-text">Đáp án ' . $i . '</label>
                        <textarea class="form-control" rows="5" id="edit-answer-' . $i . '" name="QuizEdit[answer-' . $i . ']"></textarea>
                    </div><div class="form-check mb-3">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="edit-ans-grade-' . $i . '"
                                   name="QuizEdit[edit-ans-grade-' . $i . ']">Đây là đáp án đúng
                        </label>
                    </div></div>';
                        echo $html;
                    }
                    ?>
                    <input type="button" class="btn btn-primary" name="btnEditQues" id="btnEditQues" value="Cập nhật"></input>
                    <input type="button" class="btn btn-danger ml-4" onclick="resetFormEdit();" value="Xóa bỏ"></input>
                </form>
                <div class="toast" data-autohide="true" id="toast-edit-quiz-error">
                    <div class="toast-header">
                        <strong class="mr-auto text-primary" id="toast-edit-quiz-error-title">Hệ thống</strong>
                        <small class="text-muted" id="toast-edit-quiz-error-time">Now</small>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
                    </div>
                    <div class="toast-body" id="toast-edit-quiz-error-message"></div>
                </div>
                <div class="toast" data-autohide="true" id="toast-edit-quiz-success">
                    <div class="toast-header">
                        <strong class="mr-auto text-primary" id="toast-edit-quiz-success-title">Hệ thống</strong>
                        <small class="text-muted" id="toast-edit-quiz-success-time">Now</small>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
                    </div>
                    <div class="toast-body" id="toast-edit-quiz-success-message"></div>
                </div>
                <div class="toast" data-autohide="true" id="toast-edit-quiz-primary">
                    <div class="toast-header">
                        <strong class="mr-auto text-primary" id="toast-edit-quiz-primary-title">Hệ thống</strong>
                        <small class="text-muted" id="toast-primary-time">Now</small>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
                    </div>
                    <div class="toast-body" id="toast-edit-quiz-primary-message"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    CKEDITOR.replace('edit-q-text', {
        extraPlugins: 'uploadimage'
    });

    CKEDITOR.replace('edit-q-feedback', {
        extraPlugins: 'uploadimage',
    });
    for (var i = 1; i <= 8; i++) {
        CKEDITOR.replace('edit-answer-' + i, {
            extraPlugins: 'uploadimage',
        });
    }
    function rhtmlspecialchars(str) {
        if (typeof (str) == "string") {
            str = str.replace(/&gt;/ig, ">");
            str = str.replace(/&lt;/ig, "<");
            str = str.replace(/&#039;/g, "'");
            str = str.replace(/&quot;/ig, '"');
            str = str.replace(/&amp;/ig, '&');
        }
        return str;
    }

    function deleteManyQues() {
        var countQues = <?=count($this->questions);?>;
        for (var i = 1; i <= countQues; i++) {
            if ($("#ques-" + i).prop('checked') == true && questions[i].isused != 'X') {
                var result = deleteQuestion($('#ques-' + i).attr('value'));
            }
        }
        setTimeout(() =>{window.location.href = '/dashboard/questionbank'}, 1000);
    }

    function deleteQuestion(id) {
        var dataInput = {};
        dataInput['questionid'] = id;
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/ajax/deleteQuestionBank',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function(res) {
                if (JSON.parse(res).status == 'success') {
                    $("#row-" + id).hide('slow');
                    return true;
                } else {
                    return false;
                }
            },
            fail: function(res) {
                console.log(res);
            }
        });
    };

    function checkNumberOfAnswers() {
        var n = document.getElementById('q-num-answers').value;
        for (var i = 1; i <= 8; i++) {
            document.getElementById('ans-container-' + i).style = "display:none";
        }
        for (var i = 1; i <= n; i++) {
            document.getElementById('ans-container-' + i).removeAttribute('style');
        }
    }

    function checkNumberOfAnswersToEdit() {
        var n = document.getElementById('edit-q-num-answers').value;
        for (var i = 1; i <= 8; i++) {
            document.getElementById('edit-ans-container-' + i).style = "display:none";
        }
        for (var i = 1; i <= n; i++) {
            document.getElementById('edit-ans-container-' + i).removeAttribute('style');
        }
    }

    function resetForm() {
        document.getElementById('form-add-answer').reset();
    }
    $(document).ready(function () {
        $("#myInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
    CKEDITOR.replace('q-text', {
        extraPlugins: 'uploadimage'
    });
    CKEDITOR.replace('q-feedback', {
        extraPlugins: 'uploadimage'
    });
    for (var i = 1; i <= 8; i++) {
        CKEDITOR.replace('answer-' + i, {
            extraPlugins: 'uploadimage'
        });
    }
    var currentEditQues = 0;
    var questions = Object.assign([], <?php echo json_encode($this->questions); ?>);
    function editQuestion(id) {
        var questionId = questions[id - 1].id;
        currentEditQues = questionId;
        $('#edit-q-name').val(questions[id - 1].name);
        CKEDITOR.instances['edit-q-text'].setData(rhtmlspecialchars(questions[id - 1].questiontext));
        CKEDITOR.instances['edit-q-feedback'].setData(rhtmlspecialchars(questions[id - 1].feedback));
        $('#edit-q-grade').val(questions[id - 1].defaultgrade);
        var numberOfAnswer = questions[id - 1].answers.length;
        $('#edit-q-num-answers').val(numberOfAnswer);
        for (var i = 1; i <= numberOfAnswer; i++) {
            CKEDITOR.instances['edit-answer-' + i].setData(rhtmlspecialchars(questions[id - 1].answers[i - 1].answer));
            $('#edit-ans-grade-' + i).prop('checked', false);
            if (questions[id - 1].answers[i - 1].fraction > 0) {
                $('#edit-ans-grade-' + i).prop('checked', true);
            }
        }
        checkNumberOfAnswersToEdit();
    }

    function isInt(n)
    {
        return n != "" && !isNaN(n) && Math.round(n) == n;
    }
    function isFloat(n){
        return n != "" && !isNaN(n) && Math.round(n) != n;
    }

    $("#btnAddQues").click(function(e) {
        e.preventDefault();
        var isValid = true;
        // validate
        var countTrue = 0;
        for (var j = 1; j <= 8; j++) {
            var idCheck = "#ans-grade-" + j;
            if ($(idCheck).prop("checked") == true) {
                countTrue++;
            }
        }
        if (countTrue == 0) {
            isValid = false;
            $("#toast-quiz-error-message").html("Phải có ít nhất một đáp án đúng!");
            $("#toast-quiz-error").toast({ delay: 5000});
            $("#toast-quiz-error").toast('show');
        }
        var answerCount = $("#q-num-answers").val();
        for (var i = 1; i <= answerCount; i++) {
            var id = 'answer-' + i;
            if (CKEDITOR.instances[id].getData() == "") {
                isValid = false;
                $("#toast-quiz-error-message").html("Các câu trả lời không được để trống!");
                $("#toast-quiz-error").toast({ delay: 5000});
                $("#toast-quiz-error").toast('show');
            }
        }
        if (!(isInt($("#q-grade").val()) || isFloat($("#q-grade").val()))) {
            isValid = false;
            $("#toast-quiz-error-message").html("Điểm số không hợp lệ!");
            $("#toast-quiz-error").toast({ delay: 5000});
            $("#toast-quiz-error").toast('show');
        }
        if (CKEDITOR.instances['q-text'].getData() == "") {
            isValid = false;
            $("#toast-quiz-error-message").html("Nội dung câu hỏi không được để trống!");
            $("#toast-quiz-error").toast({ delay: 5000});
            $("#toast-quiz-error").toast('show');
        }
        if ($("#q-name").val().length > 255) {
            isValid = false;
            $("#toast-quiz-error-message").html("Độ dài tên câu hỏi không được quá 255 kí tự!");
            $("#toast-quiz-error").toast({ delay: 5000});
            $("#toast-quiz-error").toast('show');
        }
        // end validate
        if (isValid) {
            $("#form-add-answer").submit(); // check valid condition and submit form :v
        }
        e.preventDefault();
    })

    $("#btnAssignQues").click(function() {
        dataInput = {};
        dataInput['id'] = {};
        var quesCount = <?=count($this->questions)?>;
        var index = -1;
        for (var i = 1; i <= quesCount; i++) {
            if ($("#ques-" + i).prop('checked') == true) {
                dataInput['id'][++index] = $("#ques-" + i).val();
            }
        }
        dataInput['quizid'] = $("#sel1").find('option:selected').val();
        dataInput['grade'] = ($("#grade").val() == '' ? 1 : $("#grade").val());

        $.ajax({
            type: "POST",
            cache: false,
            url: '/ajax/assignQuesById',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function(res) {
                $("#assign-error").hide();
                $("#assign-success").hide();
                var ress = JSON.parse(res).status;
                if (ress != 'success' && Array.isArray(ress)) {
                    var content = '';
                    ress.forEach(element => {
                        content += element + '; ';
                    });
                    $("#assign-error").html("<b>Chú ý:</b> Một số câu hỏi đã tồn tại trong đề thi.");
                    $("#assign-error").show(500);
                } else if (ress == 'errGrade') {
                    $("#assign-error").html("Số điểm của câu không hợp lệ.");
                    $("#assign-error").show(500);
                } else {
                    $("#assign-success").html("Thêm câu hỏi vào đề thi thành công.");
                    $("#assign-success").show(500);
                }
            }
        });
    });

    $("#btnEditQues").click(function(e) {
        var isValid = true;
        // validate data start
        var countTrue = 0;
        for (var j = 1; j <= 8; j++) {
            var idCheck = "#edit-ans-grade-" + j;
            if ($(idCheck).prop("checked") == true) {
                countTrue++;
            }
        }
        if (countTrue == 0) {
            isValid = false;
            $("#toast-edit-quiz-error-message").html("Phải có ít nhất một đáp án đúng!");
            $("#toast-edit-quiz-error").toast({ delay: 5000});
            $("#toast-edit-quiz-error").toast('show');
        }
        var answerCount = $("#edit-q-num-answers").val();
        for (var i = 1; i <= answerCount; i++) {
            var id = 'edit-answer-' + i;
            if (CKEDITOR.instances[id].getData() == "") {
                isValid = false;
                $("#toast-edit-quiz-error-message").html("Các câu trả lời không được để trống!");
                $("#toast-edit-quiz-error").toast({ delay: 5000});
                $("#toast-edit-quiz-error").toast('show');
            }
        }
        if (!(isInt($("#edit-q-grade").val()) || isFloat($("#edit-q-grade").val()))) {
            isValid = false;
            $("#toast-edit-quiz-error-message").html("Điểm số không hợp lệ!");
            $("#toast-edit-quiz-error").toast({ delay: 5000});
            $("#toast-edit-quiz-error").toast('show');
        }
        if (CKEDITOR.instances['edit-q-text'].getData() == "") {
            isValid = false;
            $("#toast-edit-quiz-error-message").html("Nội dung câu hỏi không được để trống!");
            $("#toast-edit-quiz-error").toast({ delay: 5000});
            $("#toast-edit-quiz-error").toast('show');
        }
        if ($("#edit-q-name").val().length > 255) {
            isValid = false;
            $("#toast-edit-quiz-error-message").html("Độ dài tên câu hỏi không được quá 255 kí tự!");
            $("#toast-edit-quiz-error").toast({ delay: 5000});
            $("#toast-edit-quiz-error").toast('show');
        }
        e.preventDefault();

        // validate data end
        if (isValid) {
            var currentUrl = window.location.pathname;
            currentUrl = currentUrl.split('/');
            var currentQuizId = currentUrl[2];
            var dataQues = {};
            dataQues['id'] = currentEditQues;
            dataQues['name'] = $("#edit-q-name").val()
            dataQues['text'] = CKEDITOR.instances['edit-q-text'].getData();
            dataQues['feedback'] = CKEDITOR.instances['edit-q-feedback'].getData();
            dataQues['type'] = countTrue > 1 ? 2 : 1;
            dataQues['grade'] = $("#edit-q-grade").val();
            dataQues['answers'] = [];
            var answer_data = [];
            for (var i = 1; i <= answerCount; i++) {
                answer_data = [];
                var id = 'edit-answer-' + i;
                answer_data.push(CKEDITOR.instances[id].getData());
                var idCheck = "#edit-ans-grade-" + i;
                answer_data.push($(idCheck).prop("checked") == true ? 1 : 0);
                dataQues['answers'].push(answer_data);
            }
            $.ajax({
                type: 'POST',
                cache: false,
                url: '/ajax/updateQuestion',
                data: {dataAjax: JSON.stringify(dataQues)},
                success: function(response) {
                    var res = JSON.parse(response).status;
                    if (res == 'success') {
                        $("#toast-edit-quiz-success-message").html("Update thành công! Hệ thống sẽ tự chuyển trong 3 giây!");
                        $("#toast-edit-quiz-success").toast({ delay: 5000});
                        $("#toast-edit-quiz-success").toast('show');
                        setTimeout(() => {
                            window.location.href = '/dashboard/questionbank';
                        }, 3000);
                    } else {
                        $("#toast-edit-quiz-error-message").html("Lỗi hệ thống!");
                        $("#toast-edit-quiz-error").toast({ delay: 5000});
                        $("#toast-edit-quiz-error").toast('show');
                    }
                }
            });
        }
        // call ajax update
        e.preventDefault();
    })
</script>