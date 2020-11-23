<?php
namespace Application\Admin\Model;
use \Venus\Model as Model;

class Question extends Model {
    public function __construct() {
        parent::__construct('questions');
    }

    public function getAllQuestions() {
        $model = $this->select()
            ->from($this->table)
            ->execute()
            ->fetchAll();
        return $model;
    }
}