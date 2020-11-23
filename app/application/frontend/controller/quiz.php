<?php
namespace Application\Frontend\Controller;
use \Venus\User as UserLib;
use \Venus\Venus as VenusLib;
use \Venus\Request as RequestLib;
use \Venus\Session as SessionLib;
use Application\Frontend\Model\Quiz as QuizModel;
use Application\Frontend\Model\Attempts as Attempts;
use Application\Frontend\Model\Questions as Questions;

class Quiz extends Base {
    public function index($param = null) {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl. '/login');
        }
        if($param == null) {
            $this->view->setLayout('error');
            $this->view->render('index/error');
            return;
        }
        else {
            $quiz = new QuizModel();
            $this->view->quiz = $quiz->getInfo($param[0]);
            if (isset($this->view->quiz['createdby'])) {
                if($this->view->quiz['createdby'] == UserLib::getInfo()->id || UserLib::getInfo()->privilege == "5") {
                    $this->view->setTitle('Chỉnh sửa đề thi: ' . $this->view->quiz['name']);
                    if (RequestLib::post('Quiz')) {
                        // Process add quiz
                        // Lấy dữ liệu
                        $formData = RequestLib::post('Quiz');
                        $quesModel = new Questions();
                        $valid = $quesModel->validate($formData);
                        if ($valid !== true) {
                            $this->view->error['validate'] = $valid;
                        } else {
                            $formData['category'] = $this->view->quiz['category'];
                            $formData['quizid'] = $this->view->quiz['id'];
                            $result = $quesModel->addQues($formData);
                            if ($result) {
                                $this->view->success['addQues'] = "Đã thêm câu hỏi thành công!";
                            } else {
                                $this->view->error['validate'] = "Lỗi hệ thống! Vui lòng liên hệ quản trị!";
                            }
                        }
                    }
                    $question = new Questions();
                    $this->view->questions = [];
                    $quesData = $question->getQuestionsByQuiz($param[0]);
                    if (isset($quesData)) {
                        foreach ($quesData as $ques) {
                            $ques['seq'] = $question->getSeqByQuizIdAndQuesId($this->view->quiz['id'], $ques['id']);
                            $this->view->questions[] = $ques;
                        }
                    }
                    $this->view->quizIsChangable = $quiz->quizIsChangable($param[0]);
                } else {
                    $this->view->render('quiz/notaccept');
                    return;
                }
            } else {
                $this->view->render('quiz/notaccept');
                return;
            }
        }
        $this->view->render('index');
    }

    public function create() {
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl. '/login?return=' . VenusLib::$baseUrl . '/quiz/create');
        }
        if (RequestLib::post('Quiz')) {
            $dataInput = RequestLib::post('Quiz');
            $quiz_data = [
                'category' => 1,
                'code' => substr(md5(md5(md5((new \DateTime())->format('Y-m-d-H-i-s')))), 0, 8),
                'name' => isset($dataInput['quiz-name']) ? $dataInput['quiz-name'] : null,
                'summary' => isset($dataInput['quiz-summary']) ?$dataInput['quiz-summary'] : $dataInput['quiz-name'],
                'timeopen' => isset($dataInput['quiz-enable-timeopen']) ? $dataInput['quiz-enable-timeopen'] == 'on' ? isset($dataInput['quiz-time-open']) ? $dataInput['quiz-time-open'] : null : null : null,
                'timeclose' => isset($dataInput['quiz-enable-timeclose']) ? $dataInput['quiz-enable-timeclose'] == 'on' ? isset($dataInput['quiz-time-close']) ? $dataInput['quiz-time-close'] : null : null : null,
                'timelimit' => isset($dataInput['quiz-enable-timelimit']) ? $dataInput['quiz-enable-timelimit'] == 'on' ? isset($dataInput['quiz-time-limit']) ? $dataInput['quiz-time-limit'] : null : null : null,
                'overduehandling' => isset($dataInput['overduehandling']) ? $dataInput['overduehandling'] : null,
                'attempt' => isset($dataInput['attempts-limit']) ? $dataInput['attempts-limit'] : null,
                'grademethod' => isset($dataInput['quiz-grade-method']) ? $dataInput['quiz-grade-method'] : null,
                'review' => isset($dataInput['quiz-review']) ? $dataInput['quiz-review'] : null,
                'questionsperpage' => isset($dataInput['quiz-question-per-page']) ? $dataInput['quiz-question-per-page'] : null,
                'suffleanswer' => isset($dataInput['quiz-random-question-answer']) ? $dataInput['quiz-random-question-answer'] == 'on' ? 1 : 0 : 0,
                'sufflequestion' => isset($dataInput['quiz-random-question']) ? $dataInput['quiz-random-question'] == 'on' ? 1 : 0 : 0,
                'timecreated' => (new \DateTime())->format('Y-m-d H:i:s'),
                'createdby' => UserLib::getId(),
                'timemodified' => (new \DateTime())->format('Y-m-d H:i:s'),
                'modifiedby' => UserLib::getId(),
                'password' => isset($dataInput['quiz-enable-pwd']) && $dataInput['quiz-enable-pwd'] == 'on' && $dataInput['quiz-password'] != '' ? md5($dataInput['quiz-password']) : null,
                'showdetails' => 0,
                'navmethod' => $dataInput['quiz-result-method'],
                'acceptguest' => isset($dataInput['quiz-accept-guest']) ? $dataInput['quiz-accept-guest'] == 'on' ? 1 : 0 : 0,
                'visible' => 1
            ];
            $quiz_model = new QuizModel();
            $result = $quiz_model->addQuiz($quiz_data);
            if ($result !== true) {
                $this->view->error = $result;
            } else {
                $this->view->success = "Đã tạo đề thành công!";
            }
        }
        $this->view->setTitle('Tạo đề thi mới');
        $this->view->render('create');
    }

    public function enter(){
        $this->view->setTitle('Nhập mã đề');
        $this->view->render('enter');
    }

    public function enroll($param = null){
        if ($param != null) {
            if (isset($param[0])) {
                $quizModel = new QuizModel();
                $this->view->quizDetails = $quizModel->getInfo($param[0]);
                if (!isset($this->view->quizDetails['id'])) {
                    $this->view->setLayout('error');
                    $this->view->render('index/error');
                    return;
                }
                if(UserLib::logged()) {
                    $this->view->myAttempts = (new Attempts())->getAttemptsByQuiz($param[0], UserLib::getInfo()->id);
                }
                if(isset($this->view->quizDetails['visible']) && $this->view->quizDetails['visible'] != 1) {
                    $this->view->render('quiz/notaccept');
                }
                else {
                    $this->view->setTitle('Vào thi: ' . $this->view->quizDetails['name']);
                    $this->view->render('quiz/enroll');
                }
                return;
            }
        } else {
            $this->view->render('notaccept');
        }
    }
    public function exportResult($param = null){
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl . '/login');
        }
        if($param == null) {
            $this->view->render('notaccept');
        }
        else {
            $quiz = new QuizModel();
            $this->view->quiz = $quiz->getInfo($param[0]);
            if($this->view->quiz['createdby'] == UserLib::getInfo()->id || UserLib::getInfo()->privilege == "5") {
                $attempt = new Attempts();
                $this->view->attempts = $attempt->getSimpleAttemptsByQuiz($param[0]);
                $this->view->account = UserLib::getInfo();
                if($_GET['method'] != 'I' && $_GET['method'] != 'D'){
                    header('location: ' . VenusLib::$baseUrl . '/dashboard/managequiz/' . $this->view->quiz['id']);
                }
                $this->view->setTitle('Xuất kết quả thi');
                $this->view->setLayout('export');
                $this->view->render('exportresult');
            }
            else {
               $this->view->render('notaccept');
            }
        }
    }
    public function exportQuiz($param = null){
        if (!UserLib::logged()) {
            header('location: ' . VenusLib::$baseUrl . '/login');
        }
        if($param == null) {
            $this->view->render('notaccept');
        }
        else {
            $quiz = new QuizModel();
            $this->view->quiz = $quiz->getInfo($param[0]);
            if(UserLib::getInfo()->privilege == "5" || $this->view->quiz['createdby'] == UserLib::getInfo()->id || $quiz->checkDoTheQuiz(UserLib::getInfo()->id, $param[0])) {
                $question = new Questions();
                $this->view->account = UserLib::getInfo();
                $this->view->questions = $question->getQuestionsByQuiz($param[0]);
                if($_GET['method'] != 'I' && $_GET['method'] != 'D'){
                    header('location: ' . VenusLib::$baseUrl . '/quiz/enroll' . $this->view->quiz['id']);
                }
                $this->view->setTitle('In nội dung đề thi');
                $this->view->setLayout('export');
                $this->view->render('exportquiz');
            }
            else {
                $this->view->render('notaccept');
            }
        }
    }
}