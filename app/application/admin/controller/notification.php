<?php

namespace Application\Admin\Controller;

use Application\Frontend\Model\Notification as Notifi;
use \Venus\Request as Request;
use \Venus\Admin as Admin;

class Notification extends Base {
    public function index($param = null) {
        if (!Admin::logged()) {
            header("location: /admin/login");
        }

        if (Request::post("NotiAdd")) {
            $noti_model = new Notifi();
            $valid = $noti_model->validate(Request::post("NotiAdd"));
            if (!$valid) {
                $this->view->failed = "Dữ liệu không hợp lệ!";
            } else {
                $result = $noti_model->addNoti(Request::post("NotiAdd"));
                if ($result) {
                    $this->view->success = "Thêm thông báo thành công.";
                } else {
                    $this->view->failed = "Thêm thông báo thất bại.";
                }
            }
        }

        if (Request::post("NotiEdit")) {
            $noti_model = new Notifi();
            $valid = $noti_model->validate(Request::post("NotiEdit"));
            if (!$valid) {
                $this->view->failed = "Dữ liệu không hợp lệ!";
            } else {
                $result = $noti_model->updateNoti(Request::post("NotiEdit"));
                if ($result) {
                    $this->view->success = "Sửa thông báo thành công.";
                } else {
                    $this->view->failed = "Sửa thông báo thất bại.";
                }
            }
        }

        if (Request::post('noti-delete-id')) {
            $noti_model = new Notifi();
            $result = $noti_model->deleteNotiById(Request::post('noti-delete-id'));
            if ($result) {
                $this->view->success = "Xóa thông báo thành công.";
            } else {
                $this->view->failed = "Xóa thông báo thất bại.";
            }
        }

        if (Request::post('noti-hide-id')) {
            $noti_model = new Notifi();
            $result = $noti_model->hideNotiById(Request::post('noti-hide-id'));
            if ($result) {
                $this->view->success = "Ẩn thông báo thành công.";
            } else {
                $this->view->failed = "Ẩn thông báo thất bại.";
            }
        }

        if (Request::post('noti-show-id')) {
            $noti_model = new Notifi();
            $result = $noti_model->showNotiById(Request::post('noti-show-id'));
            if ($result) {
                $this->view->success = "Hiện thông báo thành công.";
            } else {
                $this->view->failed = "Hiện thông báo thất bại.";
            }
        }

        $this->view->list = (new Notifi())->getListNotifications();
        $this->view->render('index');
    }
}