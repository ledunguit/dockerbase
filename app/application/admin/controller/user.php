<?php
namespace Application\Admin\Controller;
use \Venus\Request as Request;
use \Venus\Admin as Admin;
use Application\Admin\Model\User as UserModelAdmin;
use Application\Frontend\Model\Users as UserModelFront;

class User extends Base {
    public function index() {
        if (!Admin::logged()) {
            header("location: /admin/login");
        }
        if (isset($_POST['User'])) {
            $data = $_POST['User'];
            $user_model = new UserModelFront();
            $result = $user_model->addUser($data);
            if ($result === true) {
                $this->view->noti['success'] = "Đã thêm người dùng thành công.";
            } else {
                $this->view->noti['failed'] = $result;
            }
        }
        $userModel = new UserModelAdmin();
        $this->view->users = $userModel->getAllUsers();
        $this->view->render('index');
    }
}