<?php

namespace Application\Frontend\Controller;

use \Venus\User as UserLib;
use \Venus\Venus as VenusLib;
use Application\Frontend\Model\Quiz as Quiz;
use Application\Frontend\Model\Attempts as Attempts;
use Application\Frontend\Model\Questions as Questions;
use \Venus\Request as RequestLib;
use \Venus\Session as SessionLib;

class Dashboard extends Base {
    public function index() {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl . '/login?return=' . VenusLib::$baseUrl . '/dashboard');
        }
        $this->view->setTitle('Bảng điều khiển');
        $this->view->render('index');
    }

    public function myquiz() {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl . '/login');
        }
        $quiz = new Quiz();
        $this->view->quizzes = $quiz->getQuizzesByUser(UserLib::getInfo()->id);
        $this->view->report = $quiz->getQuizReportByUser(UserLib::getInfo()->id);
        $this->view->setTitle('Quản lý đề thi của tôi');
        $this->view->render('myquiz');
    }

    public function grade() {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl . '/login');
        }
        $attempt = new Attempts();
        $this->view->rows = $attempt->getGradeTable(UserLib::getInfo()->id);
        $this->view->average = $attempt->getAverageGradeByUser(UserLib::getInfo()->id);
        $this->view->setTitle('Bảng điểm cá nhân');
        $this->view->render('grade');
    }

    public function questionBank() {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl . '/login');
        }
        if (RequestLib::post('Ques')) {
            $ques_data = RequestLib::post('Ques');
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
        $this->view->questions = (new Questions())->getQuestionsForBank(UserLib::getInfo()->id);
        $this->view->simpleList = (new Quiz())->getMySimpleList();
        $this->view->setTitle('Ngân hàng câu hỏi');
        $this->view->render('questionbank');
    }

    public function managequiz($param = null) {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl . '/login');
        }
        if ($param == null) {
            $this->view->render('error');
        } else {
            $quiz = (new Quiz())->getInfo($param[0]);
            if (!$quiz) {
                $this->view->render('error');
                return;
            }
            if (!isset($quiz['createdby'])) {
                $this->view->render('error');
                return;
            } else if($quiz['createdby'] == UserLib::getInfo()->id || UserLib::getInfo()->privilege == "5"){
                $attempt = new Attempts();
                $quiz = new Quiz();
                $this->view->quiz = $quiz->getInfo($param[0]);
                $this->view->rank = $quiz->getRankList($param[0]);
                $this->view->attempts = $attempt->getSimpleAttemptsByQuiz($param[0]);
                $this->view->isChangable = $quiz->quizIsChangable($param[0]);
                $this->view->grade = $quiz->getGradeReport($param[0]);
                $this->view->setTitle('Quản lý đề thi: ' . $this->view->quiz['name']);
                $this->view->render('managequiz');
            }
            else {
                $this->view->render('error');
            }
        }
    }

    public function exportGrade() {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl . '/login');
        }
        $attempt = new Attempts();
        $this->view->rows = $attempt->getGradeTable(UserLib::getInfo()->id);
        $this->view->average = $attempt->getAverageGradeByUser(UserLib::getInfo()->id);
        $this->view->account = UserLib::getInfo();
        if($_GET['method'] != 'I' && $_GET['method'] != 'D'){
            header('location: ' . VenusLib::$baseUrl . '/dashboard/grade');
        }
        $this->view->setLayout('export');
        $this->view->render('exportgrade');
    }
}