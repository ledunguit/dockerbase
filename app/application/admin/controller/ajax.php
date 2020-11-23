<?php
namespace Application\Admin\Controller;

use Venus\User as UserLib;
use Application\Admin\Model\Categories as Categories;
use Application\Admin\Model\QuestionBank as QuestionBank;
use Application\Admin\Model\Requirement as Requirement;
use Application\Frontend\Model\Attempts as Attempts;
use Application\Admin\Model\User as User;
use Application\Admin\Model\Result as Result;
use Application\Admin\Model\Setting as Setting;
use Application\Frontend\Model\Quiz as QuizModel;
use Application\Frontend\Model\Questions as Question;
use \Venus\Admin as AdminLib;

class Ajax extends Base {
    public function getQuizByCategory(){
        if(isset($_POST['dataAjax'])){
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            $category = (new Categories())->getQuizzesByCategory($data, 'id');
            echo json_encode($category);
        }
        else {
            echo json_encode('hihi');
        }
    }
    public function getQuestionsForBank(){
        if(isset($_POST['dataAjax'])){
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            $quizzes = (new QuestionBank())->getQuestionsByBank($data);
            echo json_encode($quizzes);
        }
        else {
            echo json_encode('error');
        }
    }
    public function getQuestionForCategory(){
        if(isset($_POST['dataAjax'])){
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            $quizzes = (new QuestionBank())->getQuestionsByCategory($data);
            echo json_encode($quizzes);
        }
        else {
            echo json_encode('error');
        }
    }
    public function getResultByQuiz(){
        if(isset($_POST['dataAjax'])){
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            $result = (new Attempts())->getSimpleAttemptsByQuiz($data);
            echo json_encode($result);
        }
        else {
            echo json_encode('error');
        }
    }

    public function saveWebsite1() {
        if (!\Venus\Admin::logged()) {
            echo json_encode(array('status' => 'Không có quyền thực hiện thao tác này!'));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            if (isset($data) && $data == NULL) {
                echo json_encode(array('status' => "Dữ liệu không đúng định dạng!"));
                return;
            }
            if (preg_replace('/\s+/', '', $data->webName) == '' || $data->webName == '' || strlen($data->webName) === 0) {
                echo json_encode(array('status' => "Tên trang web không hợp lệ!"));
                return;
            }
            if ($data->webDescription == NULL || strlen($data->webDescription) == 0) {
                echo json_encode(array('status' => "SEO Keyword không hợp lệ!"));
                return;
            }
            $setting_model = new Setting();
            $result = $setting_model->updatePart1($data->webName, $data->webDescription);
            if ($result === true) {
                echo json_encode(array('status' => "Thành công!"));
            } else {
                echo json_encode(array('status' => "Thất bại!"));
            }
        } else {
            echo json_encode(array('status' => "Thất bại!"));
        }
    }

    public function saveWebsite2() {
        if (!\Venus\Admin::logged()) {
            echo json_encode(array('status' => 'Không có quyền thực hiện thao tác này!'));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            $setting_model = new Setting();
            $result = $setting_model->updatePart2($data->homeName, $data->homeShort, $data->powerBy);
            if ($result === true) {
                echo json_encode(array('status' => "Thành công!"));
            } else {
                echo json_encode(array('status' => "Thất bại!"));
            }
        } else {
            echo json_encode(array('status' => "Thất bại!"));
        }
    }

    public function saveWebsite3() {
        if (!\Venus\Admin::logged()) {
            echo json_encode(array('status' => 'Không có quyền thực hiện thao tác này!'));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            $setting_model = new Setting();
            $result = $setting_model->updatePart3($data->address, $data->phone, $data->email, $data->facebook, $data->insta, $data->twitter);
            if ($result === true) {
                echo json_encode(array('status' => "Thành công!"));
            } else {
                echo json_encode(array('status' => "Thất bại!"));
            }
        } else {
            echo json_encode(array('status' => "Thất bại!"));
        }
    }

    public function saveWebsiteIntro() {
        if (!\Venus\Admin::logged()) {
            echo json_encode(array('status' => 'Không có quyền thực hiện thao tác này!'));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            $setting_model = new Setting();
            $result = $setting_model->updateIntro($data->intro);
            if ($result === true) {
                echo json_encode(array('status' => "Thành công!"));
            } else {
                echo json_encode(array('status' => "Thất bại!"));
            }
        } else {
            echo json_encode(array('status' => "Thất bại!"));
        }
    }

    public function saveHelp() {
        if (!\Venus\Admin::logged()) {
            echo json_encode(array('status' => 'Không có quyền thực hiện thao tác này!'));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            $setting_model = new Setting();
            $result = $setting_model->updateHelp($data->help);
            if ($result === true) {
                echo json_encode(array('status' => "Thành công!"));
            } else {
                echo json_encode(array('status' => "Thất bại!"));
            }
        } else {
            echo json_encode(array('status' => "Thất bại!"));
        }
    }

    public function banUser() {
        if (!\Venus\Admin::logged()) {
            echo json_encode(array('status' => 'Không có quyền thực hiện thao tác này!'));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            $user_model = new User();
            $result = $user_model->disabledUser($data->userid);
            if ($result === true) {
                echo json_encode(array('status' => "Thành công!"));
            } else {
                echo json_encode(array('status' => "Thất bại!"));
            }
        } else {
            echo json_encode(array('status' => "Thất bại!"));
        }
    }

    public function unbanUser() {
        if (!\Venus\Admin::logged()) {
            echo json_encode(array('status' => 'Không có quyền thực hiện thao tác này!'));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            $user_model = new User();
            $result = $user_model->enableUser($data->userid);
            if ($result === true) {
                echo json_encode(array('status' => "Thành công!"));
            } else {
                echo json_encode(array('status' => "Thất bại!"));
            }
        } else {
            echo json_encode(array('status' => "Thất bại!"));
        }
    }

    public function deleteUser() {
        if (!\Venus\Admin::logged()) {
            echo json_encode(array('status' => 'Không có quyền thực hiện thao tác này!'));
            return;
        }
        if (isset($_POST['dataAjax'])) {
            $input = $_POST['dataAjax'];
            $data = json_decode("$input");
            $user_model = new User();
            $result = $user_model->deleteUser($data->userid);
            if ($result === true) {
                echo json_encode(array('status' => "Thành công!"));
            } else {
                echo json_encode(array('status' => "Thất bại!"));
            }
        } else {
            echo json_encode(array('status' => "Thất bại!"));
        }
    }

    public function deleteQuiz() {
        if (!\Venus\Admin::logged()) {
            echo json_encode(array('status' => 'Không có quyền thực hiện thao tác này!'));
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
                $quiz_model = new QuizModel();
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
        } else {
            echo json_encode(array('status' => "Thất bại!"));
        }
    }

    public function updateQuestion() {
        if (!AdminLib::logged()) {
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
            }

            // call ques model and update its info
            $question_model = new Question();

            $result = $question_model->updateQuesById($ques_id, $ques_name, $ques_text, $ques_feedback, $ques_type, $ques_grade);

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

    public function deleteQuestionBank() {
        if (!AdminLib::logged()) {
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

    public function updateQuesToCate() {
        if (!AdminLib::logged()) {
            echo json_encode(array('status' => 'No previleges!'));
            return;
        }

        if (isset($_POST['dataAjax'])) {
            try {
                $input = $_POST['dataAjax'];
                $data = json_decode("$input");

                $ques_id = $data->questionid;

                $cate_id = $data->cateid;

                $ques_model = new Question();

                $result = $ques_model->updateQuestionCate($ques_id, $cate_id);

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

    public function changeCateForQuiz() {
        if (!AdminLib::logged()) {
            echo json_encode(array('status' => 'No previleges!'));
            return;
        }

        if (isset($_POST['dataAjax'])) {
            try {
                $input = $_POST['dataAjax'];
                $data = json_decode("$input");

                $quiz_id = $data->quizid;

                $cate_id = $data->cateid;

                $quiz_model = new QuizModel();

                $result = $quiz_model->updateQuizCate($quiz_id, $cate_id);

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

    public function changeCateForRequest() {
        if (!AdminLib::logged()) {
            echo json_encode(array('status' => 'No previleges!'));
            return;
        }

        if (isset($_POST['dataAjax'])) {
            try {
                $input = $_POST['dataAjax'];
                $data = json_decode("$input");

                $quiz_id = $data->quizid;

                $cate_id = $data->cateid;

                $quiz_model = new QuizModel();
                $requirement_model = new Requirement();
                $result = $quiz_model->updateQuizCate($quiz_id, $cate_id);
                $requirement_model->removeRequirementByQuizId($quiz_id);
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


}