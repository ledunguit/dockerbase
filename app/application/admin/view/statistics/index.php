<div class="container-fluid">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header font-weight-bold">Thông tin chung</div>
                    <div class="card-body">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. A ad at, beatae debitis placeat sed.
                        Alias asperiores consequatur, ducimus fugit nulla quae quia quo quod ratione voluptas? Expedita,
                        laudantium neque.
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header font-weight-bold">Thông tin</div>
                    <div class="card-body">
                        <li><b>Đề thi bị ẩn:</b> <?php echo isset($this->numberOfHiddenQuizzes)?$this->numberOfHiddenQuizzes:0?></li>
                        <li><b>Danh mục bị ẩn:</b> <?php echo isset($this->numberOfHiddenCategories)?$this->numberOfHiddenCategories:0?></li>
                        <li><b>Thành viên bị khóa:</b> <?php echo isset($this->numberOfBlockedUsers)?$this->numberOfBlockedUsers:0?></li>
                        <li><b>Số đề thi hệ thống:</b> <?php echo isset($this->numberOfSystemQuiz)?$this->numberOfSystemQuiz:0?></li>
                        <li><b>Số đề thi đóng góp:</b> <?php echo isset($this->numberOfContributedQuiz)?$this->numberOfContributedQuiz:0?></li>
                        <li><b>Tổng sổ câu hỏi:</b> <?php echo isset($this->numberOfQuestions)?$this->numberOfQuestions:0?></li>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-header text-findhouse font-weight-bold">Số lượt thi trong 2 tuần qua</div>
                    <div class="card-body">
                        <canvas id="chart1"></canvas>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-bordered text-center">
                        <thead>
                        <tr class="bg-secondary text-light">
                            <th>#</th>
                            <th>Hôm nay</th>
                            <th>Tháng này</th>
                            <th>Năm nay</th>
                            <th>Tổng cộng</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="font-weight-bold text-left">Thành viên</td>
                            <td><?php echo isset($this->numberOfUsersToday)?$this->numberOfUsersToday:0?></td>
                            <td><?php echo isset($this->numberOfUsersThisMonth)?$this->numberOfUsersThisMonth:0?></td>
                            <td><?php echo isset($this->numberOfUsersThisYear)?$this->numberOfUsersThisYear:0?></td>
                            <td><?php echo isset($this->numberOfUsers)?$this->numberOfUsers:0?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-left">Khách thi</td>
                            <td><?php echo isset($this->numberOfGuestToday)?$this->numberOfGuestToday:0?></td>
                            <td><?php echo isset($this->numberOfGuestThisMonth)?$this->numberOfGuestThisMonth:0?></td>
                            <td><?php echo isset($this->numberOfGuestThisYear)?$this->numberOfGuestThisYear:0?></td>
                            <td><?php echo isset($this->numberOfGuest)?$this->numberOfGuest:0?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-left">Đề thi</td>
                            <td><?php echo isset($this->numberOfQuizToday)?$this->numberOfQuizToday:0?></td>
                            <td><?php echo isset($this->numberOfQuizThisMonth)?$this->numberOfQuizThisMonth:0?></td>
                            <td><?php echo isset($this->numberOfQuizThisYear)?$this->numberOfQuizThisYear:0?></td>
                            <td><?php echo isset($this->numberOfQuiz)?$this->numberOfQuiz:0?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-left">Lượt thi</td>
                            <td><?php echo isset($this->numberOfAttemptsToday)?$this->numberOfAttemptsToday:0?></td>
                            <td><?php echo isset($this->numberOfAttemptsThisMonth)?$this->numberOfAttemptsThisMonth:0?></td>
                            <td><?php echo isset($this->numberOfAttemptsThisYear)?$this->numberOfAttemptsThisYear:0?></td>
                            <td><?php echo isset($this->numberOfAttempts)?$this->numberOfAttempts:0?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header font-weight-bold">Tỉ lệ đề thi theo danh mục</div>
                    <div class="card-body">
                        <canvas id="chart2"></canvas>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header font-weight-bold">Đề thi nổi bật</div>
                    <div class="card-body">
                        <ol>
                            <?php
                            foreach ($this->popularQuiz as $key){
                                echo '<li><a href="'.\Venus\Venus::$adminUrl . '/quiz/enroll/'.$key->id.'">'.$key->name.'</a> ('.$key->number.' lượt thi)</li>';
                            }?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-sm-8">

            </div>
        </div>
    </div>
</div>
<script>
    var ctx = document.getElementById('chart1');
    chart1 = Object.values(<?php echo json_encode($this->attemptTimeTwoWeeks)?>);
    var data = [];
    var label = [];
    for(i = 0; i < chart1.length; i++){
        label.push(chart1[i].date);
        data.push(chart1[i].count);
    }
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: label,
            datasets: [{
                label: 'Số lượt thi',
                data: data,
                lineTension: 0.1,
                fill: true,
                backgroundColor: [
                    'rgba(255, 206, 86, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                ]
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    dataPie = Object.values(<?php echo json_encode($this->dataForPie)?>);
    var data = [];
    var label = [];
    for(i = 0; i < dataPie.length; i++){
        label.push(dataPie[i].name);
        data.push(dataPie[i].number);
    }
    var ctx = document.getElementById('chart2');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: label,
            datasets: [{
                label: 'Tỉ lệ',
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
    });
</script>