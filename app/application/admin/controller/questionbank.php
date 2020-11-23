<?php
namespace Application\Admin\Controller;
use \Venus\Request as Request;
use \Venus\Admin as Admin;
use Application\Admin\Model\Categories as Categories;
use Application\Admin\Model\QuestionBank as Banks;
use Application\Frontend\Model\Questions as Questions;

class QuestionBank extends Base {
    public function index() {
        if (!Admin::logged()) {
            header("location: /admin/login");
        }
        if (Request::post('Ques')) {
            $ques_data = Request::post('Ques');
            $quesModel = new Questions();
            $valid = $quesModel->validate($ques_data);
            if ($valid == true) {
                $result = $quesModel->addQuesBank($ques_data);
                if ($result == true) {
                    $this->view->success = "Đã thêm câu hỏi thành công!";
                } else {
                    $this->view->failed = "Lỗi hệ thống, không thể thêm câu hỏi! Vui lòng liên hệ quản trị!";
                }
            }
        }

        $this->view->categories = (new Categories())->getAllCategories();
        $this->view->render('index');
    }
}