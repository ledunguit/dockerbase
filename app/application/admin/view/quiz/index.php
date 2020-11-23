<div class="container-fluid">
    <div class="main-content p-3">
        <h2 class="text-center text-danger">Quản lý đề thi</h2>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="sel1" class="font-weight-bold">Chọn danh mục:</label>
                    <div class="d-flex">
                        <select class="form-control mr-3" id="sel1">
                            <?php
                            foreach ($this->categories as $key) {
                                echo '<option value="' . $key['id'] . '">' . $key['name'] . '</option>';
                            }
                            ?>
                        </select>
                        <a href="javascript:viewByCategory()">
                            <button type="button" class="btn btn-warning">Xem</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="sel1" class="font-weight-bold">Chuyển đến:</label>
                    <div class="d-flex">
                        <select class="form-control mr-3" id="sel2">
                            <?php
                            foreach ($this->categories as $key) {
                                echo '<option value="' . $key['id'] . '">' . $key['name'] . '</option>';
                            }
                            ?>
                        </select>
                        <a href="javascript:changeQuizCate()">
                            <button type="button" class="btn bg-findhouse text-light">Chuyển</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên đề thi</th>
                        <th>Thời gian</th>
                        <th>Số câu hỏi</th>
                        <th>Người tạo</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody id="body-table">
                    </tbody>
                </table>
            </div>
            <div class="alert alert-warning" style="width: 100%;display: none" id="result">Chưa có đề thi nào trong danh
                mục này !
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <ul class="pagination" id="pager">
            </ul>
        </div>

    </div>
</div>
<script type="text/javascript">
    var url = location.origin;
    var list;
    var sumQuizCurrent;
    $(document).ready(function () {
        viewByCategory(1);
    });
    function viewByCategory(categoryId = null) {
        if(categoryId == null)
            categoryId = $('#sel1').val();
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/admin/ajax/getQuizByCategory',
            data: {dataAjax: categoryId},
            success: function (data) {
                var res = JSON.parse(data);
                list = res;
                if(res != null) {
                    var n = res.length;
                    var numberOfPage = n / 10 + 1;
                    var pager = '<li class="page-item"><a class="page-link" id ="previous" href="javascript:viewPage(' + (i - 1) + ')">Previous</a></li>';
                    for (var i = 1; i <= numberOfPage; i++) {
                        pager += '<li class="page-item"><a class="page-link" href="javascript:viewPage(' + i + ')">' + i + '</a></li>';
                    }
                    pager += '<li class="page-item"><a class="page-link" id = "next">Next</a></li>';
                    $('#pager').html(pager);
                    if (n == 0) {
                        $('#body-table').html('');
                        $('#result').css('display', 'block');
                    } else {
                        viewPage(1);
                    }
                }
                else {
                    $('#body-table').html('');
                    $('#result').css('display', 'block');
                }
            }
        });
    }

    function deleteItem(quizId) {
        var dataInput = {};
        dataInput['quizid'] = quizId;
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/admin/ajax/deleteQuiz',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function(res) {
                location.reload();
            },
            fail: function(res) {
                console.log(res);
            }
        });
    }

    function viewPage(page) {
        var res = list;
        sumQuizCurrent = list.length;
        console.log(sumQuizCurrent);
        var from = (page - 1) * 10;
        var end = from + 10;
        var str = '';
        for (var i = from; i < end; i++) {
            if(i >= res.length){
                break;
            }
            var temp = '';
            if (res[i].visible == 0) {
                temp = 'alert-danger';
            }
            str += '<tr class="' + temp + '"><td><input type="checkbox" id="quiz-' + (i + 1) + '" value="' + res[i].id + '" style="margin:0px 6px;">' + (i + 1) + '</td>' +
                '<td>' + res[i].name + '</td>' +
                '<td>' + res[i].timeLimitConverted + '</td>' +
                '<td>' + res[i].numberOfQuestions + '</td>' +
                '<td><a href="' + url + '/account/' + res[i].createdby + '">' + res[i].createdByName + '</a></td>' +
                '<td style="width:310px">' +
                '<a href="' + url + '/quiz/enroll/' + res[i].id + '" class="mr-2"><button type="button" class="btn btn-info"><i class="fas fa-eye mr-2"></i>Xem</button></a>' +
                '<a href="' + url + '/dashboard/managequiz/' + res[i].id + '" class="mr-2" target="_blank"><button type="button" class="btn btn-primary"><i class="fas fa-pencil-alt mr-2"></i>Cài đặt</button></a>' +
                '<a href="javascript:deleteItem(' + res[i].id + ')"><button type="button" class="btn btn-danger"><i class="fas fa-trash-alt mr-2"></i>Xóa</button></a>' +
                '</td>' +
                '</tr>';
        }
        $('#body-table').html(str);
        $('#result').css('display', 'none');
        $('#previous').attr('href', 'javascript:viewPage(' + (page - 1) + ')');
        $('#next').attr('href', 'javascript:viewPage(' + (page + 1) + ')');
    }

    function changeQuizCate() {
        var dataInput = {};
        dataInput['cateid'] = $("#sel2 option:selected").val();
        for (var i = 1; i <= sumQuizCurrent; i++) {
            if ($('#quiz-' + i).prop('checked') == true) {
                dataInput['quizid'] = $('#quiz-' + i).val();
                console.log(dataInput);
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '/admin/ajax/changeCateForQuiz',
                    data: {dataAjax: JSON.stringify(dataInput)},
                    success: function(res) {
                        window.location.reload();
                    },
                    fail: function(res) {
                    }
                });
            }
        }
    }
</script>