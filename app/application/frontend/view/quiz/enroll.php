<?php
$details = $this->quizDetails;
$timeopen = $details['timeopen'];
$timeclose = $details['timeclose'];
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
    $timeopen = date_format(date_create($details['timeopen']), 'd/m/yy H:i:s');
} else {
    $timeopen = 'Không có';
}
if ($timeclose != null) {
    $timeclose = date_format(date_create($details['timeclose']), 'd/m/yy H:i:s');
} else {
    $timeclose = 'Không có';
}
$attempt = ($details['attempt']) ? $details['attempt'] : 'Không giới hạn';
if (isset($this->myAttempts) && count($this->myAttempts) > 0) {
    $average = ($this->myAttempts[0]['average'] !== null) ? number_format(round($this->myAttempts[0]['average'], 2), 2) : null;
} else {
    $average = null;
}

use Venus\User; ?>
    <div class="container-fluid">
        <div class="main-content border-none">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
                <li class="breadcrumb-item"><a
                            href="<?= \Venus\Venus::$baseUrl ?>/categories/<?php echo $details['categoryShortName'] ?>"><?php echo $details['categoryName'] ?></a>
                </li>
                <li class="breadcrumb-item active"><a href=""><?php echo $details['name'] ?></a></li>
            </ul>
            <h2><?php echo $details['name'] ?></h2>
            <?php echo $details['summary'] ?>
            <div class="main-content border-none">
                <div class="row d-flex justify-content-center col-sm-6">
                    <table class="table table-bordered mt-4">
                        <tr>
                            <td class="font-weight-bold">Danh mục: </td>
                            <td><a href="<?= \Venus\Venus::$baseUrl ?>/categories/<?=$details['categoryShortName']?>"><?php echo $details['categoryName']?></a></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Tạo bởi: </td>
                            <td><a href="<?= \Venus\Venus::$baseUrl ?>/account/<?=$details['createdby']?>"><?php echo $details['createdByName']?></a></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Mã đề: </td>
                            <td><?php echo $details['code']?></td>
                        </tr>
                        <tr>
                            <?php if ($timeopen != '') echo '
                        <td class="font-weight-bold" style="width: 50%">Thời gian mở đề:</td>
                        <td>' . $timeopen . '</td>'; ?>
                        </tr>
                        <tr>
                            <?php if ($timeclose != '') echo '
                        <td class="font-weight-bold" style="width: 50%">Thời gian đóng đề:</td>
                        <td>' . $timeclose . '</td>'; ?>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Trạng thái:</td>
                            <td class=""><span class="badge badge-<?php echo $classBadge . '">' . $status; ?></span></td>
                    </tr>
                    <tr>
                        <td class=" font-weight-bold">Thời gian làm bài:
                            </td>
                            <td class=""><?php echo $details['timeLimitConverted']; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Số lần thử tối đa:</td>
                            <td class=""><?php echo $attempt; ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Cách tính điểm:</td>
                            <td class=""><?php
                                if($details['grademethod'] == 1){
                                    echo 'Lần đầu tiên';
                                }
                                if($details['grademethod'] == 2){
                                    echo 'Lần cuối cùng';
                                }
                                if($details['grademethod'] == 3){
                                    echo 'Lần cao nhất';
                                }
                                if($details['grademethod'] == 4){
                                    echo 'Lần thấp nhất';
                                }
                                if($details['grademethod'] == 5){
                                    echo 'Điểm trung bình';
                                }
                                ?></td>
                        </tr>
                        <?php
                        if ($average) {
                            echo '<tr>
                        <td class="font-weight-bold">Điểm trung bình:</td>
                        <td class="">' . $average .'</td>
                    </tr>';
                        } ?>

                    </table>
                    <?php
                    if ($status == 'Đang diễn ra' && (((isset($this->myAttempts) && count($this->myAttempts) < $attempt)) || !isset($this->myAttempts) || $attempt == 'Không giới hạn')) {
                        if ($details['password'] == null) {
                            if (!User::logged() && $details['acceptguest'] == 0) {
                                echo '<b style="color:red">Bạn cần đăng nhập để thực hiện đề thi !</b>';
                            } else if (!User::logged() && $details['acceptguest'] == 1) {
                                echo '<label for="demo">Nhập địa chỉ Email của bạn để nhận đáp án và giải thích:</label>
                            <div class="input-group mb-3">
                              <input type="text" class="form-control" placeholder="Nhập địa chỉ Email..." id="emailSubcribe" name="email">
                              <div class="input-group-append">
                                <span class="input-group-text">Only Email Accepted</span>
                              </div>
                            </div>';
                                echo '<button type="button" class="btn btn-primary" id="startQuiz">Bắt đầu</button></br>';
                            } else if ($details['password'] == null) {
                                echo '<button type="button" class="btn btn-primary" id="startQuiz">Bắt đầu</button></br>';
                            } else {
                                echo '<b style="color:red; text-align: center;">Đề thi được bảo vệ bằng mật khẩu. Nhập mật khẩu để tiếp tục</b>';
                                echo '<input type="text" id="passwordForQuiz" class="form-control" placeholder="Mật khẩu" style="margin-bottom: 20px" required><button class="btn btn-primary" type="submit" id="startWithPassword">Vào thi</button>';
                            }
                        } else {
                            if (!User::logged()) {
                                echo '<b style="color:red">Bạn cần đăng nhập để thực hiện đề thi !</b>';
                            } else if ($details['password'] == null) {
                                echo '<button type="button" class="btn btn-primary" id="startQuiz">Bắt đầu</button></br>';
                            } else {
                                echo '<b style="color:red; text-align: center;">Đề thi được bảo vệ bằng mật khẩu. Nhập mật khẩu để tiếp tục</b>';
                                echo '<input type="text" id="passwordForQuiz" class="form-control" placeholder="Mật khẩu" style="margin-bottom: 20px" required><button class="btn btn-primary" type="submit" id="startWithPassword">Vào thi</button>';
                            }
                        }
                    } ?>
                </div>
                <div class="row d-flex justify-content-center col-sm-6 alert-danger" id="error-status"
                     style="display: none !important;">Alo alo
                </div>
                <?php if (isset($this->myAttempts)): ?>
                    <h2 class="mt-4">Lịch sử làm bài</h2>
                <div class="table-responsive">
                    <table class="table table-bordered mt-4 text-center">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Thời gian nộp bài</th>
                            <th>Điểm số</th>
                            <th>Thang điểm 10</th>
                            <th>Xếp loại</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        $str = '';
                        foreach ($this->myAttempts as $attempt) {
                            $i++;
                            $colorRank = 'body';
                            if ($attempt['rank'] == 'Yếu')
                                $colorRank = 'danger';
                            if($attempt['inProgress'] == 'finished') {
                                $str .= '<tr class="alert-'.$colorRank.'"><td>' .$i. '</td>
                                <td>' . date_format(date_create($attempt['timesubmitted']), 'H:i:s d/m/yy') . '</td>
                                <td>' . round($attempt['grade'], 2) . ' / ' . round($attempt['sumgrade'], 2) . '</td>
                                <td class="font-weight-bold">' . round($attempt['gradeByBaseGrade'], 2) . '</td>
                                <td class="font-weight-bold text-' . $colorRank . '">' . $attempt['rank'] . '</td>
                                <td><a href="' . \Venus\Venus::$baseUrl . '/attempts/review/' . $attempt['id'] . '"><button type="button" class="btn btn-success">Xem</button></a></td>
                                </tr>';
                            }
                            else {
                                $str .= '<tr><td>' .$i. '</td>
                                <td>Chưa nộp bài</td>
                                <td>0</td>
                                <td>0</td>
                                <td>Chưa xếp loại</td>
                                <td><a href="' . \Venus\Venus::$baseUrl . '/attempts/progress/' . $attempt['id'] . '"><button type="button" class="btn bg-findhouse text-light">Tiếp tục</button></a></td>
                                </tr>';
                            }
                        }
                        echo $str;
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                <div id="confirm-start" title="Thông báo từ hệ thống." style="display: none">
                    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Bạn chắc
                        chắn muốn vào làm bài?</p>
                </div>
                <div id="confirm-start-with-password" title="Thông báo từ hệ thống." style="display: none">
                    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Bạn chắc
                        chắn muốn vào làm bài với password đã cung cấp?</p>
                </div>
            </div>
        </div>
    </div>

<?php if (User::logged()): ?>
    <script>
        var isValid;
        $("#startQuiz").click(function (event) {
            var currentUrl = window.location.pathname;
            currentUrl = currentUrl.split('/');
            var currentQuizId = currentUrl[3];
            $("#confirm-start").dialog({
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "Xác nhận": function () {
                        var dataInfo = {};
                        dataInfo['quizid'] = currentQuizId;
                        dataInfo['userid'] = <?=User::getInfo()->id?>;
                        $.ajax({
                            type: 'POST',
                            cache: false,
                            url: '/ajax/checkDuplicateAttemptUserLogged',
                            data: {dataAjax: JSON.stringify(dataInfo)},
                            success: function (response) {
                                var res = JSON.parse(response);
                                var result = res.status;
                                if (result != 'not') {
                                    $.post("/ajax/insertAttemptWhenUserLogged", {quizId: currentQuizId}, function (data) {
                                        isValid = false;
                                        if (data == 'null') {
                                            $("#confirm-start").dialog("close");
                                            $("#error-status").html("Đã xảy ra lỗi. Vui lòng kiểm tra lại !");
                                            $("#error-status").css("display", "block");
                                        } else {
                                            isValid = true;
                                        }
                                        if (isValid) {
                                            window.location.href = encodeURI("/attempts/progress/" + data);
                                        }
                                    });
                                } else {
                                    $("#confirm-start").dialog("close");
                                    $("#error-status").html("Vui lòng nộp bài trước khi thi lần khác!");
                                    $("#error-status").css("display", "block");
                                }
                            }
                        });
                        event.preventDefault();
                    },
                    "Hủy bỏ": function () {
                        $(this).dialog("close");
                    }
                }
            });
        });

        $("#startWithPassword").click(function (e) {
            var currentUrl = window.location.pathname;
            currentUrl = currentUrl.split('/');
            var currentQuizId = currentUrl[3];
            var passwordInput = $("#passwordForQuiz").val();
            if (passwordInput == "" || passwordInput == null) {
                $("#error-status").html("Vui lòng nhập password của đề để xác minh quyền truy cập!");
                $("#error-status").css("display", "block");
            } else {
                $("#confirm-start-with-password").dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Xác nhận": function () {
                            var dataInfo = {};
                            dataInfo['quizid'] = currentQuizId;
                            dataInfo['userid'] = <?=User::getInfo()->id?>;
                            $.ajax({
                                type: 'POST',
                                cache: false,
                                url: '/ajax/checkDuplicateAttemptUserLogged',
                                data: {dataAjax: JSON.stringify(dataInfo)},
                                success: function (response) {
                                    var res = JSON.parse(response);
                                    var result = res.status;
                                    if (result != 'not') {
                                        var dataInfo1 = {};
                                        dataInfo1['quizid'] = currentQuizId;
                                        dataInfo1['password'] = MD5(passwordInput);
                                        $.ajax({
                                            type: 'POST',
                                            cache: false,
                                            url: '/ajax/checkPasswordOfQuiz',
                                            data: {dataAjax: JSON.stringify(dataInfo1)},
                                            success: function (response) {
                                                var res = JSON.parse(response)
                                                var result = res.status;
                                                if (result == "true") {
                                                    window.location.href = "/attempts/progress/" + res.attemptid;
                                                } else if (result == "WrongPass") {
                                                    $("#confirm-start-with-password").dialog("close");
                                                    $("#error-status").html("Password của đề không chính xác, vui lòng kiểm tra lại!");
                                                    $("#error-status").css("display", "block");
                                                } else if (result == "SystemError") {
                                                    $("#confirm-start-with-password").dialog("close");
                                                    $("#error-status").html("Lỗi hệ thống, vui lòng liên hệ quản trị của bạn!");
                                                    $("#error-status").css("display", "block");
                                                }
                                            }
                                        });
                                    } else {
                                        $("#confirm-start-with-password").dialog("close");
                                        $("#error-status").html("Đã xảy ra lỗi, bạn không thể thi lần mới khi chưa nộp bài!");
                                        $("#error-status").css("display", "block");
                                    }
                                }
                            });
                            e.preventDefault();
                        },
                        "Hủy bỏ": function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        })
    </script>
<?php else: ?>
    <script>
        var isValid;
        $("#startQuiz").click(function (event) {
            if ($("#emailSubcribe").val() == null || $("#emailSubcribe").val() == "") {
                $("#error-status").html("Để nhận kết quả bài thi thì bạn cần nhập email!");
                $("#error-status").css("display", "block");
            } else {
                if (!checkEmail($("#emailSubcribe").val())) {
                    $("#error-status").html("Email bạn vừa nhập không hợp lệ!");
                    $("#error-status").css("display", "block");
                } else {
                    var currentUrl = window.location.pathname;
                    currentUrl = currentUrl.split('/');
                    var currentQuizId = currentUrl[3];
                    $("#confirm-start").dialog({
                        resizable: false,
                        height: "auto",
                        width: 400,
                        modal: true,
                        buttons: {
                            "Xác nhận": function () {
                                var dataInfo = {};
                                dataInfo['quizid'] = currentQuizId;
                                dataInfo['guest'] = $("#emailSubcribe").val();
                                $.ajax({
                                    type: 'POST',
                                    cache: false,
                                    url: '/ajax/checkDuplicateAttemptGuest',
                                    data: {dataAjax: JSON.stringify(dataInfo)},
                                    success: function (response) {
                                        var res = JSON.parse(response);
                                        var result = res.status;
                                        if (result != 'not') {
                                            $.ajax({
                                                type: 'POST',
                                                cache: false,
                                                url: '/ajax/insertGuestAttempt',
                                                data: {dataAjax: JSON.stringify(dataInfo)},
                                                success: function (response) {
                                                    var res = JSON.parse(response);
                                                    var result = res.status;
                                                    if (result == "true") {
                                                        window.location.href = "/attempts/progress/" + res.attemptid;
                                                    } else {
                                                        $("#confirm-start").dialog('close');
                                                        $("#error-status").html("Lỗi hệ thống! Liên hệ quản trị của bạn!");
                                                        $("#error-status").css("display", "block");
                                                    }
                                                }
                                            });
                                        } else {
                                            $("#confirm-start").dialog('close');
                                            $("#error-status").html("Email này đã có người sử dụng và đang trong quá trình làm bài, vui lòng thử lại sau!");
                                            $("#error-status").css("display", "block");
                                        }
                                    }
                                });
                                event.preventDefault();
                            },
                            "Hủy bỏ": function () {
                                $(this).dialog("close");
                            }
                        }
                    });
                }
            }
        });
    </script>
<?php endif; ?>