<?php
namespace Application\Admin\Model;
use \Venus\Model as Model;
use \Application\Frontend\Model\Questions as Questions;

class QuestionBank extends Model {

    public function getNumberOfQuestions($bankId){
        $model = $this->select('count(id) num')
            ->from('bankdetails')
            ->where('bankid = '. $bankId)
            ->execute()
            ->fetch();
        if($model){
            return $model->num;
        }
        return 0;
    }
    public function getQuestionsByBank($bankId){
        $list = [];
        $model = $this->select('questionid')
            ->from('bankdetails')
            ->where('bankid = ' . $bankId)
            ->execute()
            ->fetchAll();
        $ques = new Questions();
        if($model){
            foreach ($model as $key) {
                array_push($list, $ques->getInfo($key->questionid));
            }
            return $list;
        }
        return null;
    }
    public function getQuestionsByCategory($category) {
        $list = [];
        $model = $this->select('id')
            ->from('questions')
            ->where('category = ' . $category)
            ->execute()
            ->fetchAll();
        $ques = new Questions();
        if($model){
            foreach ($model as $key) {
                array_push($list, $ques->getInfo($key->id));
            }
            return $list;
        }
        return null;
    }
}