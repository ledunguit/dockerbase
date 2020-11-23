<?php

namespace Application\Frontend\Model;

use \Venus\Model as Model;
use \Venus\User as User;
use Application\Frontend\Model\Quiz as Quiz;

class Index extends Model {
    public $data = [];
    public $quiz = [];

    public function loadStatis() {
        $data['catesCount'] = $this->countCates();
        $data['usersCount'] = $this->countUsers();
        $data['quizsCount'] = $this->countQuizs();
        $data['attemptsCount'] = $this->countAttempts();
        return $data;
    }

    public function countCates() {
        $model = $this->select()
            ->from('categories')
            ->where('visible = :visible')
            ->execute(array('visible' => 1))
            ->fetchAll();
        return count($model);
    }

    public function countQuizs() {
        $model = $this->select()
            ->from('quiz')
            ->where('visible = :visible')
            ->execute(array('visible' => 1))
            ->fetchAll();
        return count($model);
    }

    public function countUsers() {
        $model = $this->select()
            ->from('users')
            ->where('privilege < :privilege')
            ->execute(array('privilege' => 5))
            ->fetchAll();
        return count($model);
    }

    public function countAttempts() {
        $model = $this->select()
            ->from('attempts')
            ->execute()
            ->fetchAll();
        return count($model);
    }

    public function getRecentQuiz() {
        $model = $this->select('id')
            ->from('quiz')
            ->where('visible = 1')
            ->and('category != 1')
            ->orderBy('id desc')
            ->limit(8)
            ->execute()
            ->fetchAll();

        foreach ($model as $key) {
            $temp = new Quiz();
            array_push($this->quiz, $temp->getInfo($key->id));
        }
        return $this->quiz;
    }

    public function getRecentLogin(){
        $model = $this->select('id, firstname, lastname, lastlogin')
            ->from('users')
            ->where('id > 1')
            ->orderBy('lastlogin desc')
            ->limit(10)
            ->execute()
            ->fetchAll();
        if($model){
            return $model;
        }
        return null;
    }
}