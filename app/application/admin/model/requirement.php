<?php
namespace Application\Admin\Model;
use \Venus\Model as Model;

class Requirement extends Model {
    public function __construct() {
        parent::__construct('users');
    }

    public function getRequirements() {
        $model = $this->select('requirement.id, quiz.id quizid, name, code')
            ->from('requirement, quiz')
            ->where('quiz.id = requirement.quizid')
            ->execute()
            ->fetchAll();
        return $model;
    }

    public function removeRequirementByQuizId($quiz_id) {
        try {
            $model = $this->delete()
            ->from('requirement')
            ->where('quizid = :quizid')
            ->execute(array('quizid' => $quiz_id));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}