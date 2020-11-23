<div class="container-fluid">
    <div class="main-content pt-3 pb-3 border-none">
        <h2 class="text-center text-danger">Quản lý kết quả thi</h2>
        <div class="text-center">Tổng hợp toàn bộ dữ liệu bài làm của người dùng</div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-3">
            <div class="card mt-3">
                <div class="card-header bg-footer text-light font-weight-bold">Chọn đề thi</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="sel1">Chọn danh mục:</label>
                        <select class="form-control" id="choose-category" onchange="javascript:getQuizByCategory()">
                            <?php if ($this->categories) {
                                foreach ($this->categories as $key) {
                                    echo '<option value="' . $key['id'] . '"><a href="javascript:viewByCategory(' . $key['id'] . ', \'' . $key['name'] . '\')">' . $key['name'] . '</a> (' . $key['numberOfAttempts'] . ')</option>';
                                }
                            } ?>
                        </select>
                        <select class="form-control mt-2" id="choose-quiz">
                            <option value="0">--Vui lòng chọn danh mục</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn bg-orange text-light" onclick="javascript:viewByQuiz()">Xem</button>
                    </div>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Họ và tên</th>
                        <th>Thời gian</th>
                        <th>Điểm số</th>
                        <th>Hệ 10</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody id="body-table"></tbody>
                </table>
            </div>
            <div class="alert alert-warning" style="width: 100%;display: none" id="result">Chưa có lượt thi nào trong đề thi này !
            </div>
            <div class="d-flex justify-content-center">
                <ul class="pagination" id="pager">
                </ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    baseUrl = location.origin;
    var list;
    $(document).ready(function(){
        getQuizByCategory();
        viewByQuiz();
    });
    function getQuizByCategory() {
        var categoryId = $('#choose-category').val();
        $.ajax({
            type: 'POST',
            cache: false,
            url: "/admin/ajax/getQuizByCategory",
            data: {dataAjax: categoryId},
            success: function (data) {
                var res = JSON.parse(data);
                n = res.length;
                var str = '';
                for (var i = 0; i < n; i++) {
                    str += '<option value="' + res[i].id + '">' + res[i].name + ' (' + res[i].numberOfAttempts + ')</option>'
                }
                $('#choose-quiz').html(str);
                list = res;
            }
        });
    }

    function viewByQuiz() {
        url = "/admin/ajax/getResultByQuiz";
        id = $('#choose-quiz').val();
        ajax(url, id);
    }

    function ajax(url, id) {
        $.ajax({
            type: 'POST',
            cache: false,
            url: url,
            data: {dataAjax: id},
            success: function (data) {
                var res = JSON.parse(data);
                console.log(res);
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
            if (res[i].status == -1) {
                temp = 'alert-danger';
            }
            if(res[i].fullname == null){
                res[i].fullname = res[i].guest;
            }
            str += '<tr class="text-center ' + temp + '"><td><input type="checkbox" style="margin:0px 6px;" id="' + res[i].id + '">' + (i + 1) + '</td>' +
                '<td class="text-left"><a href="' + baseUrl + '/account/' + res[i].userid + '">'+ res[i].fullname + '</a></td>';
            str += '<td>'+res[i].timesubmitted + '</td>';
            str += '<td>'+res[i].grade + ' / ' + res[i].sumgrade + '</td>';
            str += '<td>'+res[i].gradeByBaseGrade + '</td>';
            str += '<td>' +
                '<a href="' + baseUrl + '/attempts/review/' + res[i].id + '" class="mr-2"><button type="button" class="btn btn-info"><i class="fas fa-eye mr-2"></i>Xem</button></a>' +
                '<a href="javascript:editItem(' + i + ')" class="mr-2"><button type="button" class="btn btn-danger"><i class="fas fa-trash-alt mr-2"></i>Xóa</button></a>' +
                '</td></tr>';
        }
        $('#body-table').html(str);
        $('#result').css('display', 'none');
        $('#previous').attr('href', 'javascript:viewPage(' + (page - 1) + ')');
        $('#next').attr('href', 'javascript:viewPage(' + (page + 1) + ')');
    }
</script>