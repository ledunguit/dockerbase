<?php
namespace Application\Admin\Controller;
use \Venus\Request as Request;
use \Venus\Admin as Admin;
use Application\Admin\Model\Setting as SettingModel;

class Setting extends Base {
    public function index() {
        if (!Admin::logged()) {
            header("location: /admin/login");
        }
        if (isset($_POST['btnUpdatePic'])) {
            if (isset($_FILES['customLogo'])) {
                $ext = pathinfo($_FILES['customLogo']['name'], PATHINFO_EXTENSION);
                if ($ext == 'png') {
                    if ($_FILES['customLogo']['size'] == 0) {
                        $this->view->error['customLogo'] = "File size không hợp lệ!";
                    } else {
                        move_uploaded_file($_FILES['customLogo']['tmp_name'], './publics/images/logo.' . $ext);
                    }
                } else {
                    $this->view->error['customLogo'] = "Định dạng file logo không hợp lệ!";
                }
            }

            if (isset($_FILES['customBanner'])) {
                $ext = pathinfo($_FILES['customBanner']['name'], PATHINFO_EXTENSION);
                if ($ext == 'jpg') {
                    if ($_FILES['customBanner']['size'] == 0) {
                        $this->view->error['customBanner'] = "File size không hợp lệ!";
                    } else {
                        move_uploaded_file($_FILES['customBanner']['tmp_name'], './publics/images/website/1.' . $ext);
                    }
                } else {
                    $this->view->error['customBanner'] = "Định dạng file logo không hợp lệ!";
                }
            }

            if (isset($_FILES['customBanner1'])) {
                $ext = pathinfo($_FILES['customBanner1']['name'], PATHINFO_EXTENSION);
                if ($ext == 'jpg') {
                    if ($_FILES['customBanner1']['size'] == 0) {
                        $this->view->error['customBanner1'] = "File size không hợp lệ!";
                    } else {
                        move_uploaded_file($_FILES['customBanner1']['tmp_name'], './publics/images/website/2.' . $ext);
                    }
                } else {
                    $this->view->error['customBanner1'] = "Định dạng file logo không hợp lệ!";
                }
            }

            if (isset($_FILES['customBanner2'])) {
                $ext = pathinfo($_FILES['customBanner2']['name'], PATHINFO_EXTENSION);
                if ($ext == 'jpg') {
                    if ($_FILES['customBanner2']['size'] == 0) {
                        $this->view->error['customBanner2'] = "File size không hợp lệ!";
                    } else {
                        move_uploaded_file($_FILES['customBanner2']['tmp_name'], './publics/images/website/3.' . $ext);
                    }
                } else {
                    $this->view->error['customBanner2'] = "Định dạng file logo không hợp lệ!";
                }
            }
        }
        $this->view->setTitle('Cài đặt trang web');
        $this->view->info = (new SettingModel())->getInfo();
        $this->view->render('index');
    }
}