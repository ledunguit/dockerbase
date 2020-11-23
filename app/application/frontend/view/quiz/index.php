<div class="container-fluid">
    <div class="main-content">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>/dashboard">Bảng điều khiển</a></li>
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl . '/dashboard/myquiz' ?>">Đề thi của
                    tôi</a></li>
            <li class="breadcrumb-item"><a
                        href="<?= \Venus\Venus::$baseUrl . '/dashboard/managequiz/' . $this->quiz['id']; ?>"><?php echo $this->quiz['name']?></a></li>
            <li class="breadcrumb-item active"><a href="#">Chỉnh sửa các câu hỏi</a></li>
        </ul>
        <div class="h3 text-danger text-uppercase text-center mt-4 mb-4">Chỉnh sửa các câu hỏi</div>
        <table class="table table-bordered" style="width: 80%; margin: auto;">
            <tr>
                <td style="width: 15%">Đề thi:</td>
                <td class="h5 text-uppercase"><a
                            href="<?= \Venus\Venus::$baseUrl . '/quiz/enroll/' . $this->quiz['id'] ?>"><?php echo $this->quiz['name'] ?></a>
                </td>
            </tr>
            <tr>
                <td>Danh mục:</td>
                <td>
                    <a href="<?= \Venus\Venus::$baseUrl . '/categories/' . $this->quiz['categoryShortName']; ?>"><?php echo $this->quiz['categoryName']; ?></a>
                </td>
            </tr>
            <tr>
                <td>Người tạo:</td>
                <td>
                    <a href="<?= \Venus\Venus::$baseUrl . '/account/' . $this->quiz['createdby']; ?>"><?php echo $this->quiz['createdByName']; ?></a>
                </td>
            </tr>
            <tr>
                <td>Mô tả:</td>
                <td><?php echo $this->quiz['summary']; ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="main-content">
        <?php if (isset($this->error['validate'])): ?>
            <span id="addQuesStatus" class="alert alert-danger"><?=$this->error['validate']?></span>
        <?php elseif (isset($this->success['addQues'])): ?>
            <span id="addQuesStatus" class="alert alert-success"><?=$this->success['addQues']?></span>
        <?php endif; ?>
        <?php
        if (!$this->quizIsChangable) {
            echo '<div class="alert alert-danger mb-4"><b>Lưu ý: </b>Bạn không thể chỉnh sửa các câu hỏi vì đề thi đã có người thực hiện !</div>';
        } else {
            echo '<div class="row mb-3 ml-2">
            <a>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createQuestion"><i
                            class="fas fa-plus mr-2"></i>Tạo câu hỏi
                </button>
            </a>
             <a href="javascript:deleteManyQues();">
                <button type="button" class="btn btn-danger ml-2"><i class="fas fa-trash-alt mr-2"></i>Xóa</button>
            </a></div>';
        }
        ?>
        <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Tên câu hỏi</th>
                <th>Loại</th>
                <th>Điểm số</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $str = '';
            $i = 0;
            foreach ($this->questions as $key) {
                $i++;
                $type = ($key['type'] == 1) ? "circle" : "square";
                $str .= '<tr class="text-center">
                <td>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="checkbox" id="ques-'. $i .'"class="form-check-input" value="' . $key['id'] . '">' . $i . '
                        </label>
                    </div>
                </td>
                <td class="text-left">' . $key['name'] . '</td>
                <td><i class="far fa-check-' . $type . '"></i></td>
                <td>' . number_format(round($key['grade'], 2), 4) . '</td>
                <td style="width: 15%">';
                if ($key['canBeChanged']) {
                    $str .= '<a href="javascript:editQuestion(' . $i . ')">
                        <button type="button" class="btn btn-info"  data-toggle="modal" data-target="#editQuestion"> Chỉnh sửa
                        </button>
                    </a><a href="javascript:deleteQuestion(' . $key['id'] . ')">
                        <button type="button" class="btn btn-danger ml-2">Xóa
                        </button>
                    </a>';
                } else {
                    $str .= '<button type="button" class="btn btn-info" disabled> Chỉnh sửa
                        </button><button type="button" class="btn btn-danger ml-2" disabled>Xóa
                        </button>';
                }
                $str .= '</td>
            </tr>';
            }
            echo $str; ?>
            </tbody>
        </table>
        </div>
    </div>
    <div id="dialog-delete-question" title="Xóa câu hỏi ?" style="display: none">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Hành động này không
            thể hoàn tác. Bạn có chắc chắn muốn xóa câu hỏi này?</p>
    </div>
    <div id="dialog-message" title="Thành công !" style="display: none">
        <p>
            <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;" id="dialog-message-content">Xóa câu hỏi thành công!</span>
        </p>
    </div>
</div>
<div class="modal" id="createQuestion">
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
                        <textarea class="form-control" rows="2" id="q-name" name="Quiz[name]"
                                  placeholder="Nhập tên của câu hỏi..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="q-text">Nội dung câu hỏi:</label>
                        <textarea class="form-control" rows="5" id="q-text" name="Quiz[text]"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="q-feedback">Giải thích:</label>
                        <textarea class="form-control" rows="5" id="q-feedback" name="Quiz[feedback]"></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label for="q-grade">Điểm số của câu hỏi (Mặc định là 1.00 điểm):</label>
                        <input type="text" class="form-control" placeholder="1.00000" id="q-grade"
                               name="Quiz[grade]" value="1.00000" style="width: 30%">
                    </div>
                    <div class="form-group mt-3">
                        <label for="q-num-answers">Số đáp án:</label>
                        <select class="form-control" id="q-num-answers" name="Quiz[num-answers]" style="width: 30%"
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
                        <textarea class="form-control" rows="5" id="answer-' . $i . '" name="Quiz[answer-' . $i . ']"></textarea>
                    </div><div class="form-check mb-3">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="ans-grade-' . $i . '"
                                   name="Quiz[ans-grade-' . $i . ']">Đây là đáp án đúng
                        </label>
                    </div></div>';
                        echo $html;
                    }
                    ?>
                    <input type="submit" class="btn btn-primary" name="btnAddQues" id="btnAddQues" value="Tiếp tục">
                    <input type="button" class="btn btn-danger ml-4" onclick="resetFormAdd();" value="Điền lại">
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
                        <label for="edit-q-grade">Điểm số của câu hỏi (Mặc định là 1.00 điểm):</label>
                        <input type="text" class="form-control" placeholder="1.00000" id="edit-q-grade"
                               name="QuizEdit[grade]" value="1.00000" style="width: 30%">
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
                    <input type="submit" class="btn btn-primary" name="btnEditQues" id="btnEditQues" value="Tiếp tục"></input>
                    <input type="submit" class="btn btn-danger ml-4" onclick="resetFormEdit();" value="Xóa bỏ"></input>
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
<script>
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
    var questions = Object.assign({}, <?php echo json_encode($this->questions); ?>);
    var currentEditQues = 0;
    function editQuestion(id) {
        var questionId = questions[id - 1].id;
        currentEditQues = questionId;
        $('#edit-q-name').val(questions[id - 1].name);
        CKEDITOR.instances['edit-q-text'].setData(rhtmlspecialchars(questions[id - 1].questiontext));
        CKEDITOR.instances['edit-q-feedback'].setData(rhtmlspecialchars(questions[id - 1].feedback));
        $('#edit-q-grade').val(questions[id - 1].grade);
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

    function resetFormAdd() {
        document.getElementById('form-add-answer').reset();
    }

    function resetFormEdit() {
        document.getElementById('form-edit-answer').reset();
    }

    function deleteManyQues() {
        var countQues = <?=count($this->questions);?>;
        for (var i = 1; i <= countQues; i++) {
            if ($("#ques-" + i).prop('checked') == true) {
                var result = deleteQuestions($('#ques-' + i).attr('value'));
            }
        }
        alert("Đã xóa toàn bộ những câu hỏi đã chọn!");
        setTimeout(() =>{window.location.href = '/quiz/' + window.location.pathname.split('/')[2]}, 1000);
    }

    function deleteQuestions(quesid) {
        var dataInput = {};
        dataInput['quizid'] = window.location.pathname.split('/')[2];
        dataInput['questionid'] = quesid;
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/ajax/deleteQuestion',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function(res) {
                console.log(res);
            },
            fail: function(res) {
                return false;
            }
        });
    }

    function deleteQuestion(questionid) {
        $("#dialog-delete-question").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Xóa câu hỏi": function () {
                    var dataInput = {};
                    dataInput['questionid'] = questionid;
                    dataInput['quizid'] = window.location.pathname.split('/')[2];
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/ajax/deleteQuestion',
                        data: {dataAjax: JSON.stringify(dataInput)},
                        success: function(res) {
                            console.log(res);
                            if (JSON.parse(res).status == 'success') {
                                window.location.href = '/quiz/' + window.location.pathname.split('/')[2];
                            } else {
                                alert("Không thể xóa câu hỏi này! Lỗi hệ thống...");
                            }
                        },
                        fail: function(res) {
                            alert('Lỗi hệ thống!');
                        }
                    });
                    $("#dialog-delete-question").dialog("close");
                },
                Cancel: function () {
                    $("#dialog-delete-question").dialog("close");
                }
            }
        });
    }

    function isInt(n)
    {
        return n != "" && !isNaN(n) && Math.round(n) == n;
    }
    function isFloat(n){
        return n != "" && !isNaN(n) && Math.round(n) != n;
    }

    $("#btnAddQues").click(function(e) {
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
            dataQues['quizid'] = currentQuizId;
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
                            window.location.href = '/quiz/' + currentQuizId;
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