<div class="container-fluid">
    <div class="main-content">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>/dashboard">Bảng điều khiển</a></li>
            <li class="breadcrumb-item active"><a href="">Bảng điểm cá nhân</a></li>
        </ul>
        <h2 class="d-flex justify-content-center text-danger">Bảng điểm cá nhân</h2>
        <p class="text-center"><b>Điểm trung bình:</b> <?php echo number_format(round($this->average, 2), 2) . ' / 10.0';?></p>
        <div class="d-flex">
            <div class="ml-auto mb-3">
                <a href="<?= \Venus\Venus::$baseUrl ?>/dashboard/exportgrade?method=I"><button type="button" class="btn btn-light mr-2"><i class="fas fa-file-pdf mr-2 text-danger"></i>Xuất PDF</button></a>
                <a href="<?= \Venus\Venus::$baseUrl ?>/dashboard/exportgrade?method=D"><button type="button" class="btn btn-light mr-2"><i class="fas fa-download mr-2 text-success"></i>Tải xuống</button></a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th>Tên đề thi</th>
                    <th>Số lần thi</th>
                    <th>Trung bình</th>
                    <th>Tỉ lệ</th>
                    <th>Xếp loại</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $str = '';
                $i = 0;
                foreach ($this->rows as $key) {
                    $i++;
                    if($key['rank'] == 'Yếu'){
                        $color = 'danger';
                    }
                    else {
                        $color = 'body';
                    }
                    $str .= '<tr><td class="text-center">'.$i.'</td>
                <td>'.$key['name'].'</td>
                <td class="text-center">'.$key['numberOfAttempts'].'</td>
                <td class="text-center">'.number_format(round($key['grade'], 2), 2).' / '.number_format(round($key['sumgrade'], 2), 1).'</td>
                <td class="text-center">'.$key['percentage'].'%</td>
                <td class="text-'.$color.' text-center">'.$key['rank'].'</td>
                <td class="text-center"><a href="'.\Venus\Venus::$baseUrl.'/quiz/enroll/'.$key['quizId'].'"><button type="button" class="btn btn-success">Xem</button></a></td></tr>';
                }
                echo $str;
                ?>

                </tbody>
            </table>
        </div>
    </div>
</div>