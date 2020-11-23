<?php

namespace Application\Frontend\Model;

use \Venus\Model as Model;
use \Venus\User as User;
use \Venus\Admin as Admin;
use Venus\User as UserLib;
use Application\Frontend\Model\Quesions as Question;

class Quiz extends Model {
    public $data = [];

    public function __construct($id = null) {
        parent::__construct();
        $this->id = (int)$id;
    }

    public function getInfo($id) {
        $this->id = (int)$id;
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

    public function getLastSeqOfQuiz($quizId) {
        $model = $this->select('seq')
            ->from('assign')
            ->where('quizid = :quizid')
            ->orderBy('seq DESC')
            ->execute(array('quizid' => $quizId))
            ->fetch();
        if ($model) {
            return $model->seq;
        } else {
            return null;
        }
    }

    public function getIdQuizByCode($code) {
        $model = $this->select()
            ->from('quiz')
            ->where('code = :code')
            ->execute(array('code' => $code))
            ->fetch();
        if ($model)
            return $model->id;
        return null;
    }

    public function idToName($id) {
        $model = $this->select('name')
            ->from('quiz')
            ->where('id = :id')
            ->execute(array('id' => $id))
            ->fetch();
        if ($model)
            return $model->name;
        return null;
    }

    public function getQuizzesByUser($userId) {
        $list = [];
        $model = $this->select('id')
            ->from('quiz')
            ->where('createdby = ' . $userId)
            ->execute()
            ->fetchAll();
        if ($model) {
            foreach ($model as $key) {
                array_push($list, $this->getInfo($key->id));
            }
        }
        return $list;
    }

    public function getQuizReportByUser($userId) {
        $arr = [];
        $arr['numOfQuizzes'] = $this->getNumberOfQuizzesCreatedByUser($userId);
        $arr['getTimesBeAttempted'] = $this->getTimesBeAttempted($userId);
        return $arr;
    }

    public function getTimesBeAttempted($userId) {
        $model = $this->select('count(*) num')
            ->from('quiz, attempts')
            ->where('quiz.id = attempts.quizid')
            ->and('createdby = ' . $userId)
            ->execute()
            ->fetch();
        return $model->num;
    }

    public function getNumberOfQuizzesCreatedByUser($userId) {
        $model = $this->select('count(id) num')
            ->from('quiz')
            ->where('createdby = ' . $userId)
            ->execute()
            ->fetch();
        return $model->num;
    }

    public function convertTime($time) {
        $hour = 0;
        $minute = 0;
        $second = $time;
        while ($second > 60) {
            $minute++;
            $second -= 60;
        }
        if ($second == 60) {
            $minute++;
            $second = 0;
        }
        while ($minute > 60) {
            $hour++;
            $minute -= 60;
        }
        if ($minute == 60) {
            $hour++;
            $minute = 0;
        }
        $str = '';
        if ($hour != 0)
            $str .= $hour . ' giờ ';
        if ($minute != 0)
            $str .= $minute . ' phút ';
        if ($second != 0)
            $str .= $second . ' giây';
        return $str . trim('');
    }

    public function getAllQuesIdByQuizId($quizId) {
        $model = $this->select('questionid')
            ->from('assign')
            ->where('quizid = :quizid')
            ->execute(array('quizid' => $quizId))
            ->fetchAll();
        return $model;
    }

    public function getTimeLimitByQuizId($quizId) {
        $model = $this->select('timelimit')
            ->from('quiz')
            ->where('id = :quizid')
            ->execute(array('quizid' => $quizId))
            ->fetch();
        return $model;
    }

    public function checkPasswordByQuizId($quizId, $password) {
        $model = $this->select('id')
            ->from('quiz')
            ->where('id = :quizid')
            ->and('password = :password')
            ->execute(array('quizid' => $quizId, 'password' => $password))
            ->fetch();
        return $model != null ? true : false;
    }

    public function getMySimpleList() {
        $list = [];
        $model = $this->select('id, name')
            ->from('quiz')
            ->where('createdby = ' . UserLib::getInfo()->id)
            ->execute()
            ->fetchAll();
        if ($model) {
            foreach ($model as $key) {
                if ($this->quizIsChangable($key->id)) {
                    array_push($list, $key);
                }
            }
        }
        return $list;
    }

    public function quizIsChangable($quizId) {
        $model = $this->select('count(id) number')
            ->from('attempts')
            ->where('quizid = ' . $quizId)
            ->execute()
            ->fetch();
        if ($model) {
            if ($model->number === '0') {
                return true;
            }
        }
        return false;
    }

    public function updateVisible($quizId, $visible) {
        $model = $this->update('quiz')
            ->set('visible = :visible, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'visible' => $visible,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateTimeLimit($quizId, $time) {
        $model = $this->update('quiz')
            ->set('timelimit = :timelimit, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'timelimit' => $time,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateTimeOpen($quizId, $timeOpen) {
        $model = $this->update('quiz')
            ->set('timeopen = :timeopen, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'timeopen' => $timeOpen,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId)
            );
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateTimeClose($quizId, $timeClose) {
        $model = $this->update('quiz')
            ->set('timeclose = :timeclose, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'timeclose' => $timeClose,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateTimeCloseAndOpenToNull($quizId) {
        $model = $this->update('quiz')
            ->set('timeopen = null, timeclose = null, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function getTimeCloseById($quizId) {
        $model = $this->select('timeclose')
            ->from('quiz')
            ->where('id = :id')
            ->execute(array('id' => $quizId))
            ->fetch();
        if ($model) {
            return $model->timeclose;
        }
        return null;
    }

    public function updateOverdueHandleMethod($quizId, $method) {
        $model = $this->update('quiz')
            ->set('overduehandling = :overduehandling, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'overduehandling' => $method,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateNumberOfAttempts($quizId, $number) {
        $model = $this->update('quiz')
            ->set('attempt = :attempt, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'attempt' => $number,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateGradeMethod($quizId, $method) {
        $model = $this->update('quiz')
            ->set('grademethod = :grademethod, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'grademethod' => $method,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateReviewSetting($quizId, $setting) {
        $model = $this->update('quiz')
            ->set('review = :review, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'review' => $setting,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateQuestionPerpage($quizId, $numberOfQues) {
        $model = $this->update('quiz')
            ->set('questionsperpage = :questionsperpage, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'questionsperpage' => $numberOfQues,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateSuffleAnswerSetting($quizId, $suffleanswer) {
        $model = $this->update('quiz')
            ->set('suffleanswer = :suffleanswer, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'suffleanswer' => $suffleanswer,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateSuffleQuestionSetting($quizId, $sufflequestion) {
        $model = $this->update('quiz')
            ->set('sufflequestion = :sufflequestion, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'sufflequestion' => $sufflequestion,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateQuizPassword($quizId, $password) {
        $model = $this->update('quiz')
            ->set('password = :password, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'password' => $password,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function removePasswordById($quizId) {
        $model = $this->update('quiz')
            ->set('password = :password, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'password' => null,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateDetailSetting($quizId, $setting) {
        $model = $this->update('quiz')
            ->set('showdetails = :showdetails, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'showdetails' => $setting,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateReceiveReviewSetting($quizId, $setting) {
        $model = $this->update('quiz')
            ->set('navmethod = :navmethod, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'navmethod' => $setting,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function updateAcceptGuestSetting($quizId, $setting) {
        $model = $this->update('quiz')
            ->set('acceptguest = :acceptguest, modifiedby = :modifiedby, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'acceptguest' => $setting,
                'modifiedby' => (User::getId() !== null ? User::getId() : (Admin::getId() !== null ? Admin::getId() : null)),
                'timemodified' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'id' => $quizId
            ));
        if ($model) {
            return true;
        }
        return false;
    }

    public function getGradeReport($quizId) {
        $model = $this->select('distinct(userid) id')
            ->from('attempts')
            ->where('quizid = ' . $quizId)
            ->execute()
            ->fetchAll();
        $attempt = new Attempts();
        $result = [];
        if ($model) {
            $grade = [];
            foreach ($model as $key) {
                array_push($grade, $attempt->computeAverageGrade($quizId, $key->id));
            }
            $result['highestGrade'] = max($grade);
            $result['lowestGrade'] = min($grade);
            $result['averageGrade'] = number_format(round(array_sum($grade) / count($grade), 2), 2);
        }
        return $result;
    }

    public function getRankList($quizId) {
        $model = $this->select('distinct(userid) userid')
            ->from('attempts')
            ->where('quizid = ' . $quizId)
            ->and('isnull(guest)')
            ->execute()
            ->fetchAll();
        $attempt = new Attempts();
        $result = [];
        $temp = [];
        $user = new Users();
        if($model) {
            foreach ($model as $key) {
                $temp[$key->userid] = number_format(round($attempt->computeAverageGrade($quizId, $key->userid), 2), 2);
            }
            arsort($temp);
            $i = 0;
            foreach ($temp as $key => $value) {
                $i++;
                if($i > 10) {
                    break;
                }
                array_push($result, array($key => array($user->idToName($key), $value)));
            }
            return $result;
        }
        return null;
    }
    public function checkDoTheQuiz($userId, $quizId){
        $model = $this->select('count(id) number')
            ->from('attempts')
            ->where('quizid = ' . $quizId)
            ->and('userid = ' . $userId)
            ->execute()
            ->fetch();
        if($model){
            if($model->number > 0){
                return true;
            }
        }
        return false;
    }

    public function getQuesIdBySeqAndQuizId($seq, $quizid) {
        $model = $this->select('questionid')
            ->from('assign')
            ->where('seq = :seq')
            ->and('quizid = :quizid')
            ->execute(array('seq' => $seq, 'quizid' => $quizid))
            ->fetch();
        if (isset($model->questionid)) {
            return $model->questionid;
        }
        return null;
    }

    public function deleteQuesByIdAndQuizId($ques_id, $quiz_id) {
        try {
            $model = $this->delete()
            ->from('assign')
            ->where('questionid = :questionid')
            ->and('quizid = :quizid')
            ->execute(array('questionid' => $ques_id, 'quizid' => $quiz_id));
            return true;
         } catch (\Exception $e) {
            return false;
         }
    }

    public function validate($data) {
        if (!isset($data)) {
            return false;
        }
        if (strlen($data['name']) > 255) {
            return 'Tên đề thi vượt quá 255 kí tự!';
        }
        if (strlen($data['name']) == 0) {
            return 'Tên đề thi trống!';
        }
        if (isset($data['timeopen']) && $data['timeopen'] !== null) {
            try {
                new \DateTime($data['timeopen']);
            } catch (\Exception $e) {
                return 'Thời gian mở đề không hợp lệ!';
            }
        }
        if (isset($data['timeclose']) && $data['timeclose'] !== null) {
            try {
                new \DateTime($data['timeclose']);
            } catch (\Exception $e) {
                return 'Thời gian đóng đề không hợp lệ!';
            }
        }
        if (isset($data['timelimit'])) {
            $time_data = explode(':', $data['timelimit']);
            if (count($time_data) !== 2) {
                return "Thời gian làm bài không đúng định dạng!";
            }
            if ($time_data[0] < 0 || $time_data[0] > 24) {
                return "Đạt giới hạn giờ làm bài! [0-2]";
            } else {
                if ($time_data[0] == 24) {
                    if ($time_data[1] > 0) {
                        return "Đạt giới hạn thời gian làm bài!";
                    }
                } else {
                    if ($time_data[1] < 0 || $time_data[1] > 59) {
                        return "Thời gian làm bài không hợp lệ!";
                    }
                }
            }
        }
        if (isset($data['overduehandling'])) {
            if (!($data['overduehandling'] == 1 || !$data['overduehandling'] == 2)) {
                return "Phương thức vượt quá thời gian không hợp lệ!";
            }
        }
        if ((isset($data['attempt']) && (!is_numeric($data['attempt'])) || (isset($data['attempt']) && (!($data['attempt'] <= 10 && $data['attempt'] >= 0))))) {
            return "Vui lòng chọn số lần thử hợp lệ [0-10]";
        }
        if (isset($data['grademethod']) && (!($data['grademethod'] <= 5 && $data['grademethod'] > 0))) {
            return "Vui lòng chọn phương thức tính điểm hợp lệ!";
        }
        if (isset($data['review']) && (!($data['review'] == 1 || $data['review'] == 0))) {
            return "Vui lòng chọn giá trị cho phép xem lại bài hợp lệ!";
        }
        if (isset($data['questionsperpage']) && (!($data['questionsperpage'] <= 50 && $data['questionsperpage'] > 0))) {
            return "Số câu hỏi mỗi trang không hợp lệ!";
        }
        return true;
    }

    public function addQuiz($data) {
        if ($this->validate($data) !== true) {
            return $this->validate($data);
        }
        try {
            if (isset($data['timelimit'])) {
                $timelimit = explode(':', $data['timelimit']);
                $totalTime = $timelimit[0] * 60 * 60 + $timelimit[1] * 60;
            } else {
                return "Time not found!";
            }
            $model = $this->insertInto(
                'quiz',
                'category, code, name, summary, timeopen, timeclose, timelimit, overduehandling, attempt, grademethod, review, questionsperpage, suffleanswer, sufflequestion, timecreated, createdby, timemodified, modifiedby, password, showdetails, navmethod, acceptguest, visible',
                ':category, :code, :name, :summary, :timeopen, :timeclose, :timelimit, :overduehandling, :attempt, :grademethod, :review, :questionsperpage, :suffleanswer, :sufflequestion, :timecreated, :createdby, :timemodified, :modifiedby, :password, :showdetails, :navmethod, :acceptguest, :visible'
            )
            ->execute(array(
                'category' => $data['category'],
                'code' => $data['code'],
                'name' => $data['name'],
                'summary' => $data['summary'],
                'timeopen' => $data['timeopen'],
                'timeclose' => $data['timeclose'],
                'timelimit' => $totalTime,
                'overduehandling' => $data['overduehandling'],
                'attempt' => $data['attempt'],
                'grademethod' => $data['grademethod'],
                'review' => $data['review'],
                'questionsperpage' => $data['questionsperpage'],
                'suffleanswer' => $data['suffleanswer'],
                'sufflequestion' => $data['sufflequestion'],
                'timecreated' => $data['timecreated'],
                'createdby' => $data['createdby'],
                'timemodified' => $data['timemodified'],
                'modifiedby' => $data['modifiedby'],
                'password' => $data['password'],
                'showdetails' => $data['showdetails'],
                'navmethod' => $data['navmethod'],
                'acceptguest' => $data['acceptguest'],
                'visible' => $data['visible']
            ));
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getAllAttemptByQuizId($quiz_id) {
        try {
            $model = $this->select('id')
                ->from('attempts')
                ->where('quizid = :quizid')
                ->execute(array('quizid' => $quiz_id))
                ->fetchAll();
            return $model;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteQuiz($quiz_id) {
        try {
            $model = $this->delete()
                ->from('assign')
                ->where('quizid = :quizid')
                ->execute(array('quizid' => $quiz_id));


            $attempt_ids_data = $this->getAllAttemptByQuizId($quiz_id);
            $attempt_ids = [];

            foreach ($attempt_ids_data as $attempt_id_data) {
                $attempt_ids[] = $attempt_id_data->id;
            }
            foreach ($attempt_ids as $attempt_id) {
                $model = $this->delete()
                    ->from('attemptdetails')
                    ->where('attemptid = :attemptid')
                    ->execute(array('attemptid' => $attempt_id));
            }
            $model = $this->delete()
                ->from('attempts')
                ->where('quizid = :quizid')
                ->execute(array('quizid' => $quiz_id));

            $model = $this->delete()
                ->from('quiz')
                ->where('id = :id')
                ->execute(array('id' => $quiz_id));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function addRquestPublicByQuizId($id) {
        try {
            $model = $this->select('id')
            ->from('requirement')
            ->where('quizid = :quizid')
            ->and('createdby = :createdby')
            ->execute(array(
                'quizid' => $id,
                'createdby' => \Venus\User::getId()
            ))
            ->fetch();
            if (!isset($model->id)) {
                $model = $this->insertInto('requirement', 'quizid, createdby, timecreated', ':quizid, :createdby, :timecreated')
                ->execute(array(
                    'quizid' => $id,
                    'createdby' => \Venus\User::getId(),
                    'timecreated' => ((new \DateTime())->format('Y-m-d H:i:s'))
                ));
                return true;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateQuizCate($quiz_id, $cate_id) {
        try {
            $model = $this->update('quiz')
            ->set('category = :category')
            ->where('id = :id')
            ->execute(array(
                'category' => $cate_id,
                'id' => $quiz_id
            ));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}