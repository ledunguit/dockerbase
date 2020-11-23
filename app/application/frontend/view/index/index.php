<div class="container-fluid" style="padding: 0px; margin: 0px;">
    <div id="demo" class="carousel slide" data-ride="carousel">
        <ul class="carousel-indicators">
            <li data-target="#demo" data-slide-to="0" class="active"></li>
            <li data-target="#demo" data-slide-to="1"></li>
            <li data-target="#demo" data-slide-to="2"></li>
        </ul>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="publics/images/website/1.jpg" alt="Los Angeles" style="width:100%">
                <div class="carousel-caption">
                    <h3><?php echo $this->info->homename ?></h3>
                    <p><?php echo $this->info->homeshort ?></p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="publics/images/website/2.jpg" alt="Los Angeles" style="width:100%">
                <div class="carousel-caption">
                    <h3><?php echo $this->info->homename ?></h3>
                    <p><?php echo 'Powered by: ' . $this->info->poweredby ?></p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="publics/images/website/3.jpg" alt="Los Angeles" style="width:100%">
                <div class="carousel-caption">
                    <h3><?php echo $this->info->homename ?></h3>
                    <p><?php echo 'Powered by: ' . $this->info->poweredby ?></p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#demo" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#demo" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
    </div>
</div>
<div class="wrapper">
    <div class="main-content p-4">
        <h2 class="text-danger">Thông báo</h2>
        <div class="row">
        <?php
        $i = 1;
        foreach ($this->notifications as $key) {
            echo '
            <div class="media-body col-sm-6">
                <h5><a class="text-findhouse" href="' . \Venus\Venus::$baseUrl . '/notification/' . $key['id'] . '">' . $key['title'] . '</a></h5>
                <p>' . $key['description'] . '</p>
        </div>';
            $i++;
            if ($i > 2) {
                break;
            }
        } ?>
    </div></div>
    <div class="main-content pt-5 pb-5 bg-chiro text-light">
        <h2>Thống kê chung</h2>
        <div class="container">
            <div class="card-deck">
                <div class="card bg-chiro border-none">
                    <div class="card-body text-center">
                        <img src="<?= \Venus\Venus::$baseUrl ?>/publics/images/icon/category.png" alt="category"
                             width="64px" height="64px"/>
                        <p class="card-text text-light"><?= $this->allStatis['catesCount'] ? $this->allStatis['catesCount'] : "Có lỗi dữ liệu" ?>
                            danh mục</p>
                    </div>
                </div>
                <div class="card bg-chiro border-none">
                    <div class="card-body text-center">
                        <img src="<?= \Venus\Venus::$baseUrl ?>/publics/images/icon/exam.png" alt="exam" width="64px"
                             height="64px"/>
                        <p class="card-text text-light"><?= $this->allStatis['quizsCount'] ? $this->allStatis['quizsCount'] : "Có lỗi dữ liệu" ?>
                            đề thi</p>
                    </div>
                </div>
                <div class="card bg-chiro border-none">
                    <div class="card-body text-center">
                        <img src="<?= \Venus\Venus::$baseUrl ?>/publics/images/icon/graduated.png" alt="users"
                             width="64px" height="64px"/>
                        <p class="card-text text-light"><?= $this->allStatis['usersCount'] ? $this->allStatis['usersCount'] : "Có lỗi dữ liệu" ?>
                            người dùng</p>
                    </div>
                </div>
                <div class="card bg-chiro border-none">
                    <div class="card-body text-center">
                        <img src="<?= \Venus\Venus::$baseUrl ?>/publics/images/icon/learning.png" alt="attempts"
                             width="64px" height="64px"/>
                        <p class="card-text text-light"><?=$this->allStatis['attemptsCount']? $this->allStatis['attemptsCount'] : "0"?>
                            lượt làm bài</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-content pt-5">
        <div class="left">
            <h2>Đề thi mới nhất</h2>
            <ul class="list-group list-group-flush">
                <?php $i = 0; ?>
                <?php foreach ($this->recentQuiz as $key): ?>
                <?php
                $i++;
                if ($i % 2 == 0)
                    $class = 'odd';
                else $class = 'even';
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-center <?php echo ' ' . $class . '"'; ?>>
                        <div class=" left
                ">
                <?php
                $path = './publics/images/categories/' . $key['categoryShortName'] . '.png';
                if (getimagesize($path))
                    $imgPath = $path;
                else
                    $imgPath = './publics/images/icon/exam.png';
                ?>
                <img src="<?php echo $imgPath; ?>"/>
        </div>
        <div class="right">
            <a href="<?= \Venus\Venus::$baseUrl ?>/quiz/enroll/<?php echo $key['id']; ?>"
               class="h5"><?php echo $key['name']; ?></a>
            <div class="row">
                <div class="col-sm-12"><?php echo $key['summary']; ?></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <i class="fas fa-file mr-2 text-info"></i><b>Danh mục:</b> <a class="text-body"
                                                                                  href="<?= \Venus\Venus::$baseUrl ?>/categories/<?php echo $key['categoryShortName']; ?>"><?php echo $key['categoryName']; ?></a>
                </div>
            </div>
            <div class="row" style="margin-top:10px;">
                <div class="col-sm-4">
                    <i class="fas fa-code mr-2 text-danger"></i><b>Mã đề:</b> <?php echo $key['code']; ?>
                </div>
                <div class="col-sm-4">
                    <i class="fas fa-question-circle mr-2 text-success"></i><b>Tổng số câu
                        hỏi:</b> <?php echo $key['numberOfQuestions']; ?>
                </div>
                <div class="col-sm-4">
                    <i class="fas fa-pencil-alt mr-2 text-warning"></i><b>Số lượt làm
                        bài:</b> <?php echo $key['numberOfAttempts']; ?>
                </div>
            </div>
        </div>
        </li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div class="right">
        <div class="card mt-4">
            <div class="card-header font-weight-bold"><i class="fas fa-user-friends mr-2"></i>Hoạt
                động gần đây
            </div>
            <div class="card-body">
                <ul>
                    <?php
                    foreach ($this->recentOnline as $key){
                        echo '<li><i class="fas fa-user-circle mr-2 text-success"></i><a href="'.Venus\Venus::$baseUrl.'/account/'.$key->id.'">'. $key->lastname . ' '. $key->firstname .'</a></li>';
                    }?>
                </ul>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header font-weight-bold"><i class="fas fa-user-friends mr-2"></i>Danh
                mục đề thi
            </div>
            <div class="card-body">
                <ol>
                    <?php
                    if($this->categories){
                        foreach($this->categories as $key){
                            echo '<li><a href="'.Venus\Venus::$baseUrl.'/categories/'.$key['shortname'].'">'.$key['name'].'</a> ('.$key['numberOfQuizzes'].' đề thi)</li>';
                        }
                    }?>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="bg-findhouse row m-auto p-4">
    <div class="container d-flex">
        <div class="col-sm-1 mb-4"><img src="<?= \Venus\Venus::$baseUrl ?>/publics/images/website/uit-white.png" style="width:80px"/></div>
        <div class="col-sm-11 h6 m-auto text-light">
            <p>Trường ĐH Công nghệ thông tin - ĐHQG TP.HCM</p>
            <p>Khoa Mạng máy tính & Truyền thông</p>
        </div>
    </div>
</div>