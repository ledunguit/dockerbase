<?php

namespace Application\Frontend\Model;

use \Venus\Model as Model;
use \Venus\User as User;
use \Venus\Admin as Admin;
use \DateTime;

class Questions extends Model {

    public function __construct($id = null) {
        parent::__construct();
        $this->id = (int)$id;
    }

    public function validate(array &$data) {
        if (!isset($data)) {
            return "false";
        }
        if (isset($data['name']) && strlen($data['name']) > 255) {
            $data['name'] = \Venus\Helper::convertQuesName($data['name']);
        }
        if ($data['text'] == "") {
            return "Nội dung câu hỏi không được để trống!";
        }
        if ($data['grade'] == "") {
            return "Điểm của câu hỏi không được để trống!";
        }
        if (!is_numeric($data['num-answers'])) {
            return "Sô câu trả lời không hợp lệ!";
        }
        $ansCount = (int)$data['num-answers'];
        if ($ansCount < 2 || $ansCount > 8) {
            return "Sô câu trả lời không hợp lệ!";
        }
        $ansValid = true;
        for ($i = 1; $i <= $ansCount; $i++) {
            if ($data['answer-' . $i] == "") {
                $ansValid = false;
            }
        }
        if (!$ansValid) {
            return "Các đáp án không được rỗng!";
        }
        return true;
    }

    public function addQues($data) {
        $dataInsert = [];
        $dataInsert['category'] = $data['category'];
        $dataInsert['name'] = $data['name'];
        $dataInsert['questiontext'] = $data['text'];
        $dataInsert['feedback'] = $data['feedback'];
        $trueAnsCount = 0;
        $answersCount = 0;
        for ($i = 1; $i <= 8; $i++) {
            if (isset($data['ans-grade-' . $i]) && $data['ans-grade-' . $i] == 'on') {
                $trueAnsCount++;
            }
            if (isset($data['answer-' . $i]) && $data['answer-' . $i]) {
                $answersCount++;
            }
        }
        if ($trueAnsCount > 1) {
            $dataInsert['type'] = 2;
        } else {
            $dataInsert['type'] = 1;
        }
        $now = new DateTime();
        $dataInsert['timecreated'] = $now->format('Y-m-d H:i:s');
        if (User::logged()) {
            $dataInsert['createdby'] = User::getId();
        }
        $dataInsert['defaultgrade'] = $data['grade'];
        $quesModel = $this->insertInto(
            'questions',
            'category, name, questiontext, feedback, type, timecreated, createdby, timemodified, modifiedby, defaultgrade',
            ':category, :name, :questiontext, :feedback, :type, :timecreated, :createdby, :timemodified, :modifiedby, :defaultgrade')
            ->execute(array(
                'category' => $dataInsert['category'],
                'name' => $dataInsert['name'],
                'questiontext' => htmlspecialchars($dataInsert['questiontext']),
                'feedback' => htmlspecialchars($dataInsert['feedback']),
                'type' => $dataInsert['type'],
                'timecreated' => $dataInsert['timecreated'],
                'createdby' => $dataInsert['createdby'],
                'timemodified' => null,
                'modifiedby' => null,
                'defaultgrade' => $dataInsert['defaultgrade']
            ));
        if ($quesModel) {
            $assignData = [];
            $quizModel = new Quiz();
            $assignData['quizid'] = $data['quizid'];
            $quesModel = $this->select('id')
                ->from('questions')
                ->where('createdby = :createdby')
                ->orderBy('id DESC')
                ->execute(array('createdby' => $dataInsert['createdby']))
                ->fetch();
            if (!isset($quesModel->id)) {
                return false;
            }
            $assignData['questionid'] = $quesModel->id;
            $assignData['grade'] = $dataInsert['defaultgrade'];
            $result = $this->assignQues($assignData);
            if ($result) {
                $answersData = [];
                $answersData['questionid'] = $assignData['questionid'];
                $answersData['answers'] = [];
                for ($j = 1; $j <= $answersCount; $j++) {
                    $answersData['answers'][$j]['content'] = $data['answer-' . $j];
                    if (isset($data['ans-grade-' . $j]) && $data['ans-grade-' . $j] == 'on') {
                        $answersData['answers'][$j]['fraction'] = 1;
                    } else {
                        $answersData['answers'][$j]['fraction'] = 0;
                    }
                }
            }
            $result = $this->addAnswers($answersData);
            return $result;
        }
        return false;
    }

    public function assignQues($data) {
        if (!isset($data)) {
            return false;
        }
        $model = $this->insertInto('assign', 'quizid, questionid, grade', ':quizid, :questionid, :grade')
            ->execute(array(
                'quizid' => $data['quizid'],
                'questionid' => $data['questionid'],
                'grade' => $data['grade']
            ));
        if ($model) {
            return true;
        } else {
            return false;
        }
    }

    public function addAnswers($data) {
        if (!isset($data)) {
            return false;
        }
        $isOk = true;
        foreach ($data['answers'] as $ans) {
            $model = $this->insertInto('answers', 'questionid, answer, fraction', ':questionid, :answer, :fraction')
            ->execute(array(
                'questionid' => $data['questionid'],
                'answer' => htmlspecialchars($ans['content']),
                'fraction' => $ans['fraction']
            ));
            if (!$model) {
                $isOk = false;
            }
        }
        return $isOk;
    }

    public function addAnswersSub($data) {
        if (!isset($data)) {
            return false;
        }
        $isOk = true;
        foreach ($data['answers'] as $ans) {
            $model = $this->insertInto('answers', 'questionid, answer, fraction', ':questionid, :answer, :fraction')
            ->execute(array(
                'questionid' => $ans['questionid'],
                'answer' => htmlspecialchars($ans['answer']),
                'fraction' => $ans['fraction']
            ));
            if (!$model) {
                $isOk = false;
            }
        }
        return $isOk;
    }

    public function deleteAnswerById($ans_id) {
        $model = $this->delete()
            ->from('answers')
            ->where('id = :id')
            ->execute(array('id' => $ans_id));
        if ($model) {
            return true;
        }
        return false;
    }

    public function getInfo($id, $quizid = null) {
        $data = [];
        $model = $this->select()
            ->from('questions')
            ->where('id = ' . $id)
            ->execute()
            ->fetch();
        if ($model) {
            $data['id'] = $model->id;
            $data['category'] = $model->category;
            $data['name'] = $model->name;
            $data['questiontext'] = $model->questiontext;
            $data['feedback'] = $model->feedback;
            $data['type'] = $model->type;
            $data['timecreated'] = $model->timecreated;
            $data['createdby'] = $model->createdby;
            $data['timemodified'] = $model->timemodified;
            $data['modifiedby'] = $model->modifiedby;
            $data['defaultgrade'] = $model->defaultgrade;
            if ($quizid != null) {
                $data['grade'] = $this->getGrade($quizid, $id);
            } else $data['grade'] = null;
            $data['answers'] = $this->getAnswersForQuestion($id);
            $data['isused'] = $this->questionIsUsed($id);
            $data['canBeChanged'] = $this->questionCanBeChanged($id);
        }
        return $data;
    }

    public function questionCanBeChanged($id){
        $model = $this->select('count(*) number')
            ->from('attemptdetails')
            ->where('questionid = ' . $id)
            ->execute()
            ->fetch();
        if($model && $model->number > 0){
            return false;
        }
        return true;
    }
    public function getBriefInfo($id) {
        $data = [];
        $model = $this->select()
            ->from('questions')
            ->where('id = ' . $id)
            ->execute()
            ->fetch();
        if ($model) {
            $data['id'] = $model->id;
            $data['category'] = $model->category;
            $data['name'] = $model->name;
            $data['questiontext'] = $model->questiontext;
            $data['feedback'] = $model->feedback;
            $data['type'] = $model->type;
            $data['defaultgrade'] = $model->defaultgrade;
            $data['answers'] = $this->getAnswersForQuestion($id);
            $data['isused'] = $this->questionIsUsed($id);
            $data['canBeChanged'] = $this->questionCanBeChanged($id);
        }
        return $data;
    }
    public function getAnswersForQuestion($id) {
        $result = [];
        $model = $this->select()
            ->from('answers')
            ->where('questionid = ' . $id)
            ->execute()
            ->fetchAll();
        if ($model) {
            $temp = array();
            foreach ($model as $key) {
                $temp['id'] = $key->id;
                $temp['answer'] = $key->answer;
                $temp['fraction'] = $key->fraction;
                array_push($result, $temp);
            }
        }
        return $result;
    }

    public function getAllByAttemptId($id) {
        $list = [];
        $model = $this->select('quizid, layout')
            ->from('attempts')
            ->where('id = ' . $id)
            ->execute()
            ->fetch();
        $arr = explode(',', $model->layout);
        if ($model) {
            foreach ($arr as $key) {
                $questionModel = new Questions();
                array_push($list, $questionModel->getInfo($key, $model->quizid));
            }
        }
        return $list;
    }

    public function getGrade($quizId, $questionId) {
        $model = $this->select('grade')
            ->from('assign')
            ->where('quizid = ' . $quizId)
            ->and('questionid = ' . $questionId)
            ->execute()
            ->fetch();
        if ($model) {
            return $model->grade;
        }
    }

    public function getQuestionsForBank($userId) {
        $list = [];
        $model = $this->select('id')
            ->from('questions')
            ->where('createdby = ' . $userId)
            ->execute()
            ->fetchAll();
        if ($model) {
            foreach ($model as $key) {
                $questionModel = new Questions();
                array_push($list, $questionModel->getBriefInfo($key->id));
            }
        }
        return $list;
    }
    public function questionIsUsed($id) {
        $model = $this->select('count(id) result')
            ->from('assign')
            ->where('questionid = ' . $id)
            ->execute()
            ->fetch();
        if($model && $model->result > 0){
            return 'X';
        }
        return 'Chưa sử dụng';
    }
    public function getQuestionsByQuiz($quizId) {
        $list = [];
        $model = $this->select('questionid')
            ->from('assign')
            ->where('quizid = ' . $quizId)
            ->execute()
            ->fetchAll();
        if ($model) {
            foreach ($model as $key) {
                $questionModel = new Questions();
                array_push($list, $questionModel->getInfo($key->questionid, $quizId));
            }
        }
        return $list;
    }

    public function updateQuesById($ques_id, $ques_name, $ques_text, $ques_feedback, $ques_type, $ques_grade) {
        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');
        $model = $this->update('questions')
            ->set('name = :name, questiontext = :questiontext, feedback = :feedback, type = :type, timemodified = :timemodified, modifiedby = :modifiedby, defaultgrade = :defaultgrade')
            ->where('id = :id')
            ->execute(array(
                'name' => $ques_name,
                'questiontext' => htmlspecialchars($ques_text),
                'feedback' => htmlspecialchars($ques_feedback),
                'type' => $ques_type,
                'timemodified' => $now,
                'modifiedby' => User::getId(),
                'defaultgrade' => $ques_grade,
                'id' => $ques_id
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateQuesById1($ques_id, $ques_name, $ques_text, $ques_feedback, $ques_type) {
        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');
        $model = $this->update('questions')
            ->set('name = :name, questiontext = :questiontext, feedback = :feedback, type = :type, timemodified = :timemodified, modifiedby = :modifiedby')
            ->where('id = :id')
            ->execute(array(
                'name' => $ques_name,
                'questiontext' => htmlspecialchars($ques_text),
                'feedback' => htmlspecialchars($ques_feedback),
                'type' => $ques_type,
                'timemodified' => $now,
                'modifiedby' => User::getId(),
                'id' => $ques_id
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateTempGrade($quiz_id, $ques_id, $ques_grade) {
        try {
            $model = $this->update('assign')
            ->set('grade = :grade')
            ->where('quizid = :quizid')
            ->and('questionid = :questionid')
            ->execute(array(
                'grade' => $ques_grade,
                'quizid' => $quiz_id,
                'questionid' => $ques_id
            ));
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllAnswerIds($question_id) {
        $model = $this->select('id')
            ->from('answers')
            ->where('questionid = :questionid')
            ->execute(array(
                'questionid' => $question_id
            ))
            ->fetchAll();
        return $model;
    }

    public function updateAnswerById($id, $data) {
        $model = $this->update('answers')
            ->set('answer = :answer, fraction = :fraction')
            ->where('id = :id')
            ->execute(array(
                'answer' => htmlspecialchars($data[0]),
                'fraction' => htmlspecialchars($data[1]),
                'id' => $id
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function getSeqByQuizIdAndQuesId($quiz_id, $ques_id) {
        try {
            $model = $this->select('seq')
            ->from('assign')
            ->where('quizid = :quizid')
            ->and('questionid = :questionid')
            ->execute(array('quizid' => $quiz_id, 'questionid' => $ques_id))
            ->fetch();
            return $model->seq;
        } catch (\Exception $e ) {
            return null;
        }
    }

    public function addQuesBank($data) {
        try {
            $dataInsert = [];
            $dataInsert['category'] = 1;
            $dataInsert['name'] = $data['name'];
            $dataInsert['questiontext'] = $data['text'];
            $dataInsert['feedback'] = $data['feedback'];
            $trueAnsCount = 0;
            $answersCount = 0;
            for ($i = 1; $i <= 8; $i++) {
                if (isset($data['ans-grade-' . $i]) && $data['ans-grade-' . $i] == 'on') {
                    $trueAnsCount++;
                }
                if (isset($data['answer-' . $i]) && $data['answer-' . $i]) {
                    $answersCount++;
                }
            }
            if ($trueAnsCount > 1) {
                $dataInsert['type'] = 2;
            } else {
                $dataInsert['type'] = 1;
            }
            $now = new DateTime();
            $dataInsert['timecreated'] = $now->format('Y-m-d H:i:s');
            if (User::logged()) {
                $dataInsert['createdby'] = User::getId();
            } else if (Admin::logged()) {

                $dataInsert['createdby'] = Admin::getId();
            }
            $dataInsert['defaultgrade'] = $data['grade'];
            $quesModel = $this->insertInto(
                'questions',
                'category, name, questiontext, feedback, type, timecreated, createdby, timemodified, modifiedby, defaultgrade',
                ':category, :name, :questiontext, :feedback, :type, :timecreated, :createdby, :timemodified, :modifiedby, :defaultgrade')
            ->execute(array(
                'category' => $dataInsert['category'],
                'name' => $dataInsert['name'],
                'questiontext' => htmlspecialchars($dataInsert['questiontext']),
                'feedback' => htmlspecialchars($dataInsert['feedback']),
                'type' => $dataInsert['type'],
                'timecreated' => $dataInsert['timecreated'],
                'createdby' => $dataInsert['createdby'],
                'timemodified' => null,
                'modifiedby' => null,
                'defaultgrade' => $dataInsert['defaultgrade']
            ));
            $quesModel = $this->select('id')
                ->from('questions')
                ->where('createdby = :createdby')
                ->orderBy('id DESC')
                ->execute(array('createdby' => $dataInsert['createdby']))
                ->fetch();
            if (!isset($quesModel->id)) {
                return false;
            }
            $answersData = [];
            $answersData['questionid'] = $quesModel->id;
            $answersData['answers'] = [];
            for ($j = 1; $j <= $answersCount; $j++) {
                $answersData['answers'][$j]['content'] = $data['answer-' . $j];
                if (isset($data['ans-grade-' . $j]) && $data['ans-grade-' . $j] == 'on') {
                    $answersData['answers'][$j]['fraction'] = 1;
                } else {
                    $answersData['answers'][$j]['fraction'] = 0;
                }
            }
            $result = $this->addAnswers($answersData);
            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteQuesionBankById($ques_id) {
        try {
            $model = $this->delete()->from('answers')->where('questionid = :questionid')->execute(array('questionid' => $ques_id));
            $model = $this->delete()->from('questions')->where('id = :id')->execute(array('id' => $ques_id));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function assignQuesBank($quiz_id, $ques_id, $grade) {
        try {
            $model = $this->select('id')
            ->from('assign')
            ->where('quizid = :quizid')
            ->and('questionid = :questionid')
            ->execute(array('quizid' => $quiz_id, 'questionid' => $ques_id))
            ->fetch();
            if (isset($model->id) && $model->id !== NULL) {
                return $model->id;
            } else {
                $model = $this->insertInto('assign', 'quizid, questionid, grade', ':quizid, :questionid, :grade')
                ->execute(array(
                    'quizid' => $quiz_id,
                    'questionid' => $ques_id,
                    'grade' => $grade
                ));
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateQuestionCate($ques_id, $cate_id) {
        try {
            $model = $this->update('questions')
            ->set('category = :category')
            ->where('id = :id')
            ->execute(array(
                'category' => $cate_id,
                'id' => $ques_id
            ));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}