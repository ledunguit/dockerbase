<div class="container-fluid">
    <div class="main-content">
        <h2 class="text-danger">Danh sách thông báo</h2>
        <ul class="list-group list-group-flush">
            <?php foreach ($this->list as $key) {
                echo '<li class="list-group-item"><h5><a href="'.Venus\Venus::$baseUrl.'/notification/'.$key['id'].'">'.$key['title'].'</a></h5>
                <p>'.$key['description'].'</p>
                <b>Ngày đăng: </b>'.date_format(date_create($key['timecreated']), "H:i:s d/m/yy").'<br>
                <b>Đăng bởi: </b><a href="'.Venus\Venus::$baseUrl.'/account/'.$key['createdBy'].'">'.$key['author'].'</a>
            </li>';
            } ?>
        </ul>
    </div>
</div>