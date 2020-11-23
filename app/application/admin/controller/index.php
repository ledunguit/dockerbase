<?php
namespace Application\Admin\Controller;
use \Venus\Request as Request;
use \Venus\Admin as Admin;
use \Venus\Session as Session;
use Application\Admin\Model\Admin as AdminModel;
use Application\Admin\Model\Overview as Overview;

class Index extends Base {
    public function index() {
        if (!Admin::logged()) {
            header("location: /admin/login");
        }
        $this->view->render('index');
    }

    public function login() {
        if (Admin::logged()) {
            header("location: /admin");
        }
        if (Request::post("Admin")) {
            $input = Request::post("Admin");
            $admin = new AdminModel();
            $admin->adminEmail = $input['email'];
            $admin->adminPassword = $input['password'];
            if ($admin->authenticate() === true) {
                header("location: /admin");
            } else if ($admin->authenticate() === "No Privilege") {
                $this->view->loginError = "Bạn không có quyền hạn truy cập khu vực này!";
            } else {
                $this->view->loginError = "Tài khoản hoặc mật khẩu không đúng!";
            }
        }
        $this->view->setLayout('login');
        $this->view->render('login');
    }

    public function logout() {
        if (Admin::logout()) {
            header("location: /admin");
        } else {
            Session::destroy();
            header("location: /admin");
        }
    }
}