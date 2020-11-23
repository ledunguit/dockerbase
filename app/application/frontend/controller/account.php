<?php
namespace Application\Frontend\Controller;
use \Venus\User as UserLib;
use \Venus\Venus as VenusLib;
use \Venus\Request as RequestLib;
use \Venus\Session as SessionLib;
use \Application\Frontend\Model\Users as UserModel;

class Account extends Base {
    public function index($param = null) {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl . '/login?return=' . VenusLib::$baseUrl . '/account/');
        }
        if($param != null) {
            if($param[0] == 1) {
                $this->view->render('attempts/error');
                return;
            }
            $this->view->user = (new UserModel())->getInfoById($param[0]);
            if ($this->view->user != null) {
                $this->view->setTitle('Hồ sơ: ' . $this->view->user->lastname . ' ' . $this->view->user->firstname);
                $this->view->render('account/index');
            } else {
                $this->view->setLayout('error');
                $this->view->render('index/error');
            }
        }
        else {
            $this->view->setTitle('Trang cá nhân của tôi');
            $this->view->user = UserLib::getInfo();
            $this->view->render('account/index');
        }

    }

    public function edit() {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl. '/login?return=' . VenusLib::$baseUrl . '/account/edit');
        }
        $this->view->setTitle('Chỉnh sửa hồ sơ');
        if (isset($_POST['btnUpdate'])) {
            if (isset($_FILES['fileUpload'])) {
                $avtUri = './publics/images/avatar/' . UserLib::getInfo()->id . '.jpg';
                $avtUri1  = './publics/images/avatar/' . UserLib::getInfo()->id . '.png';
                $ext = pathinfo($_FILES['fileUpload']['name'], PATHINFO_EXTENSION);
                if ($ext != "") {
                    if ($ext == 'jpg' || $ext == 'png') {
                        if ($_FILES['fileUpload']['size'] == 0) {
                            $this->view->error['avt'] = "File size không hợp lệ!";
                        } else {
                            move_uploaded_file($_FILES['fileUpload']['tmp_name'], './publics/images/avatar/' . UserLib::getInfo()->id . '.' . $ext);
                            if ($ext === 'jpg') {
                                if (file_exists($avtUri1)) {
                                    unlink($avtUri1);
                                }
                            } elseif ($ext === 'png'){
                                if (file_exists($avtUri)) {
                                    unlink($avtUri);
                                }
                            }
                        }
                    } else {
                        $this->view->error['avt'] = "Định dạng file không hỗ trợ!";
                    }
                }
            }
            if (RequestLib::post('User')) {
                $userModel = new UserModel();
                $data = RequestLib::post('User');
                $result = $userModel->updateUser(UserLib::getInfo()->id, $data['lastname'], $data['firstname'], $data['gender'], $data['birthday'], $data['phone'], $data['address'], $data['organization'], $data['department'], htmlspecialchars($data['description']));
                if ($result) {
                    $this->view->success['status'] = "Sửa thông tin thành công, thông tin của bạn sẽ tự động cập nhật vào lần đăng nhập tiếp theo!";
                } else {
                    $this->view->error['status'] = "Sửa thông tin không thành công!";
                }
            }
        }
        $this->view->userInfo = UserLib::getInfo();
        $this->view->render('account/edit');
    }
    public function changePassword() {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl . '/login?return=' . VenusLib::$baseUrl . '/account/changepassword');
        }
        $this->view->setTitle('Thay đổi mật khẩu');
        if (RequestLib::post('User')) {
            $data = RequestLib::post('User');
            $userModel = new UserModel();
            $result = $userModel->changePassword($data['old-password'], $data['new-password']);
            if ($result == "success") {
                $this->view->changePassStatus = "1";
            } else if ($result == "notuser") {
                $this->view->changePassStatus = "2";
            } else if ($result == "wrongpass") {
                $this->view->changePassStatus = "3";
            }
        }
        $this->view->userInfo = UserLib::getInfo();
        $this->view->render('account/changepassword');
    }
}