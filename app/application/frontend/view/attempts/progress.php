<?php
$pageUrl = \Venus\Venus::$baseUrl . '/attempts/progress/' . $this->attempt['id'];
$numberOfQuestions = count($this->questions);
$quesPerPage = $this->quiz['questionsperpage'];
$numberOfPage = ceil($numberOfQuestions / $quesPerPage);
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    if ((int)$page > $numberOfPage || (int)$page < 1)
        $page = 1;
} else {
    $page = 1;
}
$str = '';
if ($page == 1) {
    $from = 0;
    $end = $quesPerPage;
} else {
    $from = $quesPerPage * ($page - 1);
    $end = $from + $quesPerPage;
}
$pagePrev = ($page <= 1) ? 1 : ($page - 1);
$pageNext = ($page < $numberOfPage) ? ($page + 1) : $numberOfPage;
?>
<div class="title-progress row justify-content-center">
    <div class="col-sm-9"><b>Đề thi:</b> <a
                href="<?php echo \Venus\Venus::$baseUrl . '/quiz/enroll/' . $this->quiz['id']; ?>"
                class="text-white"><?php echo $this->quiz['name']; ?></a></div>
    <div class="col-sm-3"><b>Mã đề:</b> <span class="text-warning"><?php echo $this->quiz['code']; ?></span></div>
</div>
<div class="container-fluid">
    <div class="row attempt-progress d-flex justify-content-center">
        <div class="col-sm-8">
            <?php for ($i = 0; $i < $numberOfQuestions; $i++): ?>
                <?php
                if ($i < $from)
                    continue;
                if ($i >= $end)
                    break;
                ?>
                <div class="row mt-3" id="ques-<?= $i ?>">
                    <div class="col-sm-2 text-center">
                        <div class="h6 alert-primary p-3">Câu hỏi số <?= ++$i ?>:</div>
                        <p><b>Điểm: </b><?= $this->questions[--$i]['grade'] ?></p>
                        <hr>
                    </div>
                    <div class="col-sm-10 question-text" id="ques-content-<?= $i ?>">
                        <div><?= htmlspecialchars_decode($this->questions[$i]['questiontext']) ?></div>
                        <?php
                        $n = count($this->questions[$i]['answers']);
                        for ($j = 0; $j < $n; $j++): ?>

                            <?php
                            $result = '';
                            if (is_array($this->choice[$i]['chose'])) {
                                foreach ($this->choice[$i]['chose'] as $item) {
                                    if ($this->questions[$i]['answers'][$j]['id'] == $item) {
                                        $result = ' checked';
                                    }
                                }
                            } else {
                                if ($this->questions[$i]['answers'][$j]['id'] == $this->choice[$i]['chose']) {
                                    $result = ' checked';
                                }
                            }
                            ?>
                            <?php $type = 'radio'; ?>
                            <?php if ($this->questions[$i]['type'] == 2): ?>
                                <?php $type = 'checkbox'; ?>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="<?= $type ?>" class="form-check-input"
                                               name="opt<?= $type ?>-<?= $i ?>-<?= $j ?>"
                                               id="answer-<?= $type ?>-<?= $this->questions[$i]['id'] . '-' . $this->questions[$i]['answers'][$j]['id'] ?>" <?= $result ?>>
                                        <?= htmlspecialchars_decode($this->questions[$i]['answers'][$j]['answer']) ?>
                                    </label>
                                </div>
                            <?php else: ?>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="<?= $type ?>" class="form-check-input"
                                               name="opt<?= $type ?>-<?= $i ?>"
                                               id="answer-<?= $type ?>-<?= ($i + 1) . '-' . ($j + 1) ?>" <?= $result ?>>
                                        <?= htmlspecialchars_decode($this->questions[$i]['answers'][$j]['answer']) ?>
                                    </label>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <hr>
                    </div>
                </div>
            <?php endfor; ?>
            <div class="d-flex">
                <a href="<?php echo $pageUrl . '?page='.$pagePrev;?>">
                    <button type="button" class="btn btn-info ml-5">Trang trước</button>
                </a>
                <a class="ml-auto mr-5" href="<?php echo $pageUrl . '?page='.$pageNext;?>">
                    <button type="button" class="btn btn-info">Trang sau</button>
                </a>
            </div>
        </div>
        <div class="col-sm-3 mt-4">
            <div class="h3 d-flex justify-content-center mb-3 text-danger" id="time-left"></div>
            <div class="overview-table">
                <?php
                $boxNumber = 0;
                foreach ($this->questions as $key) {
                    if (($this->choice[$boxNumber]['chose'] != "" && !is_array($this->choice[$boxNumber]['chose'])) || (is_array($this->choice[$boxNumber]['chose']) && $this->choice[$boxNumber]['chose'][0] != null)) {
                        $class = "bottom-filled";
                    } else {
                        $class = "bottom-empty";
                    }
                    $boxNumber++;
                    echo '<a href=""><div class="block">
                    <div class="top">' . $boxNumber . '</div>
                    <div id="box-' . $key['id'] . '" class="bottom ' . $class . '"></div>
                    </div></a>';
                } ?>
            </div>
            <div class="d-flex justify-content-center">
                <?php if (!$this->is_guest): ?>
                    <button class="btn btn-primary mt-3" type="submit" id="btnSubmitQuiz">Nộp bài</button>
                <?php else: ?>
                    <button class="btn btn-primary mt-3" type="submit" id="btnSubmitQuizGuest">Nộp bài</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="footer text-light mt-4 text-center">© 2020 nt208.l11.mmcl - Made with love.</div>
<script>
    function checkTime(i) {
        if (i < 10) {
            i = "0" + i
        }
        ;
        return i;
    }

    function getTimeLeft() {
        var dataInput = {};
        var currentUrl = window.location.pathname;
        currentUrl = currentUrl.split('/');
        var currentAttemptId = currentUrl[3];
        dataInput['quizid'] = <?=$this->quiz['id']?>;
        dataInput['attemptid'] = currentAttemptId;
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/ajax/getTimeLeftForAttempt',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function (response) {
                var res = JSON.parse(response);
                if (res.timeleft != "Hết thời gian") {
                    $("#time-left").html(res.timeleft.h + ":" + checkTime(res.timeleft.i) + ":" + checkTime(res.timeleft.s));
                } else {
                    $("#time-left").html(res.timeleft);
                    window.location.href = '<?php echo (isset($this->attempt['userid'])) ? "/quiz/enroll/" . $this->attempt['quizid'] : "/attempts/review/" . $this->attempt['id']?>';
                }
            }
        });
        var t = setTimeout(getTimeLeft, 1000);
    }

    getTimeLeft();

    <?php for ($i = 0; $i < $numberOfQuestions; $i++): ?>
    <?php for ($j = 0; $j < count($this->questions[$i]['answers']); $j++): ?>
    <?php if($this->questions[$i]['type'] == 1): ?>
    <?php $type = 'radio'; ?>
    $("#answer-<?=$type?>-<?=($i + 1) . '-' . ($j + 1)?>").change(function () {
        var currentUrl = window.location.pathname;
        currentUrl = currentUrl.split('/');
        var currentAttemptId = currentUrl[3];
        var quesId = <?=$this->questions[$i]['id']?>;
        var answerId = <?=$this->questions[$i]['answers'][$j]['id']?>;
        saveTempAnswerRadio(currentAttemptId, quesId, answerId);
    })
    <?php else: ?>
    <?php $type = 'checkbox'; ?>
    $("#answer-<?=$type?>-<?=$this->questions[$i]['id'] . '-' . $this->questions[$i]['answers'][$j]['id']?>").change(function () {
        var a = [];
        var currentUrl = window.location.pathname;
        currentUrl = currentUrl.split('/');
        var currentAttemptId = currentUrl[3];
        var quesId = <?=$this->questions[$i]['id']?>;
        <?php for ($k = 0; $k < count($this->questions[$i]['answers']); $k++): ?>
        if ($("#answer-<?=$type?>-<?=$this->questions[$i]['id'] . '-' . $this->questions[$i]['answers'][$k]['id']?>").prop('checked') == true) {
            a.push(<?=$this->questions[$i]['answers'][$k]['id']?>);
        }
        <?php endfor; ?>
        saveTempAnswerCheckbox(currentAttemptId, quesId, a);
    })
    <?php endif; ?>
    <?php endfor; ?>
    <?php endfor; ?>

    function saveTempAnswerRadio(attemptid, quesid, chose) {
        var dataInput = {};
        dataInput['attemptid'] = attemptid;
        dataInput['quesid'] = quesid;
        dataInput['chose'] = chose;
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/ajax/saveTempAnswerRadio',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function (response) {
                var box = document.getElementById('box-' + quesid);
                box.classList.remove('bottom-empty');
                box.classList.add('bottom-filled');
            }
        });
    }

    function saveTempAnswerCheckbox(attempId, quesId, chose) {
        chose.unshift(attempId, quesId);
        var dataInput = Object.assign({}, chose);
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/ajax/saveTempAnswerCheckbox',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function (response) {
                var box = document.getElementById('box-' + quesId);
                box.classList.remove('bottom-empty');
                box.classList.add('bottom-filled');
            }
        });
    }

    $("#btnSubmitQuiz").click(function () {
        var currentUrl = window.location.pathname;
        currentUrl = currentUrl.split('/');
        var currentAttemptId = currentUrl[3];
        var dataInput = {};
        dataInput['attemptid'] = currentAttemptId;
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/ajax/submitQuiz',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function (response) {
            }
        });
    })

    $("#btnSubmitQuizGuest").click(function () {
        var currentUrl = window.location.pathname;
        currentUrl = currentUrl.split('/');
        var currentAttemptId = currentUrl[3];
        var dataInput = {};
        dataInput['attemptid'] = currentAttemptId;
        dataInput['guest'] = '<?php echo $this->attempt['guest']?>';
        $.ajax({
            type: 'POST',
            cache: false,
            url: '/ajax/submitQuizGuest',
            data: {dataAjax: JSON.stringify(dataInput)},
            success: function (response) {
            }
        });
    })

</script>