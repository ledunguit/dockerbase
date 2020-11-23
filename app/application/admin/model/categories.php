<?php

namespace Application\Admin\Model;

use Application\Frontend\Model\Quiz;
use Application\Frontend\Model\Categories as UserCategories;
use \Venus\Model as Model;
use \Venus\User as User;

class Categories extends Model {
    public $data = [];

    public function __construct($id = null) {
        parent::__construct();
        $this->id = (int)$id;
    }

    function vn_to_str($str) {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $str = str_replace(' ', '', $str);
        $str = trim($str, '-');
        return strtolower($str);
    }

    function checkShortName($string) {
        $exp = '/^([a-z0-9-]*)\w/';
        return preg_match($exp, $string);
    }

    public function validate($data) {
        if (!isset($data)) {
            return false;
        }
        if (!isset($data['name'])) {
            return false;
        }
        if (!isset($data['shortname'])) {
            return false;
        }
        $data['shortname'] = $this->vn_to_str($data['shortname']);
        if (!$this->checkShortName($data['shortname'])) {
            return "Tên rút gọn không hợp lệ.";
        }
        if (strlen($data['name']) == 0 || $data['name'] == null) {
            return "Tên danh mục không được để trống.";
        }
        if (strlen($data['shortname']) == 0 || $data['shortname'] == null) {
            return "Tên rút gọn của danh mục không được để trống.";
        } else if (strlen($data['shortname']) > 20) {
            return "Tên rút gọn của danh mục không được vượt quá 20 kí tự.";
        }
        return true;
    }

    public function addCate($data) {
        try {
            $model = $this->insertInto(
                'categories',
                'name, shortname, description, visible, timemodified',
                ':name, :shortname, :description, :visible, :timemodified'
            )->execute(array(
                'name' => $data['name'],
                'shortname' => $this->vn_to_str($data['shortname']),
                'description' => isset($data['description']) ? $data['description'] : null,
                'visible' => isset($data['hide']) ? ($data['hide'] == 'on' ? 0 : 1) : 1,
                'timemodified' => null
            ));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateCate($data) {
        try {
            $currentTime = date('Y-m-d H:i:s', time());
            $model = $this->update('categories')
            ->set('name = :name, shortname = :shortname, description = :description, visible = :visible, timemodified = :timemodified')
            ->where('id = :id')
            ->execute(array(
                'name' => $data['name'],
                'shortname' => $this->vn_to_str($data['shortname']),
                'description' => isset($data['description']) ? htmlspecialchars($data['description']) : null,
                'visible' => isset($data['hide']) ? ($data['hide'] == 'on' ? 0 : 1) : 1,
                'timemodified' => $currentTime,
                'id' => $data['id']
            ));
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
            ->execute()
            ->fetchAll();
        if ($model) {
            foreach ($model as $key){
                array_push($list, $this->getInfo($key->id));
            }
        }
        return $list;
    }
    public function getQuizzesByCategory($category, $mode) {
        $list = array();
        if($mode == 'shortname'){
            $id = (new UserCategories())->shortNameToId($category);
        }
        else {
            $id = $category;
        }
        $model = $this->select('id')
            ->from('quiz')
            ->where('category = :category')
            ->execute(array('category' => $id))
            ->fetchAll();
        foreach ($model as $key) {
            $quiz = new Quiz();
            array_push($list, $quiz->getInfo($key->id));
        }
        return $list;
    }
}