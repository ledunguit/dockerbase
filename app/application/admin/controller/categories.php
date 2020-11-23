<?php

namespace Application\Admin\Controller;

use Application\Admin\Model\Categories as Cate;
use \Venus\Request as Request;
use \Venus\Admin as Admin;

class Categories extends Base {
    public function index($param = null) {
        if (!Admin::logged()) {
            header("location: /admin/login");
        }
        $cate = new Cate();
        if (Request::post('CateCreate')) {
            $data = Request::post('CateCreate');
            $valid = $cate->validate($data);
            if ($valid === true) {
                $result = $cate->addCate($data);
                if ($result == true) {
                    $this->view->success = "Thêm danh mục thành công.";
                    if (isset($_FILES['cate-image-add'])) {
                        $ext = pathinfo($_FILES['cate-image-add']['name'], PATHINFO_EXTENSION);
                        $cateUri = './publics/images/categories/' . $data['shortname']. '.jpg';
                        $cateUri1  = './publics/images/categories/' . $data['shortname']. '.png';
                        if ($ext != "") {
                            if ($ext == 'jpg' || $ext == 'png') {
                                if ($_FILES['cate-image-add']['size'] == 0) {
                                    $this->view->error['avt'] = "File size không hợp lệ!";
                                } else {
                                    move_uploaded_file($_FILES['cate-image-add']['tmp_name'], './publics/images/categories/' . $data['shortname'] . '.' . $ext);
                                    if ($ext === 'jpg') {
                                        if (file_exists($cateUri1)) {
                                            unlink($cateUri1);
                                        }
                                    } elseif ($ext === 'png'){
                                        if (file_exists($cateUri)) {
                                            unlink($cateUri);
                                        }
                                    }
                                }
                            } else {
                                $this->view->error['avt'] = "Định dạng file không hỗ trợ!";
                            }
                        }
                    }
                } else {
                    $this->view->error = "Thêm danh mục thất bại, lỗi hệ thống.";
                }
            } else {
                $this->view->error = $valid;
            }
        }
        if (Request::post('CateEdit')) {
            $data = Request::post('CateEdit');
            $valid = $cate->validate($data);
            if ($valid === true) {
                $result = $cate->updateCate($data);
                if ($result == true) {
                    $this->view->success = "Sửa danh mục thành công.";
                    if (isset($_FILES['cate-image-edit'])) {
                        $ext = pathinfo($_FILES['cate-image-edit']['name'], PATHINFO_EXTENSION);
                        $cateUri = './publics/images/categories/' . $data['shortname']. '.jpg';
                        $cateUri1  = './publics/images/categories/' . $data['shortname']. '.png';
                        if ($ext != "") {
                            if ($ext == 'jpg' || $ext == 'png') {
                                if ($_FILES['cate-image-edit']['size'] == 0) {
                                    $this->view->error['avt'] = "File size không hợp lệ!";
                                } else {
                                    move_uploaded_file($_FILES['cate-image-edit']['tmp_name'], './publics/images/categories/' . $data['shortname'] . '.' . $ext);
                                    if ($ext === 'jpg') {
                                        if (file_exists($cateUri1)) {
                                            unlink($cateUri1);
                                        }
                                    } elseif ($ext === 'png'){
                                        if (file_exists($cateUri)) {
                                            unlink($cateUri);
                                        }
                                    }
                                }
                            } else {
                                $this->view->error['avt'] = "Định dạng file không hỗ trợ!";
                            }
                        }
                    }
                } else {
                    $this->view->error = "Chỉnh sửa thất bại, lỗi hệ thống.";
                }
            } else {
                $this->view->error = $valid;
            }
        }
        $this->view->list = $cate->getAllCategories();
        $this->view->render('index');
    }
}