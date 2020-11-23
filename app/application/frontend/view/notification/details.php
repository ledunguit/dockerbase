<div class="container-fuild">
    <div class="main-content">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= \Venus\Venus::$baseUrl ?>/notification">Các thông báo</a></li>
            <li class="breadcrumb-item active"><a href="#"><?php echo $this->item['title'] ?></a></li>
        </ul>
        <div class="left">
            <p class="h5"><?php echo $this->item['title']?></p>
            <p><?php echo date_format(date_create($this->item['timecreated']), "H:i:s d/m/yy")?></p>
            <hr>
            <p><?php echo htmlspecialchars_decode($this->item['content'])?></p>
        </div>
        <div class="right">
            <h4 class="text-uppercase">Thông báo khác</h4>
            <ul class="list-group list-group-flush">
                <?php
                $i = 0;
                foreach ($this->list as $key) {
                    $i++;
                    if($i > 8)
                        break;
                    echo '<li class="list-group-item"><a href="'.Venus\Venus::$baseUrl.'/notification/'.$key['id'].'">'.$key['title'].'</a></li>';
                } ?>
            </ul>
        </div>
    </div>
</div>