<?php
namespace Application\Admin\Controller;
use \Venus\Request as Request;
use \Venus\Admin as Admin;
use Application\Admin\Model\Categories as Categories;
use Application\Admin\Model\QuestionBank as Banks;

class Result extends Base {
    public function index() {
        if (!Admin::logged()) {
            header("location: /admin/login");
        }
        $this->view->categories = (new Categories())->getAllCategories();
        $this->view->render('index');
    }
}