<div class="container-fluid">
    <div class="main-content p4">
        <h2 class="text-center">Các yêu cầu</h2>
        <p class="text-center">Tổng hợp các yêu cầu công khai đề thi của thành viên</p>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Đề thi</th>
                    <th>Mã đề</th>
                    <th>Chuyển tới</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $str = '';
                if($this->categories){
                    foreach($this->categories as $key){
                        $str .= '<option value="'.$key['id'].'">'.$key['name'].'</option>';
                    }
                }
                $i = 0;
                if($this->require){
                    foreach ($this->require as $key){
                        $i++;
                    echo '<tr>
                    <td>'.$i.'</td>
                    <td><a href="'.\Venus\Venus::$adminUrl .'/quiz/enroll/'.$key->quizid.'" target="_blank">'.$key->name.'</a></td>
                    <td>'.$key->code.'</td>
                    <td>
                        <select class="form-control" id="selcate">
                            '.$str.'
                        </select>
                    </td>
                    <td style="width:20%">
                        <button id="accept-' . $key->code . '" class="btn btn-success mr-2"><i class="fas fa-plus mr-2"></i>Duyệt</button>
                        <button class="btn btn-danger"><i class="fas fa-trash-alt mr-2"></i>Xóa</button>
                    </td>
                </tr>';
                    }
                }
                else {
                    echo '<tr><div class="alert alert-warning">Hiện tại chưa có yêu cầu nào</div></tr>';
                }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>

    <?php foreach ($this->require as $key) :?>
        $("#accept-<?=$key->code?>").click(function() {
            var quizid = <?=$key->quizid?>;
            acceptQuiz(quizid, $("#selcate option:selected").val());
        })
    <?php endforeach; ?>
    function acceptQuiz(quizid, cateid) {
        var dataInput = {};
        dataInput['quizid'] = quizid;
        dataInput['cateid'] = cateid;
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/admin/ajax/changeCateForRequest',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function (res) {
                var resj = JSON.parse(res).status;
                if (resj == 'success') {
                    alert("Đã duyệt yêu cầu.");
                    location.reload();
                } else {
                    alert("Lỗi hệ thống, vui lòng thử lại sau!");
                }
            }
        });
    }

</script>