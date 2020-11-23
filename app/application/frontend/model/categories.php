<?php

namespace Application\Frontend\Model;

use \Venus\Model as Model;
use \Venus\User as User;

class Categories extends Model {
    public $data = [];

    public function __construct($id = null) {
        parent::__construct();
        $this->id = (int)$id;
    }

    public function getInfo($id) {
        $this->getDetails($id);
        return $this->data;
    }

    public function getDetails($id) {
        $model = $this->select()
            ->from('categories')
            ->where('id = :id')
            ->execute(array('id' => $id))
            ->fetch();
        $this->data['id'] = $model->id;
        $this->data['name'] = $model->name;
        $this->data['shortname'] = $model->shortname;
        $this->data['description'] = $model->description;
        $this->data['visible'] = $model->visible;
        $this->data['timemodified'] = $model->timemodified;
        $this->data['numberOfQuestions'] = $this->getNumberOfQuestions($id);
        $this->data['numberOfQuizzes'] = $this->getNumberOfQuizzes($id);
        $this->data['numberOfAttempts'] = $this->getNumberOfAttempts($id);
        $this->data['numberOfParticipants'] = $this->getNumberOfParticipants($id);
    }

    public function getQuizzesByCategory($category, $mode) {
        $list = array();
        if($mode == 'shortname'){
            $id = $this->shortNameToId($category);
        }
        else {
            $id = $category;
        }
        $model = $this->select('id')
            ->from('quiz')
            ->where('category = :category')
            ->and('visible = 1')
            ->execute(array('category' => $id))
            ->fetchAll();
        foreach ($model as $key) {
            $quiz = new Quiz();
            array_push($list, $quiz->getInfo($key->id));
        }
        return $list;
    }
    public function getDetailsByShortName($shortname) {
        $id = $this->shortNameToId($shortname);
        if($id){
            $this->getDetails($id);
            return $this->data;
        }
        return 0;
    }

    public function idToName($id) {
        $model = $this->select('name')
            ->from('categories')
            ->where('id = :id')
            ->execute(array('id' => $id))
            ->fetch();
        return isset($model->name) ? $model->name : null;
    }
    public function idToShortName($id) {
        $model = $this->select('shortname')
            ->from('categories')
            ->where('id = :id')
            ->execute(array('id' => $id))
            ->fetch();
        if($model)
            return $model->shortname;
    }
    public function shortNameToId($shortname) {
        $model = $this->select('id')
            ->from('categories')
            ->where('shortname = :shortname')
            ->execute(array('shortname' => $shortname))
            ->fetch();
        if($model)
            return $model->id;
    }
    public function getNumberOfQuestions($id) {
        $model = $this->select()
            ->from('questions')
            ->where('category = :id')
            ->execute(array('id' => $id))
            ->fetchAll();
        return count($model);
    }
    public function getNumberOfQuizzes($id) {
        $model = $this->select()
            ->from('quiz')
            ->where('category = :id')
            ->execute(array('id' => $id))
            ->fetchAll();
        return count($model);
    }
    public function getNumberOfAttempts($id) {
        $model = $this->select('a.id')
            ->from('attempts a, quiz q, categories c')
            ->where('c.id = q.category')
            ->and('a.quizid = q.id')
            ->and('c.id = '. $id)
            ->execute()
            ->fetchAll();
        return count($model);
    }
    public function getNumberOfParticipants($id) {
        $model = $this->select('count(distinct userid) result')
            ->from('categories c, attempts a, quiz q')
            ->where('c.id = q.category')
            ->and('a.quizid = q.id')
            ->and('c.id = '. $id)
            ->execute()
            ->fetch();
        return $model->result;
    }
    public function getAllCategories() {
        $list = [];
        $model = $this->select()
            ->from('categories')
            ->where('visible = 1')
            ->execute()
            ->fetchAll();
        if ($model) {
            foreach ($model as $key){
                array_push($list, (new Categories())->getInfo($key->id));
            }
        }
        return $list;
    }
    public function getUsersWithMostAttempts($categoryId){
        $model = $this->select('users.id, users.lastname, users.firstname, count(attempts.id) number')
            ->from('users, attempts, quiz')
            ->where('attempts.quizid = quiz.id')
            ->and('users.id = attempts.userid')
            ->and('quiz.category = ' . $categoryId)
            ->groupBy('users.id')
            ->orderBy('number desc')
            ->limit(15)
            ->execute()
            ->fetchAll();
        if($model){
            return $model;
        }
        return null;
    }
}