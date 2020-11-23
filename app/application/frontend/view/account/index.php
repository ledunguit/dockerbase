<?php
$user = $this->user;
?>
<div class="container-fluid">
    <div class="main-content border-none p-4">
        <div class="h3 text-uppercase text-info"><i class="fas fa-info-circle"></i> Thông tin cá nhân</div>
        <div class="mt-4">
            <div class="table-responsive">
                <table class="table table-bordered" style="width: 100%;">
                    <tr>
                        <td width="15%" class="font-weight-bold">Họ và tên:</td>
                        <td width="35%"><?php echo $user->lastname . ' ' . $user->firstname; ?></td>
                        <td width="15%" class="font-weight-bold">Ngày sinh:</td>
                        <td><?php echo date_format(date_create($user->birthday), "d/m/Y"); ?></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Giới tính:</td>
                        <td><?php
                            if ($user->gender == 0)
                                echo 'Nữ';
                            else if ($user->gender == 1)
                                echo 'Nam';
                            else
                                echo 'Khác';
                            ?></td>
                        <td class="font-weight-bold">Địa chỉ Email:</td>
                        <td><?php echo $user->email; ?></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Số điện thoại:</td>
                        <td><?php echo $user->phone; ?></td>
                        <td class="font-weight-bold">Địa chỉ:</td>
                        <td><?php echo $user->address; ?></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Số CMND:</td>
                        <td><?php echo 'Chưa cập nhật' ?></td>
                        <td class="font-weight-bold">Cơ quan:</td>
                        <td><?php echo $user->organization; ?></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Bộ phận:</td>
                        <td><?php echo $user->department; ?></td>
                        <td class="font-weight-bold">Trạng thái:</td>
                        <td class="font-weight-bold">Bình thường</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Truy cập lần đầu:</td>
                        <td><?php echo date_format(date_create($user->firstaccess), 'H:i:s d/m/yy'); ?></td>
                        <td class="font-weight-bold">Đăng nhập lần cuối:</td>
                        <td><?php if ($user->lastlogin != null)
                                echo date_format(date_create($user->lastlogin), 'h:i:s d/m/yy'); ?>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="main-content border-none p-4">
        <h3 class="text-uppercase">Giới thiệu bản thân</h3>
        <div class="question-text"><?php echo htmlspecialchars_decode($user->description); ?></div>
    </div>
</div>
