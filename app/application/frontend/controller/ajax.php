<?php

namespace Application\Frontend\Controller;

use Application\Frontend\Model\Users as UserModel;
use Application\Frontend\Model\Quiz as Quiz;
use Application\Frontend\Model\Attempts as AttemptModel;
use Application\Frontend\Model\AttemptDetails as AttemptDetailModel;
use Application\Frontend\Model\Questions as Question;
use Venus\SendEmail;
use \Venus\Venus as VenusLib;
use \Venus\Request as RequestLib;
use \Venus\Session as SessionLib;
use \Venus\User as UserLib;
use \DateTime;
use \DateInterval;
use \Exception as Exception;

class Ajax extends Base {
    public function index() {
        $this->view->setLayout('error');
        $this->view->render('index/error');
    }

    public function checkEmail() {
        if (RequestLib::post('email')) {
            $model = new UserModel();
            $email = RequestLib::post('email');
            echo $model->checkDuplicateEmail($email);
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function validateQuizCreate() {
        if (RequestLib::post('Quiz')) {
            var_dump(RequestLib::post('Quiz'));
            exit();
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function enterByCode() {
        if (RequestLib::post('code')) {
            $quiz = new Quiz();
            $result = $quiz->getIdQuizByCode(RequestLib::post('code'));
            if ($result !== null) {
                echo $result;
            } else {
                echo 'null';
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("error");
        }
    }

    public function insertAttemptWhenUserLogged() {
        if (RequestLib::post('quizId')) {
            $quizId = RequestLib::post('quizId');
            $quizModel = new Quiz();
            $allQuesID = $quizModel->getAllQuesIdByQuizId($quizId);
            $data = [];
            foreach ($allQuesID as $eachQues) {
                array_push($data, $eachQues->questionid);
            }
            $suffle = $quizModel->getInfo($quizId)['sufflequestion'];
            if($suffle == 1){
                shuffle($data);
            }
            $dataString = $data[0];
            for ($i = 1; $i < count($data); $i++) {
                $dataString .= ',' . $data[$i];
            }
            $attemptModel = new AttemptModel();
            $result = $attemptModel->insertData($quizId, UserLib::getId(), null, null, null, $dataString, '1');
            if ($result != null) {
                echo $result;
            } else {
                echo 'null';
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function checkPasswordOfQuiz() {
        if (isset($_POST['dataAjax'])) { // ktra dữ liệu có được tải lên không
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode Json
            $quizModel = new Quiz(); // new Quiz Model
            $result = $quizModel->checkPasswordByQuizId($data->quizid, $data->password); // check Password
            if ($result) { // Đúng pass
                $quizId = $data->quizid;
                $quizModel = new Quiz();
                $allQuesID = $quizModel->getAllQuesIdByQuizId($quizId);
                $data = [];
                foreach ($allQuesID as $eachQues) {
                    array_push($data, $eachQues->questionid);
                }
                shuffle($data);
                $dataString = $data[0];
                for ($i = 1; $i < count($data); $i++) {
                    $dataString .= ',' . $data[$i];
                }
                $attemptModel = new AttemptModel();
                $result = $attemptModel->insertData($quizId, UserLib::getId(), null, null, null, $dataString, '1');
                if ($result != null) {
                    echo json_encode(array('status' => 'true', 'attemptid' => $result));
                } else {
                    echo json_encode(array('status' => 'SystemError'));
                }
            } else { // Sai pass
                echo json_encode(array('status' => 'WrongPass'));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function insertGuestAttempt() {
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input");
            $quizId = $data->quizid;
            $guestEmail = $data->guest;
            $quizModel = new Quiz();
            $allQuesID = $quizModel->getAllQuesIdByQuizId($quizId);
            $data = [];
            foreach ($allQuesID as $eachQues) {
                array_push($data, $eachQues->questionid);
            }
            shuffle($data);
            $dataString = $data[0];
            for ($i = 1; $i < count($data); $i++) {
                $dataString .= ',' . $data[$i];
            }
            $attemptModel = new AttemptModel();
            $result = $attemptModel->insertData($quizId, null, $guestEmail, null, null, $dataString, '1');
            if ($result != null) {
                echo json_encode(array('status' => 'true', 'attemptid' => $result));
            } else {
                echo json_encode(array('status' => 'false'));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function getTimeLeftForAttempt() {
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input");
            $quizId = $data->quizid;
            $attemptId = $data->attemptid;
            $currentDateTime = date("Y-m-d H:i:s");
            $attemptModel = new AttemptModel();
            $endTime = $attemptModel->getInfo($data->attemptid)['timesubmitted'];
            if ($endTime > $currentDateTime) {
                echo json_encode(array('timeleft' => (new DateTime($endTime))->diff(new DateTime($currentDateTime))));
            } else {
                echo json_encode(array('timeleft' => "Hết thời gian"));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function saveTempAnswerCheckBox() {
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input");
            $data = (array)$data;
            $attemptDetailModel = new AttemptDetailModel();
            $quesId = -1;
            $attemptId = -1;
            if (isset($data[0])) {
                $attemptId = $data[0];
                array_shift($data);
            }
            if (isset($data[0])) {
                $quesId = $data[0];
                array_shift($data);
            }
            if (isset($data)) {
                $result = $attemptDetailModel->updateAnswersCheckBox($attemptId, $quesId, $data);
                if ($result) {
                    echo json_encode(array('status' => 'save ok'));
                } else {
                    echo json_encode(array('status' => 'save failed'));
                }
            } else {
                echo json_encode(array('status' => 'empty chose'));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function saveTempAnswerRadio() {
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input");
            $attemptDetailModel = new AttemptDetailModel();
            $result = $attemptDetailModel->updateAnswersRadio($data->attemptid, $data->quesid, $data->chose);
            if ($result) {
                echo json_encode(array('status' => 'save ok'));
            } else {
                echo json_encode(array('status' => 'save failed'));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function submitQuiz() {
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            $attemptId = $data->attemptid;
            $attemptModel = new AttemptModel();
            $result = $attemptModel->submitAttempt($attemptId);
            if ($result) {
                echo json_encode(array('status' => 'ok'));
            } else {
                echo json_encode(array('status' => 'failed'));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function submitQuizGuest() {
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            $attemptId = $data->attemptid;
            $attemptModel = new AttemptModel();
            $quizModel = new Quiz();
            $quiz = $quizModel->getInfo((new AttemptModel())->getInfo($attemptId)['quizid']);
            $result = $attemptModel->submitAttempt($attemptId);
            if ($result) {
                if($quiz['navmethod'] == 3) {
                    SendEmail::sendResult($data->attemptid);
                    echo json_encode(array('status' => 'ok'));
                }
                else {
                    echo json_encode(array('status' => 'failed'));
                }
            } else {
                echo json_encode(array('status' => 'failed'));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function checkDuplicateAttemptUserLogged() {
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            $quizId = $data->quizid;
            $userId = $data->userid;
            $attemptModel = new AttemptModel();
            $result = $attemptModel->getAttemptSumittedTime($quizId, $userId);
            $currentDateTime = date("Y-m-d H:i:s");
            if ($result !== null) {
                if ($result->timesubmitted > $currentDateTime) {
                    echo json_encode(array('status' => 'not'));
                } else {
                    echo json_encode(array('status' => 'ok'));
                }
            } else {
                echo json_encode(array('status' => 'failed'));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function checkDuplicateAttemptGuest() {
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            $quizId = $data->quizid;
            $guest = $data->guest;
            $attemptModel = new AttemptModel();
            $result = $attemptModel->getAttemptSumittedTimeGuest($quizId, $guest);
            $currentDateTime = date("Y-m-d H:i:s");
            if ($result !== null) {
                if ($result->timesubmitted > $currentDateTime) {
                    echo json_encode(array('status' => 'not'));
                } else {
                    echo json_encode(array('status' => 'ok'));
                }
            } else {
                echo json_encode(array('status' => 'failed'));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function setVisibleForQuiz() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['id']) && isset($_POST['visible'])) {
            $id = $_POST['id'];
            $visible = $_POST['visible'];
            $quiz = new Quiz();
            if ($quiz->updateVisible($id, $visible)) {
                echo 'ok';
            } else {
                echo 'failed';
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function deleteAvt() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            $avtUri = './publics/images/avatar/' . UserLib::getInfo()->id . '.jpg';
            $avtUri1 = './publics/images/avatar/' . UserLib::getInfo()->id . '.png';
            if (file_exists($avtUri)) {
                unlink($avtUri);
                echo json_encode(array('status' => 'deleted'));
            } else if (file_exists($avtUri1)) {
                unlink($avtUri1);
                echo json_encode(array('status' => 'deleted'));
            } else {
                echo json_encode(array('status' => 'none'));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateTimeLimit() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if ($data) {
                $quizid = $data->quizid;
                $newtime = $data->newtime;
            }
            $time = explode(':', $newtime);
            if (count($time) != 2) {
                echo json_encode(array("status" => "Không phải định dạng thời gian!"));
                return;
            }
            if (!is_numeric($time[0]) || !is_numeric($time[1])) {
                echo json_encode(array("status" => "Giá trị thời gian không hợp lệ!"));
                return;
            }
            if ((int)$time[0] < 0 || (int)$time[0] > 2) {
                echo json_encode(array("status" => "Giờ không hợp lệ!"));
                return;
            }
            if ((int)$time[1] < 0 || (int)$time[1] > 59) {
                echo json_encode(array("status" => "Số phút chỉ được nằm trong [0-59]"));
                return;
            }
            if ((int)$time[0] == 2 && (int)$time[1] > 0) {
                echo json_encode(array("status" => "Thời gian không hợp lệ!"));
                return;
            }
            $totalTime = (int)$time[0] * 3600 + (int)$time[1] * 60;
            //call model quiz
            $quizModel = new Quiz();
            $result = $quizModel->updateTimeLimit($data->quizid, $totalTime);
            if ($result) {
                echo json_encode(array("status" => "Cập nhật thành công!"));
                return;
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateTimeOpenAndClose() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                if (isset($data->quizid)) {
                    $quizId = $data->quizid;
                }
                if (isset($data->timeopen)) {
                    $timeOpenRaw = $data->timeopen;
                }
                if (isset($data->timeclose)) {
                    $timeCloseRaw = $data->timeclose;
                }
            } else {
                echo json_encode(array("status" => "Nothing to process!"));
                return;
            }
            if (isset($data->delete) && $data->delete == 'Yes') {
                $quizModel = new Quiz();
                $result = $quizModel->updateTimeCloseAndOpenToNull($data->quizid);
                if ($result) {
                    echo json_encode(array("status" => "Đã xóa thời gian mở và đóng!"));
                } else {
                    echo json_encode(array("status" => "Có lỗi hệ thống! Vui lòng liên hệ quản trị!"));
                }
                return;
            }
            if (isset($timeOpenRaw)) {
                try { //check time open
                    $timeOpen = new DateTime($timeOpenRaw);
                    $timeOpen = $timeOpen->format("Y-m-d H:i:s");
                } catch (Exception $e) {
                    echo json_encode(array("status" => "Không thể nhận dạng thời gian mở đề!"));
                    return;
                }
            }
            if (isset($timeCloseRaw)) {
                try { // check time close
                    $timeClose = new DateTime($timeCloseRaw);
                    $timeClose = $timeClose->format("Y-m-d H:i:s");
                } catch (Exception $e) {
                    echo json_encode(array("status" => "Không thể nhận dạng thời gian đóng đề!"));
                    return;
                }
            }
            if (isset($timeClose) && isset($timeOpen)) {
                if ($timeClose < $timeOpen) {
                    echo json_encode(array("status" => "Không thể update do thời gian mở lớn hơn thời gian đóng!"));
                    return;
                }
            }
            if (isset($timeOpen) && !isset($timeClose)) {
                $quizModel = new Quiz();
                $result = $quizModel->getTimeCloseById($quizId);
                if ($timeOpen > $result) {
                    echo json_encode(array("status" => "Không thể update do thời gian mở lớn hơn thời gian đóng đã cài đặt!"));
                    return;
                }
            }
            $okTimeOpen = false;
            $okTimeClose = false;
            if (isset($timeOpen)) {
                // call quiz model
                $quizModel = new Quiz();
                $result = $quizModel->updateTimeOpen($quizId, $timeOpen);
                if ($result) {
                    $okTimeOpen = true;
                }
            }
            if (isset($timeClose)) {
                // call quiz model
                $quizModel = new Quiz();
                $result = $quizModel->updateTimeClose($quizId, $timeClose);
                if ($result) {
                    $okTimeClose = true;
                }
            }
            if ($okTimeOpen && $okTimeClose) {
                echo json_encode(array('status' => "Đã xử lí và cập nhật!"));
            } else if ($okTimeOpen) {
                echo json_encode(array('status' => "Đã xử lí và cập nhật thời gian mở đề!"));
            } else if ($okTimeClose) {
                echo json_encode(array('status' => "Đã xử lí và cập nhật thời gian đóng đề!"));
            } else {
                echo json_encode(array('status' => 'Lỗi hệ thống! Vui lòng liên hệ quản trị!'));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateOverdueHandleMethod() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                $quizId = $data->quizid;
                $method = $data->method;
            }
            if (!is_numeric($method)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            if ($method <= 0 || $method > 2) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            $quizModel = new Quiz();
            $result = $quizModel->updateOverdueHandleMethod($quizId, $method);
            if ($result) {
                echo json_encode(array('status' => 'Đã cập nhật!'));
            } else {
                echo json_encode(array('status' => 'Lỗi hệ thống! Vui lòng liên hệ quản trị!'));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateNumberOfAttempts() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                $quizId = $data->quizid;
                $numberOfAttempt = $data->numberOfAttempt;
            }
            if (!is_numeric($numberOfAttempt)) {
                echo json_encode(array("status" => "Vui lòng nhập số!"));
                return;
            }
            $numberOfAttempt = (int)$numberOfAttempt;
            if ($numberOfAttempt > 10 || $numberOfAttempt < 0) {
                echo json_encode(array("status" => "Giá trị không hợp lệ! Vui lòng chọn trong khoảng [0-10]"));
                return;
            }
            $quizModel = new Quiz();
            $result = $quizModel->updateNumberOfAttempts($quizId, $numberOfAttempt);
            if ($result) {
                echo json_encode(array("status" => "Đã cập nhật thành công!"));
            } else {
                echo json_encode(array("status" => "Lỗi hệ thống! Vui lòng liên hệ quản trị!"));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateGradeMethod() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                $quizId = $data->quizid;
                $gradeMethod = $data->gradeMethod;
            }
            if (!is_numeric($gradeMethod)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            if ($gradeMethod < 1 || $gradeMethod > 5) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            $quizModel = new Quiz();
            $result = $quizModel->updateGradeMethod($quizId, $gradeMethod);
            if ($result) {
                echo json_encode(array('status' => 'Đã cập nhật! Vui lòng reset trang để cập nhật thông tin!'));
            } else {
                echo json_encode(array("status" => "Lỗi hệ thống! Vui lòng liên hệ quản trị!"));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateReviewSetting() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                $quizId = $data->quizid;
                $review = $data->quizReview;
            }
            if (!is_numeric($review)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            if (!($review == 0 || $review == 1)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            $quizModel = new Quiz();
            $result = $quizModel->updateReviewSetting($quizId, $review);
            if ($result) {
                echo json_encode(array('status' => 'Đã cập nhật! Vui lòng reset trang để cập nhật thông tin!'));
            } else {
                echo json_encode(array("status" => "Lỗi hệ thống! Vui lòng liên hệ quản trị!"));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateQuestionPerpage() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                $quizId = $data->quizid;
                $quesPerPage = $data->quesPerPage;
            }
            if (!is_numeric($quesPerPage)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            if ($quesPerPage < 1 || $quesPerPage > 50) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            $quizModel = new Quiz();
            $result = $quizModel->updateQuestionPerpage($quizId, $quesPerPage);
            if ($result) {
                echo json_encode(array('status' => 'Đã cập nhật! Vui lòng reset trang để cập nhật thông tin!'));
            } else {
                echo json_encode(array("status" => "Lỗi hệ thống! Vui lòng liên hệ quản trị!"));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateSuffleAnswerSetting() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                $quizId = $data->quizid;
                $suffleAnswer = $data->suffleAnswer;
            }
            if (!is_numeric($suffleAnswer)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            if (!($suffleAnswer == 1 || $suffleAnswer == 0)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            $quizModel = new Quiz();
            $result = $quizModel->updateSuffleAnswerSetting($quizId, $suffleAnswer);
            if ($result) {
                echo json_encode(array('status' => 'Đã cập nhật! Vui lòng reset trang để cập nhật thông tin!'));
            } else {
                echo json_encode(array("status" => "Lỗi hệ thống! Vui lòng liên hệ quản trị!"));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateSuffleQuestionSetting() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                $quizId = $data->quizid;
                $suffleQues = $data->suffleQues;
            }
            if (!is_numeric($suffleQues)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            if (!($suffleQues == 1 || $suffleQues == 0)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            $quizModel = new Quiz();
            $result = $quizModel->updateSuffleQuestionSetting($quizId, $suffleQues);
            if ($result) {
                echo json_encode(array('status' => 'Đã cập nhật! Vui lòng reset trang để cập nhật thông tin!'));
            } else {
                echo json_encode(array("status" => "Lỗi hệ thống! Vui lòng liên hệ quản trị!"));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateQuizPassword() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                $quizId = $data->quizid;
                $password = $data->password;
            }
            if ($password == "remove") {
                $quizModel = new Quiz();
                $result = $quizModel->removePasswordById($quizId);
                if ($result) {
                    echo json_encode(array('status' => 'Đã xóa mật khẩu! Vui lòng reset trang để cập nhật thông tin!'));
                } else {
                    echo json_encode(array("status" => "Lỗi hệ thống! Vui lòng liên hệ quản trị!"));
                }
                return;
            }
            if ($password == "") {
                echo json_encode(array('status' => 'Mật khẩu không được để trống!'));
                return;
            }
            if (strlen($password) < 6) {
                echo json_encode(array('status' => 'Vui lòng nhập mật khẩu có ít nhất 6 kí tự!'));
                return;
            }
            $quizModel = new Quiz();
            $result = $quizModel->updateQuizPassword($quizId, md5($password));
            if ($result) {
                echo json_encode(array('status' => 'Đã cập nhật! Vui lòng reset trang để cập nhật thông tin!'));
            } else {
                echo json_encode(array("status" => "Lỗi hệ thống! Vui lòng liên hệ quản trị!"));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateDetailSetting() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                $quizId = $data->quizid;
                $showDetails = $data->showDetails;
            }
            if (!is_numeric($showDetails)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            if (!($showDetails == 1 || $showDetails == 0)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            $quizModel = new Quiz();
            $result = $quizModel->updateDetailSetting($quizId, $showDetails);
            if ($result) {
                echo json_encode(array('status' => 'Đã cập nhật! Vui lòng reset trang để cập nhật thông tin!'));
            } else {
                echo json_encode(array("status" => "Lỗi hệ thống! Vui lòng liên hệ quản trị!"));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateAcceptGuestSetting() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                $quizId = $data->quizid;
                $acceptGuest = $data->acceptGuest;
            }
            if (!is_numeric($acceptGuest)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            if (!($acceptGuest == 1 || $acceptGuest == 0)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            $quizModel = new Quiz();
            $result = $quizModel->updateAcceptGuestSetting($quizId, $acceptGuest);
            if ($result) {
                echo json_encode(array('status' => 'Đã cập nhật! Vui lòng reset trang để cập nhật thông tin!'));
            } else {
                echo json_encode(array("status" => "Lỗi hệ thống! Vui lòng liên hệ quản trị!"));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateReceiveReviewSetting() {
        if (!UserLib::logged()) {
            echo json_encode(array("status" => "No previleges!"));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax']; // lấy input
            $data = json_decode("$input"); // decode json
            if (isset($data)) {
                $quizId = $data->quizid;
                $navMethod = $data->navMethod;
            }
            if (!is_numeric($navMethod)) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            if ($navMethod < 1 || $navMethod > 3) {
                echo json_encode(array('status' => 'Giá trị không hợp lệ! Vui lòng kiểm tra lại!'));
                return;
            }
            $quizModel = new Quiz();
            $result = $quizModel->updateReceiveReviewSetting($quizId, $navMethod);
            if ($result) {
                echo json_encode(array('status' => 'Đã cập nhật! Vui lòng reset trang để cập nhật thông tin!'));
            } else {
                echo json_encode(array("status" => "Lỗi hệ thống! Vui lòng liên hệ quản trị!"));
            }
        } else {
            $this->view->setLayout("error");
            $this->view->render("index/error");
        }
    }

    public function updateQuestion() {
        if (!UserLib::logged()) {
            echo json_encode(array('status' => 'No previleges!'));
            return;
        }

        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");

            if (isset($data)) {
                $ques_id = $data->id;
                $ques_name = $data->name;
                $ques_text = $data->text;
                $ques_feedback = $data->feedback;
                $ques_type = $data->type;
                $ques_grade = $data->grade;
                $ques_answers = $data->answers;
                $quiz_id = $data->quizid;
            }

            // call ques model and update its info
            $question_model = new Question();

            $result = $question_model->updateQuesById1($ques_id, $ques_name, $ques_text, $ques_feedback, $ques_type);

            $result1 = $question_model->updateTempGrade($quiz_id, $ques_id, $ques_grade);

            if ($result) {
                // call quiz Model to update answers
                // get ids of all answer by questionid
                $answer_ids = $question_model->getAllAnswerIds($ques_id);
                $answer_ids_data = [];
                foreach ($answer_ids as $answer_id) {
                    array_push($answer_ids_data, $answer_id->id);
                }

                $ans_change = count($ques_answers);
                $ans_current = count($answer_ids_data);

                if ($ans_change != $ans_current) {
                    // Add answers to database of this question
                    $answersData['answers'] = [];
                    $ans = [];

                    foreach ($ques_answers as $ques_answer) {
                        $ans['questionid'] = $ques_id;
                        $ans['answer'] = $ques_answer[0];
                        $ans['fraction'] = $ques_answer[1];
                        $answersData['answers'][] = $ans;
                    }
                    //var_dump($answersData);

                    // Remove answers from database that the question contain.
                    foreach ($answer_ids_data as $ans_id) {
                        $question_model->deleteAnswerById($ans_id);
                    }

                    // Add all data answer after reset
                    $question_model->addAnswersSub($answersData);
                    echo json_encode(array('status' => 'success'));
                } else { // Update list answer one by one
                    foreach ($answer_ids_data as $id) {
                        $updateResult = $question_model->updateAnswerById($id, $ques_answers[0]);
                        array_shift($ques_answers);
                    }

                    echo json_encode(array('status' => 'success'));
                }
            } else {
                echo json_encode(array('status' => 'failed'));
            }
        }
    }

    public function deleteQuestion() {
        if (!UserLib::logged()) {
            echo json_encode(array('status' => 'No previleges!'));
            return;
        }

        if (isset($_POST['dataAjax'])) {
            try {
                $input = $_POST['dataAjax'];
                $data = json_decode("$input");

                $ques_id = $data->questionid;
                $quiz_id = $data->quizid;

                $quiz_model = new Quiz();

                $result = $quiz_model->deleteQuesByIdAndQuizId($ques_id, $quiz_id);

                if ($result == true) {
                    echo json_encode(array('status' => 'success'));
                } else {
                    echo json_encode(array('status' => 'failed'));
                }
            } catch (\Exception $e) {
                echo json_encode(array('status' => 'System Error!'));
            }
        }
    }

    public function deleteQuestionBank() {
        if (!UserLib::logged()) {
            echo json_encode(array('status' => 'No previleges!'));
            return;
        }

        if (isset($_POST['dataAjax'])) {
            try {
                $input = $_POST['dataAjax'];
                $data = json_decode("$input");

                $ques_id = $data->questionid;

                $ques_model = new Question();

                $result = $ques_model->deleteQuesionBankById($ques_id);

                if ($result == true) {
                    echo json_encode(array('status' => 'success'));
                } else {
                    echo json_encode(array('status' => 'failed'));
                }
            } catch (\Exception $e) {
                echo json_encode(array('status' => 'System Error!'));
            }
        }
    }

    public function deleteQuizById() {
        if (!UserLib::logged()) {
            echo json_encode(array('status' => 'No previleges!'));
            return;
        }

        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            if (!is_numeric($data->quizid)) {
                echo json_encode(array('status' => 'Not a ID!'));
                return;
            }
            try {
                $quiz_model = new Quiz();
                $result = $quiz_model->deleteQuiz($data->quizid);
                if ($result == true) {
                    echo json_encode(array('status' => 'success'));
                } else {
                    echo json_encode(array('status' => 'failed'));
                }
            } catch (\Exception $e) {
                echo json_encode(array('status' => 'failed'));
                return;
            }
        }
    }

    public function assignQuesById() {
        if (!UserLib::logged()) {
            echo json_encode(array('status' => 'No Privileges!'));
            return;
        }

        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            try {
                $data = (array)$data;
                $data['id'] = (array)$data['id'];
                $error = [];
                $status = null;
                if (!is_numeric($data['grade'])) {
                    echo json_encode(array('status' => 'errGrade'));
                    return;
                }
                foreach ($data['id'] as $id) {
                    $ques_model = new Question();
                    $result = $ques_model->assignQuesBank($data['quizid'], $id, $data['grade']);
                    if ($result !== true) {
                        $error[] = $result;
                    }
                }
                if (empty($error)) {
                    echo json_encode(array('status' => 'success'));
                } else {
                    echo json_encode(array('status' => $error));
                }
            } catch (\Exception $e) {
                echo json_encode(array('status' => 'Wrong data structure!'));
            }
        }
    }

    public function deleteAttempt() {
        $input = $_POST['dataAjax'];
        $data = json_decode("$input");
        if (!isset($data)) {
            echo json_encode(array('status' => 'failed'));
        }
        try {
            if (isset($data->attemptid)) {
                $attemptModel = new AttemptModel();
                $result = $attemptModel->deleteAttemptDetailByAttemptId($data->attemptid);
                if ($result) {
                    $result = $attemptModel->deleteAttemptById($data->attemptid);
                    if ($result) {
                        echo json_encode(array('status' => 'success'));
                    } else {
                        echo json_encode(array('status' => 'failed'));
                    }
                }
            } else {
                echo json_encode(array('status' => 'failed'));
            }
        } catch (\Exception $e) {
            echo json_encode(array('status' => 'Wrong data structure!'));
        }
    }

    public function requestPublic() {
        $input = $_POST['dataAjax'];
        $data = json_decode("$input");
        if (!isset($data)) {
            echo json_encode(array('status' => 'failed'));
        }
        try {
            if (isset($data->quizid)) {
                $quiz_model = new Quiz();
                $result = $quiz_model->addRquestPublicByQuizId($data->quizid);
                if ($result) {
                    echo json_encode(array('status' => 'success'));
                } else {
                    echo json_encode(array('status' => 'failed'));
                }
            } else {
                echo json_encode(array('status' => 'none data'));
            }
        } catch (\Exception $e) {
            echo json_encode(array('status' => 'Wrong data structure!'));
        }
    }
}
