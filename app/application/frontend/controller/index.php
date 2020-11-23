<?php
namespace Application\Frontend\Controller;
use Venus\Helper;
use \Venus\User as UserLib;
use \Venus\Venus as VenusLib;
use \Venus\Request as RequestLib;
use Application\Frontend\Model\Users as UserModel;
use Application\Frontend\Model\Index as IndexModel;
use Application\Frontend\Model\Notification as Notification;
use Application\Frontend\Model\Categories as Categories;
use Application\Admin\Model\Setting as Setting;
use \Venus\Session as SessionLib;
class Index extends Base {
    public function index($param = null) {
        if (RequestLib::post("code-box")) {
            echo "Đã nhập mã";
            exit();
        }
        if ($param) {
            $this->view->setLayout('error');
            $this->view->render('error');
            exit();
        }
        $setting = new Setting();
        $this->view->info = $setting->getInfo();
        if($this->view->info == null){
            $this->view->setLayout('error');
            $this->view->render('error');
            exit;
        }
        $indexModel = new IndexModel();
        $this->view->allStatis = $indexModel->loadStatis();
        $this->view->recentQuiz = $indexModel->getRecentQuiz();
        $this->view->recentOnline = $indexModel->getRecentLogin();
        $this->view->categories = (new Categories())->getAllCategories();
        $this->view->notifications = (new Notification())->getVisibleNotifications();
        $this->view->setTitle($this->view->info->websitename);
        $this->view->render('index');
    }

    public function login() {
        if (UserLib::logged()) {
            header("location: " . VenusLib::$baseUrl);
        }
        if (RequestLib::post("email") && RequestLib::post("password") && RequestLib::post("email") !== "" && RequestLib::post("password") !== "") {
            $user = new UserModel();
            $user->email = RequestLib::post("email");
            $user->password = RequestLib::post("password");
            if ($user->checkLogin() === true) {
                if (RequestLib::get("return")) {
                    header('location: ' . RequestLib::get("return"));
                } else {
                    header("location: " . VenusLib::$baseUrl);
                }
            } elseif ($user->checkLogin() === 'lock') {
                $this->view->error['authentication'] = "Tài khoản đã bị khóa, vui lòng liên hệ quản trị!";
            } else {
                $this->view->error['authentication'] = "Tài khoản hoặc mật khẩu không đúng!";
            }
        }
        $this->view->setTitle('Đăng nhập');
        $this->view->render('index/login');
    }

    public function logout() {
        if (UserLib::logout()) {
            header("location: " . VenusLib::$baseUrl);
        } else {
            SessionLib::destroy();
            header("location: " . VenusLib::$baseUrl);
        }
    }

    public function signup() {
        if (UserLib::logged()) {
            header('location:' . VenusLib::$baseUrl);
        }
        if (RequestLib::post("User")) {
            $registerData = RequestLib::post("User");
            $user_model = new UserModel();
            $result = $user_model->addUser($registerData);
            if ($result === true) {
                $this->view->success = "Đăng ký tài khoản thành công.";
            } else {
                $this->view->fail = $result;
            }
        }
        $this->view->setTitle('Đăng ký tài khoản');
        $this->view->render('index/signup');
    }

    public function account() {
        $this->view->render('account/index');
    }

    public function dashboard() {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl . '/login?return=' . VenusLib::$baseUrl . '/dashboard');
        }
        $this->view->render('dashboard/index');
    }

    public function help() {
        $this->view->help = Helper::webInfo()->help;
        $this->view->setTitle('Trợ giúp');
        $this->view->render('index/help');
    }
    public function introduction() {
        $this->view->setTitle('Giới thiệu');
        $this->view->intro = Helper::webInfo()->introduction;
        $this->view->render('index/introduction');
    }
}