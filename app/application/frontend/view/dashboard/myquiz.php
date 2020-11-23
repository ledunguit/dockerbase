<?php
$n = count($this->quizzes);
$numberOfPage = $n / 20 + 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $pg = $_GET['page'];
    if((int) $page > $numberOfPage || (int) $page < 1) {
        $page = 1;
        $pg = 1;
    }
}
else {
    $page = 1;
    $pg = 1;
}
$str = '';
if($page == 1){
    $from = 0;
    $end = 20;
}
else {
    $from = 20*($page - 1);
    $end = $from + 20;
}
$pageUrl = \Venus\Venus::$baseUrl . '/dashboard/myquiz';
?>
<div class="container-fluid">
    <div class="main-content">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>/dashboard">Bảng điều khiển</a></li>
            <li class="breadcrumb-item active"><a href="">Các đề thi của tôi</a></li>
        </ul>
        <h2 class="d-flex justify-content-center text-danger">Quản lý đề thi</h2>
        <p>Danh sách tất cả các đề thi mà bạn đã tạo.</p>
        <a href="<?= \Venus\Venus::$baseUrl ?>/quiz/create"><button type="button" class="btn btn-success"><i class="fas fa-plus mr-2"></i>Tạo đề thi mới</button></a>
        <div class="row d-flex justify-content-center"><input class="form-control mb-4 col-sm-5" id="myInput" type="text" placeholder="Nhập từ khóa.."></div>
        <div class="table-responsive">
            <table id ="myTable" class="table table-bordered">
                <thead>
                <tr>
                    <th class="text-center" style="width: 3%">#</th>
                    <th class="text-center">Tên đề thi</th>
                    <th class="text-center" style="width: 8%">Số câu hỏi</th>
                    <th class="text-center" style="width: 8%">Thời gian</th>
                    <th class="text-center" style="width: 10%">Số lượt làm</th>
                    <th class="text-center" style="width: 10%">Hành động</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $str = '';
                $i = 0;
                foreach ($this->quizzes as $key) {
                    $i++;
                    if($i <= $from)
                        continue;
                    if($i > $end)
                        break;
                    $str .= '<tr>
                <td class="text-center">'.$i.'</td>
                <td>'.$key['name'].'</td>
                <td class="text-center">'.$key['numberOfQuestions'].'</td>
                <td class="text-center">'.$key['timeLimitConverted'].'</td>
                <td class="text-center">'.$key['numberOfAttempts'].'</td>
                <td class="text-center" style="font-size:18px">
                    <a href="'.\Venus\Venus::$baseUrl.'/quiz/enroll/'.$key['id'].'" class="mr-1"><i class="far fa-eye text-primary"></i></a>
                    <a href="'.\Venus\Venus::$baseUrl.'/dashboard/managequiz/'.$key['id'].'" class="mr-1"><i class="fas fa-cog text-info"></i></a>
                    <a href="'.\Venus\Venus::$baseUrl.'/quiz/'.$key['id'].'" class="mr-1"><i class="fas fa-edit text-warning"></i></a>
                    <a href="javascript:deleteItem('.$key['id'].')" class="mr-1"><i class="fas fa-trash-alt text-danger"></i></a>
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
                <li class="page-item"><a class="page-link" href="<?php echo $pageUrl . '?page='; echo ($page <= 1)?1:--$page; ?>">Previous</a></li>
                <?php
                for ($i = 1; $i <= $numberOfPage; $i++){
                    $active = '';
                    if($i == $pg) {
                        $active = ' active';
                    }
                    echo '<li class="page-item'.$active.'"><a class="page-link" href="' . $pageUrl . '?page=' . $i . '">'.$i.'</a></li>';
                }
                ?>
                <li class="page-item"><a class="page-link" href="<?php echo $pageUrl . '?page='; echo ($page >= $numberOfPage)?$numberOfPage:++$page; ?>">Next</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-content">
        <div class="row d-flex justify-content-center col-sm-6">
            <table class="table table-bordered mt-4">
                <tr>
                    <td class="font-weight-bold" style="width: 70%">Số đề thi đã tạo:</td>
                    <td><?php echo $this->report['numOfQuizzes']; ?></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Tổng số lượt được làm của các đề thi:</td>
                    <td><?php echo $this->report['getTimesBeAttempted']; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div id="dialog-confirm" title="Xóa đề thi" style="display:none">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Bạn có chắc chắn muốn xóa đề thi này? Những thay đổi sẽ không thể hoàn tác.</p>
</div>

<script>
    function deleteItem(quizid) {
        $("#dialog-confirm").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Xác nhận": function() {
                    dataInput = {};
                    dataInput['quizid'] = quizid;
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
                    $("#dialog-confirm").dialog( "close" );
                },
                "Hủy bỏ": function() {
                    $("#dialog-confirm").dialog( "close" );
                }
            }
        });
    }

    $(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>