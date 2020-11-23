<?php
namespace Application\Admin\Model;
use Application\Frontend\Model\Categories;
use Application\Frontend\Model\Users;
use \Venus\Model as Model;
use Venus\User as UserLib;

class Quiz extends Model {
    public $data = [];
    public function __construct() {
        parent::__construct('quiz');
    }

    public function getInfo($id) {
        $this->id = (int) $id;
        $this->getDetails($id);
        return $this->data;
    }

    private function getNumberOfQuestions($id) {
        $model = $this->select()
            ->from('assign')
            ->where('quizid = :quizid')
            ->execute(array('quizid' => $id))
            ->fetchAll();
        return count($model);
    }

    private function getNumberOfAttempts($id) {
        $model = $this->select()
            ->from('attempts')
            ->where('quizid = :quizid')
            ->execute(array('quizid' => $id))
            ->fetchAll();
        return count($model);
    }

    public function getDetails($id) {
        $model = $this->select()
            ->from('quiz')
            ->where('id = :id')
            ->execute(array('id' => $id))
            ->fetch();
        if ($model) {
            $this->data['id'] = $model->id;
            $this->data['category'] = $model->category;
            $this->data['code'] = $model->code;
            $this->data['name'] = $model->name;
            $this->data['summary'] = $model->summary;
            $this->data['timeopen'] = $model->timeopen;
            $this->data['timeclose'] = $model->timeclose;
            $this->data['timelimit'] = $model->timelimit;
            $this->data['overduehandling'] = $model->overduehandling;
            $this->data['attempt'] = $model->attempt;
            $this->data['grademethod'] = $model->grademethod;
            $this->data['review'] = $model->review;
            $this->data['shuffleanswer'] = $model->suffleanswer;
            $this->data['sufflequestion'] = $model->sufflequestion;
            $this->data['timecreated'] = $model->timecreated;
            $this->data['createdby'] = $model->createdby;
            $this->data['timemodified'] = $model->timemodified;
            $this->data['modifiedby'] = $model->modifiedby;
            $this->data['password'] = $model->password;
            $this->data['questionsperpage'] = $model->questionsperpage;
            $this->data['showdetails'] = $model->showdetails;
            $this->data['navmethod'] = $model->navmethod;
            $this->data['acceptguest'] = $model->acceptguest;
            $this->data['visible'] = $model->visible;
            $this->data['status'] = $model->status;
            $this->data['numberOfQuestions'] = $this->getNumberOfQuestions($id);
            $this->data['numberOfAttempts'] = $this->getNumberOfAttempts($id);
            $this->data['createdByName'] = $this->getUserCreate($this->data['createdby']);
            $this->data['modifiedByName'] = $this->getUserModify($this->data['modifiedby']);
            $this->data['categoryName'] = $this->getCategoryName($this->data['category']);
            $this->data['categoryShortName'] = $this->getCategoryShortName($this->data['category']);
            $this->data['timeLimitConverted'] = $this->convertTime($this->data['timelimit']);
        }
    }

    public function getUserCreate($userid) {
        return (new Users())->idToName($userid);
    }

    public function getUserModify($userid) {
        return (new Users())->idToName($userid);
    }

    public function getCategoryName($id) {
        return (new Categories())->idToName($id);
    }

    public function getCategoryShortName($id) {
        return (new Categories())->idToShortName($id);
    }

    public function convertTime($time) {
        $hour = 0;
        $minute = 0;
        $second = 0;
        if ($time <= 3600) {
            $minute = $time / 60;
            $second = $time % 60;
        } else {
            $hour = $time % 3600;
            $minute = $time / 3600;
            $second = $time - 60 * $minute;
        }
        $str = '';
        if($hour != 0)
            $str .= $hour.' giờ ';
        if($minute != 0)
            $str .= $minute.' phút ';
        if($second != 0)
            $str .= $second.' giây';
        return $str.trim('');
    }

}