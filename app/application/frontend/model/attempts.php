<?php

namespace Application\Frontend\Model;

use Application\Frontend\Model\Users as UserModel;
use Application\Frontend\Model\Quiz as Quiz;
use \Venus\Model as Model;
use \Venus\User as User;
use \DateTime;
use \DateInterval;

class Attempts extends Model {
    public $data = [];

    public function __construct($id = null) {
        parent::__construct();
        $this->id = (int)$id;
    }

    public function getInfo($id) {
        $model = $this->select()
            ->from('attempts')
            ->where('id = :id')
            ->execute(array('id' => $id))
            ->fetch();
        if ($model) {
            $this->data['id'] = $model->id;
            $this->data['quizid'] = $model->quizid;
            $user = new UserModel();
            $this->data['userid'] = $model->userid;
            $this->data['fullname'] = $user->idToName($this->data['userid']);
            $this->data['guest'] = $model->guest;
            $this->data['timestarted'] = $model->timestarted;
            $this->data['timesubmitted'] = $model->timesubmitted;
            $this->data['layout'] = explode(',', $model->layout);
            $this->data['grade'] = $this->computeGrade($this->data['id']);
            $quiz = (new Quiz())->getInfo($model->quizid);
            $this->data['sumgrade'] = $this->getSumGradeByQuiz($this->data['quizid']);
            $this->data['percentage'] = round(($this->data['grade'] / $this->data['sumgrade']) * 100, 2);
            $this->data['rank'] = $this->computeRank($this->data['percentage']);
            $this->data['gradeByBaseGrade'] = $this->data['percentage'] / 10;
            $this->data['attemptSequence'] = $this->getAttemptSeq($this->data['id'], $this->data['quizid'], $this->data['userid']);
            $this->data['duration'] = $this->computeDuration($this->data['timestarted'], $this->data['timesubmitted']);
            $this->data['average'] = $this->computeAverageGrade($this->data['quizid'], $this->data['userid']);
            $this->data['inProgress'] = $this->getStatus($this->data['timestarted'], $this->data['timesubmitted'], $quiz['timelimit']);
        }
        return $this->data;
    }

    public function getSimpleAttemptsByQuiz($id) {
        $list = [];
        $model = $this->select('id')
            ->from('attempts')
            ->where('quizid = :quizid')
            ->orderBy('userid ASC')
            ->execute(array('quizid' => $id))
            ->fetchAll();
        if ($model) {
            foreach ($model as $key) {
                $m = $this->select()
                    ->from('attempts')
                    ->where('id = :id')
                    ->execute(array('id' => $key->id))
                    ->fetch();
                $temp = [];
                if ($model) {
                    $temp['id'] = $m->id;
                    $temp['grade'] = number_format($this->computeGrade($temp['id']),2);
                    $user = new UserModel();
                    $temp['userid'] = $m->userid;
                    $temp['fullname'] = $user->idToName($m->userid);
                    $temp['email'] = $user->idToEmail($m->userid);
                    $temp['guest'] = $m->guest;
                    $temp['timesubmitted'] = $m->timestarted;
                    $temp['timesubmitted'] = $m->timesubmitted;
                    $temp['sumgrade'] = number_format($this->getSumGradeByQuiz($id),2);
                    $temp['percentage'] = round(($temp['grade'] / $temp['sumgrade']) * 100, 2);
                    $temp['rank'] = $this->computeRank($temp['percentage']);
                    $temp['gradeByBaseGrade'] = round($temp['percentage'] / 10, 2);
                }
                array_push($list, $temp);
            }
        }
        return $list;
    }


    public function getStatus($timeStarted, $timeSubmitted, $timeLimit) {
        $start = date_create($timeStarted);
        $submit = date_create($timeSubmitted);
        $temp = date_add(date_create($timeStarted), date_interval_create_from_date_string($timeLimit . ' seconds'));
        $now = date_create();
        if($start <= $temp && $now < $submit&& $now > $start) {
            $str = 'in progress';
        }
        else {
            $str = 'finished';
        }
        return $str;
    }
    public function computeGrade($attemptId) {
        $model = $this->select('questionid, chose')
            ->from('attemptdetails')
            ->where('attemptid = :attemptid')
            ->execute(array('attemptid' => $attemptId))
            ->fetchAll();
        $attemptDetailsModel = new AttemptDetails();
        $grade = 0;
        if ($model) {
            foreach ($model as $key) {
                $a = $attemptDetailsModel->getGrade($attemptId, $key->questionid);
                $arr = explode(',', $key->chose);
                if ($attemptDetailsModel->getNumberOfTrueAns($key->questionid) == 1 && ($attemptDetailsModel->checkAnswer($arr[0]) > 0)) {
                    $grade += $a;
                } else {
                    foreach ($arr as $k) {
                        if ($attemptDetailsModel->checkAnswer($k) > 0) {
                            $grade += $a / $attemptDetailsModel->getNumberOfTrueAns($key->questionid);
                        }
                    }
                    $choose = count($arr);
                    $delta = $choose - $attemptDetailsModel->getNumberOfTrueAns($key->questionid);
                    if($choose == $attemptDetailsModel->getNumberOfAns($key->questionid)){
                        $grade -= $a;
                    }
                    else if($delta > 0){
                        $sub = ($a / $attemptDetailsModel->getNumberOfTrueAns($key->questionid))*$delta;
                        if($sub > $a){
                            $grade -= $a;
                        }
                        else {
                            $grade -= $sub;
                        }
                    }
                    if($grade < 0){
                        $grade += 0;
                    }
                }
            }
        }
        return $grade;
    }

    public function computeAverageGrade($quizId, $userId) {
        $model = $this->select('grademethod')
            ->from('quiz')
            ->where('id = :id')
            ->execute(array('id' => $quizId))
            ->fetch();
        if ($model) {
            $method = $model->grademethod;
        } else {
            return 0;
        }
        $model = $this->select('id')
            ->from('attempts')
            ->where('quizid = :quizid')
            ->and('userid = :userid')
            ->execute(array('quizid' => $quizId, 'userid' => $userId))
            ->fetchAll();
        if ($model) {
            $number = count($model);
            if($number == 0) {
                return 0;
            }
            if ($method == 1) {
                return $this->computeGrade($model[0]->id);
            } else if ($method == 2) {
                return $this->computeGrade($model[$number - 1]->id);
            } else if ($method == 3) {
                $temp = array();
                foreach ($model as $key) {
                    array_push($temp, $this->computeGrade($key->id));
                }
                return max($temp);
            } else if ($method == 4) {
                $temp = array();
                foreach ($model as $key) {
                    array_push($temp, $this->computeGrade($key->id));
                }
                return min($temp);
            } else if ($method == 5) {
                $sum = 0;
                foreach ($model as $key) {
                    $sum += $this->computeGrade($key->id);
                }
                if (count($model) != 0) {
                    return $sum / count($model);
                }
            }
        }
        return 0;
    }

    public function getSumGradeByQuiz($quizId) {
        $model = $this->select('sum(grade) grade')
            ->from('assign')
            ->where('quizid = :quizid')
            ->execute(array('quizid' => $quizId))
            ->fetch();
        if ($model) {
            return $model->grade;
        }
        return null;
    }

    public function getAttemptSeq($attemptId, $quizId, $userId) {
        $model = $this->select('count(*) as seq')
            ->from('attempts')
            ->where('id <= :attemptid')
            ->and('quizid = :quizid')
            ->and('userid = :userid')
            ->execute(array(
                'attemptid' => $attemptId,
                'quizid' => $quizId,
                'userid' => $userId
            ))
            ->fetch();
        return $model->seq;
    }

    public function computeDuration($begin, $end) {
        $begin = date_create($begin);
        $end = date_create($end);
        $duration = $begin->diff($end);
        return array('hours' => $duration->h, 'minutes' => $duration->i, 'seconds' => $duration->s);
    }

    private function computeRank($percentage) {
        $rank = 'Yếu';
        if ($percentage >= 50)
            $rank = 'Trung bình';
        if ($percentage > 65)
            $rank = 'Khá';
        if ($percentage > 80)
            $rank = 'Giỏi';
        if ($percentage > 95)
            $rank = 'Xuất sắc';
        return $rank;
    }

    public function getAttemptsByQuiz($quizId, $userId = null) {
        $list = [];
        if ($userId != null) {
            $model = $this->select('id')
                ->from('attempts')
                ->where('quizid = :quizid')
                ->and('userid = :userid')
                ->execute(array('quizid' => $quizId, 'userid' => $userId))
                ->fetchAll();
        } else {
            $model = $this->select('id')
                ->from('attempts')
                ->where('quizid = :quizid')
                ->execute(array('quizid' => $quizId))
                ->fetchAll();
        }
        if ($model) {
            foreach ($model as $key) {
                array_push($list, $this->getInfo($key->id));
            }
        }
        return $list;
    }

    public function convertLimitTime($limitTime) {
        $seconds = $limitTime;
        $minutes = 0;
        $hour = 0;
        if ($limitTime != null) {
            while ($seconds >= 60) {
                $minutes++;
                $seconds -= 60;
            }
            while ($minutes >= 60) {
                $hour++;
                $minutes -= 60;
            }
        } else {
            $seconds = 0;
            $minutes = 0;
            $hour = 999;
        }
        return $hour . 'H' . $minutes . 'M' . $seconds . 'S';
    }

    public function getAttemptIdByQuizAndUserId($quizId, $userId) {
        $model = $this->select('id')
            ->from('attempts')
            ->where('quizid = :quizid')
            ->and('userid = :userid')
            ->orderBy('id desc')
            ->execute(array('quizid' => $quizId, 'userid' => $userId))
            ->fetch();
        if ($model) {
            return $model->id;
        }
    }

    public function getAttemptIdByQuizAndGuestEmail($quizId, $guest) {
        $model = $this->select('id')
            ->from('attempts')
            ->where('quizid = :quizid')
            ->and('guest = :guest')
            ->orderBy('id desc')
            ->execute(array('quizid' => $quizId, 'guest' => $guest))
            ->fetch();
        if ($model) {
            return $model->id;
        }
    }

    public function insertData($quizId, $userId, $guest, $timeStarted, $timeSubmitted, $layout) {
        $currentAttemptId = null;
        if ($timeStarted == null) {
            $timeStarted = date("Y-m-d H:i:s");
        }
        if ($timeSubmitted == null) {
            $currentDateTime = date("Y-m-d H:i:s");
            $tempDateTime = new DateTime($currentDateTime);
            $quizModel = new Quiz();
            $timeLimitOfQuiz = $quizModel->getTimeLimitByQuizId($quizId);
            $timeAdd = $this->convertLimitTime($timeLimitOfQuiz->timelimit);
            $tempDateTime->add(new DateInterval('PT' . $timeAdd));
            $timeSubmitted = $tempDateTime->format('Y-m-d H:i:s');
        }
        $model = $this->insertInto('attempts', 'quizid, userid, guest, timestarted, timesubmitted, layout', ':quizid, :userid, :guest,:timestarted, :timesubmitted, :layout')
            ->execute(array(
                'quizid' => $quizId,
                'userid' => $userId,
                'guest' => $guest,
                'timestarted' => $timeStarted,
                'timesubmitted' => $timeSubmitted,
                'layout' => $layout
            ));
        if ($model != null) {
            $temp = explode(',', $layout);
            $attemptDetailsModel = new AttemptDetails();
            if ($userId == null) {
                $currentAttemptId = $this->getAttemptIdByQuizAndGuestEmail($quizId, $guest);
            } else {
                $currentAttemptId = $this->getAttemptIdByQuizAndUserId($quizId, $userId);
            }
            foreach ($temp as $quizIdTemp) {
                $result = $attemptDetailsModel->insertData($currentAttemptId, $quizIdTemp, null, 1);
                if ($result == false) {
                    return null;
                }
            }
            return $currentAttemptId;
        } else {
            return null;
        }
    }

    public function submitAttempt($attemptId) {
        $currentDateTime = date("Y-m-d H:i:s");
        $model = $this->update('attempts')
            ->set('timesubmitted = :timesubmitted')
            ->where('id = :attemptid')
            ->execute(array('timesubmitted' => $currentDateTime, 'attemptid' => $attemptId));
        if ($model) {
            return true;
        }
        return false;
    }

    public function getAttemptSumittedTime($quizId, $userId) {
        $model = $this->select('timesubmitted')
            ->from('attempts')
            ->where('quizid = :quizid')
            ->and('userid = :userid')
            ->orderBy('id desc')
            ->execute(array('quizid' => $quizId, 'userid' => $userId))
            ->fetch();
        if ($model) {
            return $model;
        } else {
            return null;
        }
    }

    public function getAttemptSumittedTimeGuest($quizId, $guest) {
        $model = $this->select('timesubmitted')
            ->from('attempts')
            ->where('quizid = :quizid')
            ->and('guest = :guest')
            ->orderBy('id desc')
            ->execute(array('quizid' => $quizId, 'guest' => $guest))
            ->fetch();
        if ($model) {
            return $model;
        } else {
            return null;
        }
    }
    public function getNumberOfAttempts($quizId, $userId) {
        $model = $this->select('count(*) as number')
            ->from('attempts')
            ->where('quizid = ' . $quizId)
            ->and('userid = ' .$userId)
            ->execute()
            ->fetch();
        if($model){
            return $model->number;
        }
        return null;
    }
    public function getGradeByQuiz($quizId, $userId) {
        $arr = [];
        $model = $this->select('id')
            ->from('attempts')
            ->where('quizid = ' . $quizId)
            ->and('userid = ' . $userId)
            ->execute()
            ->fetch();
        if ($model) {
            $arr['quizId'] = $quizId;
            $arr['name'] = (new Quiz())->idToName($quizId);
            $arr['numberOfAttempts'] = $this->getNumberOfAttempts($quizId, $userId);
            $arr['grade'] = $this->computeAverageGrade($quizId, $userId);
            $arr['sumgrade'] = $this->getSumGradebyQuiz($quizId);
            $arr['percentage'] = round(($arr['grade'] / $arr['sumgrade']) * 100, 2);
            $arr['rank'] = $this->computeRank($arr['percentage']);
        }
        return $arr;
    }
    public function getGradeTable($userId) {
        $list = [];
        $model = $this->select('distinct(quizid) id')
            ->from('attempts')
            ->where('userid = ' . $userId)
            ->execute()
            ->fetchAll();
        if($model){
            foreach ($model as $key) {
                array_push($list, $this->getGradeByQuiz($key->id, $userId));
            }
        }
        return $list;
    }
    public function getAverageGradeByUser($userId) {
        $arr = $this->getGradeTable($userId);
        $sum = 0;
        foreach ($arr as $key){
            $sum += $key['percentage'] / 10;
        }
        $number = count($arr);
        if($number > 0) {
            return $sum / $number;
        }
        else {
            return 0;
        }
    }

    public function checkIfIsGuest($attempt_id) {
        try {
            $model = $this->select('guest')
            ->from('attempts')
            ->where('id = :id')
            ->execute(array('id' => $attempt_id))
            ->fetch();
            if ($model->guest !== null) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteAttemptDetailByAttemptId($id) {
        try {
            $model = $this->delete()
            ->from('attemptdetails')
            ->where('attemptid = :attemptid')
            ->execute(array('attemptid' => $id));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteAttemptById($id) {
        try {
            $model = $this->delete()
            ->from('attempts')
            ->where('id = :id')
            ->execute(array('id' => $id));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}