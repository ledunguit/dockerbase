<?php
namespace Application\Admin\Controller;
use \Venus\Request as Request;
use \Venus\Admin as Admin;
use Application\Admin\Model\Question as QuestionModel;

class Question extends Base {
    public function index() {
        if (!Admin::logged()) {
            header("location: /admin/login");
        }
        $quesModel = new QuestionModel();
        $this->view->Questions = $quesModel->getAllQuestions();
        $this->view->render('index');
    }
}