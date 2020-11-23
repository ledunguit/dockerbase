<div class="container-fluit">
    <div class="main-content">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
            <li class="breadcrumb-item active"><a href="<?= \Venus\Venus::$baseUrl ?>/dashboard/myquiz">Các đề thi</a></li>
            <li class="breadcrumb-item active"><a href="">Tạo đề thi</a></li>
        </ul>
        <h2>Tạo đề thi</h2>
        <div class="row d-flex justify-content-center">
            <div class="col-sm-8">
            <?php if (isset($this->error)): ?>
                <div class="alert alert-danger" id="quiz-create-result"><?=$this->error?></div>
            <?php endif; ?>
            <?php if (isset($this->success)): ?>
                <div class="alert alert-success" id="quiz-create-result"><?=$this->success?></div>
            <?php endif; ?>
                <form method="post" id="form-create-quiz">
                    <div class="form-group">
                        <label for="quiz-name">Tên đề thi (tối đa 255 ký tự):</label>
                        <input type="text" class="form-control" rows="2" id="quiz-name" name="Quiz[quiz-name]" placeholder="Nhập tên đề thi" maxlength="255">
                        <label class="validate-error" for="name-error" id="name-error">Lỗi name</label>
                    </div>
                    <div class="form-group">
                        <label for="quiz-summary">Mô tả:</label>
                        <textarea class="form-control" rows="5" id="quiz-summary" name="Quiz[quiz-summary]" placeholder="Mô tả đề thi..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="quiz-time-open">Thời gian mở đề:</label>
                        <input type="text" class="form-control" placeholder="yyyy-mm-dd HH:mm:ss" id="quiz-time-open" name="Quiz[quiz-time-open]" style="width: 50%" disabled>
                        <label class="validate-error" for="time-open-error" id="time-open-error">Lỗi thời gian mở đề</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="quiz-enable-timeopen" name="Quiz[quiz-enable-timeopen]" onclick="enableSwitch('quiz-enable-timeopen', 'quiz-time-open');">
                        <label class="custom-control-label" for="quiz-enable-timeopen">Mở</label>
                    </div>
                    <div class="form-group mt-3">
                        <label for="quiz-time-close">Thời gian đóng đề:</label>
                        <input type="text" class="form-control" placeholder="yyyy-mm-dd HH:mm:ss" id="quiz-time-close" name="Quiz[quiz-time-close]" style="width: 50%" disabled>
                        <label class="validate-error" for="time-close-error" id="time-close-error">Lỗi thời gian đóng đề</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="quiz-enable-timeclose" name="Quiz[quiz-enable-timeclose]"  onclick="enableSwitch('quiz-enable-timeclose', 'quiz-time-close');">
                        <label class="custom-control-label" for="quiz-enable-timeclose">Mở</label>
                    </div>
                    <div class="form-group mt-3">
                        <label for="quiz-time-limit">Thời gian làm bài (phút):</label>
                        <input type="text" class="form-control" placeholder="Thời gian..." id="quiz-time-limit" name="Quiz[quiz-time-limit]" style="width: 30%" disabled>
                        <label class="validate-error" for="time-limit-error" id="time-limit-error">Lỗi thời gian làm bài</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="quiz-enable-timelimit" name="Quiz[quiz-enable-timelimit]" onclick="enableSwitch('quiz-enable-timelimit', 'quiz-time-limit');">
                        <label class="custom-control-label" for="quiz-enable-timelimit">Mở</label>
                    </div>
                    <div class="form-group mt-3">
                        <label for="quiz-overduehandling">Sau khi vượt quá thời gian:</label>
                        <select class="form-control" id="overduehandling" name="Quiz[overduehandling]" style="width: 50%">
                            <option value="1" selected>Nộp bài tự động</option>
                            <option value="2">Hủy kết quả làm bài</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quiz-time-attempts">Số lần thử (số lần làm bài tối đa là 10):</label>
                        <input type="text" class="form-control" placeholder="Số lần thử..." id="attempts-limit" name="Quiz[attempts-limit]" style="width: 30%">
                        <label class="validate-error" for="attempts-limit-error" id="attempts-limit-error">Lỗi số lần làm bài</label>
                    </div>
                    <div class="form-group mt-3">
                        <label for="quiz-grade-method">Cách tính điểm:</label>
                        <select class="form-control" id="quiz-grade-method" name="Quiz[quiz-grade-method]" style="width: 50%">
                            <option value="1" selected>Lần đầu tiên</option>
                            <option value="2">Lần cuối cùng</option>
                            <option value="3">Lần cao nhất</option>
                            <option value="4">Lần thấp nhất</option>
                            <option value="5">Điểm trung bình</option>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="quiz-review">Cho phép xem lại bài làm:</label>
                        <select class="form-control" id="quiz-review" name="Quiz[quiz-review]" style="width: 30%">
                            <option value="1" selected>Có</option>
                            <option value="2">Không</option>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="quiz-grade-method">Số câu hỏi mỗi trang:</label>
                        <select class="form-control" id="quiz-questions-per-page" name="Quiz[quiz-question-per-page]" style="width: 20%">
                            <option value="1" selected>1</option>
                            <?php for ($i = 2; $i <= 50; $i++)
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            ?>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="quiz-random-question" name="Quiz[quiz-random-question]">Cho phép xáo trộn vị trí câu hỏi
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="quiz-random-question-answer" name="Quiz[quiz-random-question-answer]">Cho phép đảo vị trí đáp án trong câu hỏi
                        </label>
                    </div>
                    <div class="form-group mt-3">
                        <label for="quiz-time-limit">Mật khẩu truy cập:</label>
                        <input type="password" class="form-control" placeholder="Nhập mật khẩu truy cập..." id="quiz-password" name="Quiz[quiz-password]" style="width: 50%" disabled>
                        <label class="validate-error" for="quiz-password-error" id="quiz-password-error">Lỗi mật khẩu</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="quiz-enable-pwd" name="Quiz[quiz-enable-pwd]"  onclick="enableSwitch('quiz-enable-pwd', 'quiz-password');">
                        <label class="custom-control-label" for="quiz-enable-pwd">Bảo vệ đề bằng mật khẩu ?</label>
                    </div>
                    <div class="form-group mt-3">
                        <label for="quiz-result-method">Phương thức nhận kết quả:</label>
                        <select class="form-control" id="quiz-result-method" name="Quiz[quiz-result-method]" style="width: 50%">
                            <option value="1" selected>Ngay sau khi làm bài</option>
                            <option value="2">Khi đề thi đóng</option>
                            <option value="3">Nhận qua email khi làm bài xong</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="quiz-accept-guest" name="Quiz[quiz-accept-guest]">Cho phép khách truy cập
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary" id="continue">Tiếp tục</button>
                    <button type="submit" class="btn btn-danger ml-4">Xóa bỏ</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function enableSwitch(switchTag, inputTag){
        var tag = document.getElementById(inputTag);
        if(document.getElementById(switchTag).checked)
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

    $('#quiz-time-open').datetimepicker({
        formatTime:'H:i',
        formatDate:'Y/m/d',
        step: 10,
        minDate: currentDate(),
        maxDate: currentDateClose(),
        minTime: currentTime(),
        value: currentDateTime()
    });

    $('#quiz-time-close').datetimepicker({
        formatTime:'H:i',
        formatDate:'Y/m/d',
        step: 10,
        minDate: currentDate(),
        minTime: currentTime(),
        value: currentCloseDateTime()
    });
    $('#quiz-time-limit').datetimepicker({
        datepicker:false,
        format:'H:i',
        step:5,
        minTime: '00:05',
        maxTime: '02:05',
        value: '00:30'
    });
</script>