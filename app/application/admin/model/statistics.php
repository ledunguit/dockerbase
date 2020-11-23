<?php

namespace Application\Admin\Model;

use \Venus\Model as Model;
use \Application\Frontend\Model\Questions as Questions;
use \Application\Frontend\Model\Attempts as Attempts;

class Statistics extends Model {
    public function getNumberOfHiddenCategories() {
        $model = $this->select('count(*) number')
            ->from('categories')
            ->where('visible = 0')
            ->execute()
            ->fetch();
        if ($model) {
            return $model->number;
        }
        return 0;
    }

    public function getNumberOfHiddenQuizzes() {
        $model = $this->select('count(*) number')
            ->from('quiz')
            ->where('visible = 0')
            ->execute()
            ->fetch();
        if ($model) {
            return $model->number;
        }
        return 0;
    }

    public function getNumberOfBlockedUsers() {
        $model = $this->select('count(*) number')
            ->from('users')
            ->where('privilege = 0')
            ->execute()
            ->fetch();
        if ($model) {
            return $model->number;
        }
        return 0;
    }

    public function getNumberOfSystemQuiz() {
        $model = $this->select('count(quiz.id) number')
            ->from('quiz, users')
            ->where('quiz.createdby = users.id')
            ->and('privilege = 5')
            ->execute()
            ->fetch();
        if ($model) {
            return $model->number;
        }
        return 0;
    }

    public function getNumberOfContributedQuiz() {
        $model = $this->select('count(quiz.id) number')
            ->from('quiz, users')
            ->where('quiz.createdby = users.id')
            ->and('privilege != 5')
            ->execute()
            ->fetch();
        if ($model) {
            return $model->number;
        }
        return 0;
    }

    public function getNumberOfQuestions() {
        $model = $this->select('count(*) number')
            ->from('questions')
            ->execute()
            ->fetch();
        if ($model) {
            return $model->number;
        }
        return 0;
    }

    public function getAttemptTimesByDayAgo($dayAgo) {
        $model = $this->select('CONCAT(day(timestarted), "/", month(timestarted)) date')
            ->from('attempts')
            ->where('day(timestarted) = day((NOW() - INTERVAL ' . $dayAgo . ' DAY)) ')
            ->and('month(timestarted) = month((NOW() - INTERVAL ' . $dayAgo . ' DAY))')
            ->and('year(timestarted) = year((NOW() - INTERVAL ' . $dayAgo . ' DAY))')
            ->orderBy('date asc')
            ->execute()
            ->fetchAll();
        if ($model) {
            $result['date'] = $model[0]->date;
            $result['count'] = count($model);
            return $result;
        }
        return array('date' => '', 'count' => 0);
    }

    public function getAttemptTimeForTwoWeeks() {
        $result = [];
        for ($i = 13; $i >= 0; $i--) {
            array_push($result, $this->getAttemptTimesByDayAgo($i));
        }
        return $result;
    }

    public function getResultPercents() {
        $attemptModel = new Attempts();
        $model = $this->select('id')
            ->from('attempts')
            ->execute()
            ->fetchAll();
        $result = array(0, 0, 0, 0, 0);
        if ($model) {
            foreach ($model as $key) {
                $grade = $attemptModel->getSimpleAttemptsByQuiz($key->id)['gradeByBaseGrade'];
                if ($grade >= 9) {
                    $result[0]++;
                } else if ($grade >= 8) {
                    $result[1]++;
                } else if ($grade >= 6.5) {
                    $result[2]++;
                } else if ($grade >= 5) {
                    $result[3]++;
                } else {
                    $result[4]++;
                }
            }
        }
        return $result;
    }

    public function getNumberOfUsers($mode = null) {
        $where = '1';
        if ($mode == 'day') {
            $where = 'day(firstaccess) = day(now()) and month(firstaccess) = month(now()) and year(firstaccess) = year(now())';
        } else if ($mode == 'month') {
            $where = 'month(firstaccess) = month(now()) and year(firstaccess) = year(now())';
        } else if ($mode == 'year') {
            $where = 'year(firstaccess) = year(now())';
        }
        $model = $this->select('count(*) number')
            ->from('users')
            ->where($where)
            ->execute()
            ->fetch();
        if ($model) {
            return $model->number;
        }
        return 0;
    }

    public function getNumberOfGuest($mode = null) {
        $where = '1';
        if ($mode == 'day') {
            $where = 'day(timestarted) = day(now()) and month(timestarted) = month(now()) and year(timestarted) = year(now())';
        } else if ($mode == 'month') {
            $where = 'month(timestarted) = month(now()) and year(timestarted) = year(now())';
        } else if ($mode == 'year') {
            $where = 'year(timestarted) = year(now())';
        }
        $model = $this->select('count(distinct(guest)) number')
            ->from('attempts')
            ->where($where)
            ->and('isnull(userid)')
            ->execute()
            ->fetch();
        if ($model) {
            return $model->number;
        }
        return 0;
    }

    public function getNumberOfAttempts($mode = null) {
        $where = '1';
        if ($mode == 'day') {
            $where = 'day(timestarted) = day(now()) and month(timestarted) = month(now()) and year(timestarted) = year(now())';
        } else if ($mode == 'month') {
            $where = 'month(timestarted) = month(now()) and year(timestarted) = year(now())';
        } else if ($mode == 'year') {
            $where = 'year(timestarted) = year(now())';
        }
        $model = $this->select('count(*) number')
            ->from('attempts')
            ->where($where)
            ->and('isnull(guest)')
            ->execute()
            ->fetch();
        if ($model) {
            return $model->number;
        }
        return 0;
    }

    public function getNumberOfQuiz($mode = null) {
        $where = '1';
        if ($mode == 'day') {
            $where = 'day(timecreated) = day(now()) and month(timecreated) = month(now()) and year(timecreated) = year(now())';
        } else if ($mode == 'month') {
            $where = 'month(timecreated) = month(now()) and year(timecreated) = year(now())';
        } else if ($mode == 'year') {
            $where = 'year(timecreated) = year(now())';
        }
        $model = $this->select('count(*) number')
            ->from('quiz')
            ->where($where)
            ->execute()
            ->fetch();
        if ($model) {
            return $model->number;
        }
        return 0;
    }

    public function getPopularQuiz() {
        $model = $this->select('quiz.id, quiz.name, count(quiz.id) number')
            ->from('quiz, attempts')
            ->where('quiz.id = attempts.quizid')
            ->groupBy('quiz.id')
            ->orderBy('number desc')
            ->limit(5)
            ->execute()
            ->fetchAll();
        if($model){
            return $model;
        }
        return null;
    }
    public function getDataForPie(){
        $model = $this->select('categories.name, count(quiz.id) number')
            ->from('categories, quiz')
            ->where('quiz.category = categories.id')
            ->groupBy('categories.id')
            ->execute()
            ->fetchAll();
        if($model){
            return $model;
        }
        return null;
    }
}