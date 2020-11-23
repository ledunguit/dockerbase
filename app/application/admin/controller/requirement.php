<?php
namespace Application\Admin\Controller;
use \Venus\Request as Request;
use \Venus\Admin as Admin;
use Application\Frontend\Model\Categories as Categories;
use Application\Admin\Model\Requirement as RequireModel;

class Requirement extends Base {
    public function index() {
        if (!Admin::logged()) {
            header("location: /admin/login");
        }
        $this->view->require = (new RequireModel())->getRequirements();
        $this->view->categories = (new Categories())->getAllCategories();
        $this->view->render('index');
    }
}