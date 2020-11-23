<?php

namespace Application\Frontend\Model;

use \Venus\Model as Model;
use \Venus\User as User;

class AttemptDetails extends Model {
    public $data = [];

    public function __construct($id = null) {
        parent::__construct();
        $this->id = (int)$id;
    }

    public function choseAnswersByAttempt($id) {
        $list = [];
        $model = $this->select()
            ->from('attemptdetails')
            ->where('attemptid = ' . $id)
            ->execute()
            ->fetchAll();
        if ($model) {
            foreach ($model as $key) {
                $temp = array();
                $arr = explode(',', $key->chose);
                $temp['chose'] = array();
                $temp['grade'] = 0;
                if ($this->getNumberOfTrueAns($key->questionid) == 1) {
                    $temp['chose'] = $arr[0];
                    if ($this->checkAnswer($arr[0]) > 0) {
                        $temp['grade'] = $this->getGrade($id, $key->questionid);
                    }
                } else {
                    foreach ($arr as $k) {
                        array_push($temp['chose'], $k);
                        if ($this->checkAnswer($k) > 0) {
                            $temp['grade'] += $this->getGrade($id, $key->questionid) / $this->getNumberOfTrueAns($key->questionid);
                        }
                    }
                    $choose = count($arr);
                    $delta = $choose - $this->getNumberOfTrueAns($key->questionid);
                    if($choose == $this->getNumberOfAns($key->questionid)){
                        $temp['grade'] = 0;
                    }
                    else if($delta > 0){
                        $temp['grade'] -= ($this->getGrade($id, $key->questionid) / $this->getNumberOfTrueAns($key->questionid))*$delta;
                    }
                    if($temp['grade'] < 0){
                        $temp['grade'] = 0;
                    }
                }
                array_push($list, $temp);
            }
        }
        return $list;
    }

    public function getNumberOfAns($questionId){
        $model = $this->select('count(*) number')
            ->from('answers')
            ->where('questionid = ' . $questionId)
            ->execute()
            ->fetch();
        if($model){
            return $model->number;
        }
        return 0;
    }
    public function getNumberOfTrueAns($questionId){
        $model = $this->select('count(*) number')
            ->from('answers')
            ->where('questionid = ' . $questionId)
            ->and('fraction > 0')
            ->execute()
            ->fetch();
        if($model){
            return $model->number;
        }
        return 0;
    }
    public function checkAnswer($answerId) {
        if ($answerId == null) {
            return 0;
        }
        $model = $this->select('fraction')
            ->from('answers')
            ->where('id = ' . $answerId)
            ->execute()
            ->fetch();
        return $model->fraction;
    }

    public function getGrade($attemptId, $questionId) {
        $model = $this->select('assign.grade')
            ->from('assign, attempts, quiz')
            ->where('questionid = ' . $questionId)
            ->and('attempts.quizid = quiz.id')
            ->and('assign.quizid = quiz.id')
            ->and('attempts.id = ' . $attemptId)
            ->execute()
            ->fetch();
        if ($model) {
            return $model->grade;
        }
        else {
            return 0;
        }
    }

    public function insertData($attemptId, $questionId, $chose) {
        $model = $this->insertInto('attemptdetails', 'attemptid, questionid, chose', ':attemptid, :questionid, :chose')
            ->execute(array(
                'attemptid' => $attemptId,
                'questionid' => $questionId,
                'chose' => $chose,
            ));
        if ($model) {
            return true;
        } else {
            return false;
        }
    }

    public function updateAnswersCheckBox($attemptId, $questionId, array $chose) {
        $allChose = "";
        for ($i = 0; $i < count($chose); $i++) {
            if ($i != (count($chose) - 1)) {
                $allChose .= $chose[$i] . ',';
            } else {
                $allChose .= $chose[$i];
            }
        }
        $model = $this->update('attemptdetails')
            ->set('chose = :chose')
            ->where('attemptid = :attemptid')
            ->and('questionid = :questionid')
            ->execute(array('chose' => $allChose, 'attemptid' => $attemptId, 'questionid' => $questionId));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateAnswersRadio($attemptId, $questionId, $chose) {
        $model = $this->update('attemptdetails')
            ->set('chose = :chose')
            ->where('attemptid = :attemptid')
            ->and('questionid = :questionid')
            ->execute(array('chose' => $chose, 'attemptid' => $attemptId, 'questionid' => $questionId));
        if ($model) {
            return true;
        }
        return false;
    }
}