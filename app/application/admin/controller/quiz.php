<?php
namespace Application\Admin\Controller;
use \Venus\Request as Request;
use \Venus\Admin as Admin;
use Application\Admin\Model\Quiz as QuizModel;
use Application\Admin\Model\Categories as Categories;

class Quiz extends Base {
    public function index() {
        if (!Admin::logged()) {
            header("location: /admin/login");
        }
        $category = new Categories();
        $this->view->categories = $category->getAllCategories();
        $this->view->render('index');
    }
}