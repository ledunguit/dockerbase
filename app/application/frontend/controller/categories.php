<?php

namespace Application\Frontend\Controller;

use \Venus\User as UserLib;
use \Venus\Venus as VenusLib;
use \Venus\Request as RequestLib;
use \Venus\Session as SessionLib;
use Application\Frontend\Model\Categories as Cate;

class Categories extends Base {
    public function index($param = null) {
        $cate = new Cate();
        if ($param !== null) {
            if (isset($param[0]) && $param[0] != 'general') {
                if($cate->getDetailsByShortName($param[0])){
                    $this->view->categoryDetail = $cate->getDetailsByShortName($param[0]);
                }
                else {
                    $this->view->setLayout('error');
                    $this->view->render('index/error');
                    exit();
                }
                $this->view->setTitle($this->view->categoryDetail['name']);
                $this->view->quizzes = $cate->getQuizzesByCategory($param[0], 'shortname');
                $this->view->users = $cate->getUsersWithMostAttempts($cate->shortNameToId($param[0]));
                $this->view->render('details');
            }
            else {
                $this->view->setTitle('Các danh mục đề thi');
                $this->view->list = $cate->getAllCategories();
                $this->view->render('index');
            }
        } else {
            $this->view->render('index');
        }
    }

    public function details($param = null) {
        $this->view->render('index');
    }
}