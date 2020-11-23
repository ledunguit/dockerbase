<div class="container-fluid">
    <div class="main-content pt-5" style="border:none">
        <h2 class="text-center" style="margin-top: -20px">Ngân hàng câu hỏi</h2>
        <p class="h4 text-center text-danger"><b id="cate-name"></b></p>
    </div>
    <?php if (isset($this->failed)): ?>
            <span id="addQuesStatus" class="alert alert-danger"><?=$this->failed?></span>
        <?php elseif (isset($this->success)): ?>
            <span id="addQuesStatus" class="alert alert-success"><?=$this->success?></span>
    <?php endif; ?>
    <div class="row mb-4">
        <div class="col-sm-3">
            <div class="card">
                <div class="card-header bg-footer text-light font-weight-bold">Ngân hàng câu hỏi theo danh mục</div>
                <div class="card-body">
                    <ol>
                        <?php if ($this->categories) {
                            foreach ($this->categories as $key) {
                                echo '<li><a href="javascript:viewByCategory(' . $key['id'] . ', \'' . $key['name'] . '\')">' . $key['name'] . '</a> (' . $key['numberOfQuestions'] . ')</li>';
                            }
                        } ?>
                    </ol>
                </div>
                <div class="card-footer">Tổng hợp tất cả các câu hỏi của tất cả các đề thi phân loại theo danh mục</div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="d-flex mt-2">
                <div class="col-sm-4">
                    <a href="javascript:addItem()" class="mr-2">
                        <button class="btn btn-success" id="btn-add"><i class="fas fa-plus mr-2"></i>Thêm câu hỏi</button>
                    </a>
                    <a href="javascript:deleteMany()" class="mr-2">
                        <button class="btn btn-danger"><i class="fas fa-trash-alt mr-2"></i>Xóa</button>
                    </a>
                </div>
                <div class="col-sm-6">
                    <select class="form-control" id="sel1">
                        <?php if ($this->categories) {
                            foreach ($this->categories as $key) {
                                echo '<option value="' . $key['id'] . '">' . $key['name'] . '</a></li>';
                            }
                        } ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <a href="javascript:move()">
                        <button class="btn btn-warning"><i class="fas fa-trash-alt mr-2"></i>Chuyển</button>
                    </a>
                </div>
            </div>
            <div class="row m-3" id="cate-number"></div>
            <div class="table-responsive">
                <table class="table table-bordered mt-3 table-sm">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên câu hỏi</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody id="body-table">

                    </tbody>
                </table>
            </div>
            <div class="alert alert-warning" style="width: 100%;display: none" id="result">Chưa câu hỏi nào trong danh
                mục này !
            </div>
            <div class="d-flex justify-content-center">
                <ul class="pagination" id="pager">
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xem trước câu hỏi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="question-text" id="q-preview">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
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
                        <textarea class="form-control" rows="2" id="edit-q-name" name="edit-q-name"
                                  placeholder="Nhập tên của câu hỏi..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-q-text">Nội dung câu hỏi:</label>
                        <textarea class="form-control" rows="5" id="edit-q-text" name="edit-q-text"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-q-feedback">Giải thích:</label>
                        <textarea class="form-control" rows="5" id="edit-q-feedback" name="edit-q-feedback"></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label for="edit-q-grade">Điểm số của câu hỏi (Mặc định là 1.00 điểm):</label>
                        <input type="text" class="form-control" placeholder="1.00000" id="edit-q-grade"
                               name="edit-q-grade" value="1.00000" style="width: 30%">
                    </div>
                    <div class="form-group mt-3">
                        <label for="edit-q-num-answers">Số đáp án:</label>
                        <select class="form-control" id="edit-q-num-answers" name="edit-q-num-answers"
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
                        <textarea class="form-control" rows="5" id="edit-answer-' . $i . '" name="edit-answer-' . $i . '"></textarea>
                    </div><div class="form-check mb-3">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="edit-ans-grade-' . $i . '"
                                   name="edit-ans-grade-' . $i . '">Đây là đáp án đúng
                        </label>
                    </div></div>';
                        echo $html;
                    }
                    ?>
                    <button type="button" id="btnEditQues" class="btn btn-primary">Tiếp tục</button>
                    <button type="submit" class="btn btn-danger ml-4" onclick="resetForm();">Xóa bỏ</button>
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
                        <label for="q-grade">Điểm số của câu hỏi (Mặc định là 1.00 điểm):</label>
                        <input type="text" class="form-control" placeholder="1.00000" id="q-grade"
                               name="Ques[grade]" value="1.00000" style="width: 30%">
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
                    <input type="hidden" name="Ques[category]" value="" id="quizid">
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
<script type="text/javascript">
    CKEDITOR.replace('edit-q-text', {
        extraPlugins: 'uploadimage'
    });

    CKEDITOR.replace('edit-q-feedback', {
        extraPlugins: 'uploadimage',
    });

    CKEDITOR.replace('q-feedback', {
        extraPlugins: 'uploadimage',
    });

    CKEDITOR.replace('q-text', {
        extraPlugins: 'uploadimage',
    });
    for (var i = 1; i <= 8; i++) {
        CKEDITOR.replace('edit-answer-' + i, {
            extraPlugins: 'uploadimage',
        });
        CKEDITOR.replace('answer-' + i, {
            extraPlugins: 'uploadimage',
        });
    }
    var url = location.origin;
    var list;
    var currentCategory;
    $(document).ready(function () {
        viewByCategory(1, "General");
    });

    function viewByCategory(id, name) {
        currentCategory = id;
        $('#cate-name').text(name);
        $('#btn-add').attr('href', '');
        ajax('/admin/ajax/getQuestionForCategory', id);
    }

    var currentCountQues = 0;

    function ajax(url, id) {
        $.ajax({
            type: 'POST',
            cache: false,
            url: url,
            data: {dataAjax: id},
            success: function (data) {
                var res = JSON.parse(data);
                list = res;
                if (res != null) {
                    var n = res.length;
                    var numberOfPage = n / 30 + 1;
                    var pager = '<li class="page-item"><a class="page-link" id ="previous" href="javascript:viewPage(' + (i - 1) + ')">Previous</a></li>';
                    for (var i = 1; i <= numberOfPage; i++) {
                        pager += '<li class="page-item"><a class="page-link" href="javascript:viewPage(' + i + ')">' + i + '</a></li>';
                    }
                    pager += '<li class="page-item"><a class="page-link" id = "next">Next</a></li>';
                    $('#pager').html(pager);
                    if (n == 0) {
                        $('#body-table').html('');
                        $('#result').css('display', 'block');
                        $('#pager').html('');
                    } else {
                        $('#cate-number').html('Danh mục này có ' + res.length + '</b> câu hỏi.');
                        currentCountQues = res.length;
                        viewPage(1);
                    }
                } else {
                    $('#body-table').html('');
                    $('#cate-number').html('');
                    $('#result').css('display', 'block');
                    $('#pager').html('');
                }
            }
        });
    }

    function viewPage(page) {
        var res = list;
        var from = (page - 1) * 30;
        var end = from + 30;
        var str = '';
        for (var i = from; i < end; i++) {
            if (i >= res.length) {
                break;
            }
            var temp = '';
            if (res[i].visible == 0) {
                temp = 'alert-secondary';
            }
            str += '<tr id="row-' + res[i]['id'] + '" class="' + temp + '"><td><input type="checkbox" id="ques-' + (i + 1) + '" value="' + res[i].id + '" style="margin:0px 6px;">' + (i + 1) + '</td>' +
                '<td>' + res[i].name + '</td>';
            if (res[i].isused == "X") {
                str += '<td><span class="badge badge-danger">Đã sử dụng</span></td>';
            } else {
                str += '<td><span class="badge badge-success">Chưa sử dụng</span></td>';
            }
            str += '<td>' +
                '<a href="javascript:viewItem(' + i + ')" class="mr-2"><button type="button" class="btn btn-info"><i class="fas fa-eye"></i></button></a>' +
                '<a href="javascript:editItem(' + i + ')" class="mr-2"><button type="button" class="btn btn-success"><i class="fas fa-pencil-alt"></i></button></a>' +
                '</td></tr>';
        }
        $('#body-table').html(str);
        $('#result').css('display', 'none');
        $('#previous').attr('href', 'javascript:viewPage(' + (page - 1) + ')');
        $('#next').attr('href', 'javascript:viewPage(' + (page + 1) + ')');
    }

    function viewItem(id) {
        var question = list[id];
        var str = '<div class="alert-primary p-3"><b>Xem trước câu hỏi:</b></div>';
        str += '<div class="p-3">'
        str += '<p>' + rhtmlspecialchars(question.questiontext) + '</div>';
        var numOfAns = question.answers.length;
        var result = "";
        if (question.type == 1) {
            for (var i = 0; i < numOfAns; i++) {
                str += '<div class="form-check"><label class="form-check-label"><input type="radio" class="form-check-input mr-2 " disabled>' + rhtmlspecialchars(question.answers[i].answer) + '</label></div>';
                if (question.answers[i].fraction > 0) {
                    result = rhtmlspecialchars(question.answers[i].answer);
                }
            }
        } else {
            for (var i = 0; i < numOfAns; i++) {
                str += '<div class="form-check"><label class="form-check-label"><input type="checkbox" class="form-check-input mr-2" disabled>' + rhtmlspecialchars(question.answers[i].answer) + '</label></div>';
                if (question.answers[i].fraction > 0) {
                    result += rhtmlspecialchars(question.answers[i].answer) + "<hr> ";
                }
            }
        }
        str += '<p class="mt-3"><b>Đáp án:</b> ' + result + '</p>';
        if(question.feedback == null){
            question.feedback = '';
        }
        str += '<p class=""><b>Giải thích:</b> ' + rhtmlspecialchars(question.feedback) + '</p>';
        str += '</div>'
        $("#myModal").modal();
        $("#q-preview").html(str);
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

    function deleteMany() {
        var countQues = currentCountQues;
        for (var i = 1; i <= countQues; i++) {
            questions = list[i];
            if ($("#ques-" + i).prop('checked') == true) {
                var result = deleteQuestion($('#ques-' + i).attr('value'));
            }
        }
        setTimeout(() =>{window.location.href = '/admin/questionbank'}, 1000);
    }

    function move() {
        var countQues = currentCountQues;
        for (var i = 1; i <= countQues; i++) {
            questions = list[i];
            if ($("#ques-" + i).prop('checked') == true) {
                var result = moveCate($('#ques-' + i).attr('value'), $("#sel1 option:selected").val());
            }
        }
    }

    function moveCate(id, cateid) {
        var dataInput = {};
        dataInput['questionid'] = id;
        dataInput['cateid'] = cateid;
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/admin/ajax/updateQuesToCate',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function(res) {
                if (JSON.parse(res).status == 'success') {
                    location.reload();
                } else {
                    return false;
                }
            },
            fail: function(res) {
                console.log(res);
            }
        });
    }

    function deleteQuestion(id) {
        var dataInput = {};
        dataInput['questionid'] = id;
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/admin/ajax/deleteQuestionBank',
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

    function editItem(id) {
        $('#editQuestion').modal();
        var question = list[id];
        currentEditQues = question['id'];
        console.log(currentEditQues);
        $('#edit-q-name').val(question.name);
        CKEDITOR.instances['edit-q-text'].setData(rhtmlspecialchars(question.questiontext));
        CKEDITOR.instances['edit-q-feedback'].setData(rhtmlspecialchars(question.feedback));
        $('#edit-q-grade').val(question.defaultgrade);
        var numberOfAnswer = question.answers.length;
        $('#edit-q-num-answers').val(numberOfAnswer);
        for (var i = 1; i <= numberOfAnswer; i++) {
            CKEDITOR.instances['edit-answer-' + i].setData(rhtmlspecialchars(question.answers[i - 1].answer));
            $('#edit-ans-grade-' + i).prop('checked', false);
            if (question.answers[i - 1].fraction > 0) {
                $('#edit-ans-grade-' + i).prop('checked', true);
            }
        }
        checkNumberOfAnswersToEdit();
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

    function resetForm() {
        document.getElementById('form-add-answer').reset();
    }

    function addItem(){
        var categoryid = currentCategory;
        $("#quizid").val(categoryid);
        $('#createQuestion').modal();
    }
    function transfer() {
        $('#moveQuestion').modal();
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
                url: '/admin/ajax/updateQuestion',
                data: {dataAjax: JSON.stringify(dataQues)},
                success: function(response) {
                    var res = JSON.parse(response).status;
                    if (res == 'success') {
                        $("#toast-edit-quiz-success-message").html("Update thành công! Hệ thống sẽ tự chuyển trong 3 giây!");
                        $("#toast-edit-quiz-success").toast({ delay: 5000});
                        $("#toast-edit-quiz-success").toast('show');
                        setTimeout(() => {
                            window.location.href = '/admin/questionbank';
                        }, 3000);
                    } else {
                        console.log(response);
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