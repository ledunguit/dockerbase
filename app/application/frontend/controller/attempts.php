<?php

namespace Application\Frontend\Controller;

use \Venus\User as UserLib;
use \Venus\Venus as VenusLib;
use \Venus\Request as RequestLib;
use \Venus\Session as SessionLib;
use Application\Frontend\Model\Quiz as Quiz;
use Application\Frontend\Model\Questions as Questions;
use Application\Frontend\Model\AttemptDetails as AttemptDetails;
use Application\Frontend\Model\Attempts as AttemptModel;

class Attempts extends Base {
    public function index($param = null) {
        $this->view->render('error');
    }

    public function progress($param = null) {
        if ($param == null) {
            $this->view->render('error');
        } else {
            $attempt = new AttemptModel();
            $this->view->attempt = $attempt->getInfo($param[0]);
            if(!isset(UserLib::getInfo()->id)){
                $userid = 0;
            }
            else {
                $userid = UserLib::getInfo()->id;
            }
            if($this->view->attempt['userid'] != $userid){
                if(!isset($this->view->attempt['guest'])){
                    $this->view->render('error');
                    return;
                }
            }
            $quiz = new Quiz();
            if (isset($this->view->attempt['quizid'])) {
                $this->view->quiz = $quiz->getInfo($this->view->attempt['quizid']);
                $this->view->setTitle('Vào thi: ' . $this->view->quiz['name']);
            }
            $this->view->is_guest = $attempt->checkIfIsGuest($param[0]);
            $questions = new Questions();
            $this->view->questions = $questions->getAllByAttemptId($param[0]);
            $this->view->choice = (new AttemptDetails())->choseAnswersByAttempt($param[0]);

            $this->view->setLayout('progress');
            $this->view->render('progress');
        }
    }

    public function review($param = null) {
        if ($param === null) {
            $this->view->render('error');
        } else {
            try {
                $this->view->attempt = (new AttemptModel())->getInfo($param[0]);
            } catch (\Exception $e) {
                $this->view->render('error');
                return;
            }
            if ($this->view->attempt == null) {
                $this->view->render('error');
                return;
            }
            if ($this->view->attempt['inProgress'] == 'in progress') {
                $this->view->render('error');
                return;
            }
            if ($this->view->attempt !== null) {
                if ($this->view->attempt['userid'] !== null) {
                    if (UserLib::getInfo() !== null) {
                        if ($this->view->attempt['userid'] == UserLib::getInfo()->id || (new Quiz())->getInfo($this->view->attempt['quizid'])['createdby'] == UserLib::getInfo()->id || UserLib::getInfo()->privilege == "5") {
                            $this->view->quizDetails = (new Quiz())->getInfo($this->view->attempt['quizid']);
                            $questions = new Questions();
                            $this->view->questions = $questions->getAllByAttemptId($param[0]);
                            $this->view->chose = (new AttemptDetails())->choseAnswersByAttempt($param[0]);
                            $this->view->setTitle('Kết quả thi: ' . $this->view->attempt['fullname'] . ' - ' . $this->view->quizDetails['name']);
                            $this->view->render('review');
                        } else {
                            $this->view->render('error');
                        }
                    } else {
                        $this->view->render('error');
                    }
                } else {
                    $this->view->quizDetails = (new Quiz())->getInfo($this->view->attempt['quizid']);
                    $questions = new Questions();
                    $this->view->questions = $questions->getAllByAttemptId($param[0]);
                    $this->view->chose = (new AttemptDetails())->choseAnswersByAttempt($param[0]);
                    $this->view->setTitle('Kết quả thi: ' . $this->view->attempt['fullname'] . ' - ' . $this->view->quizDetails['name']);
                    $this->view->render('review');
                }
            } else {
                $this->view->render('error');
            }
        }
    }

}