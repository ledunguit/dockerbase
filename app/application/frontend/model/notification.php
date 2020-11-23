<?php

namespace Application\Frontend\Model;

use Application\Frontend\Model\Users as UserModel;
use Application\Frontend\Model\Quiz as Quiz;
use \Venus\Model as Model;
use \Venus\Admin as Admin;
use \DateTime;
use \DateInterval;

class Notification extends Model {
    public $data = [];

    public function __construct($id = null) {
        parent::__construct();
        $this->id = (int)$id;
    }

    public function validate($data) {
        if ($data == null) {
            return false;
        }
        if (strlen($data['title']) == 0 || $data['title'] == null || $data['title'] == '') {
            return false;
        }
        if (strlen($data['description']) == 0 || $data['description'] == null || $data['description'] == '') {
            return false;
        }
        if (strlen($data['content']) == 0 || $data['content'] == null || $data['content'] == '') {
            return false;
        }
        return true;
    }

    public function addNoti($data) {
        try {
            $model = $this->insertInto('notification', 'title, description, content, createdby, timecreated, visible', ':title, :description, :content, :createdby, :timecreated, :visible')
            ->execute(array(
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'createdby' => Admin::getId(),
                'timecreated' => ((new \DateTime())->format('Y-m-d H:i:s')),
                'visible' => 1
            ));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateNoti($data) {
        try {
            $model = $this->update('notification')
            ->set('title = :title, description = :description, content = :content')
            ->where('id = :id')
            ->execute(array(
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'id' => $data['id']
            ));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteNotiById($id) {
        try {
            $model = $this->delete()
            ->from('notification')
            ->where('id = :id')
            ->execute(array('id' => $id));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function hideNotiById($id) {
        try {
            $model = $this->update('notification')
            ->set('visible = 0')
            ->where('id = :id')
            ->execute(array('id' => $id));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function showNotiById($id) {
        try {
            $model = $this->update('notification')
            ->set('visible = 1')
            ->where('id = :id')
            ->execute(array('id' => $id));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getListNotifications() {
        $list = [];
        $model = $this->select('id')
            ->from('notification')
            ->orderBy('id desc')
            ->execute()
            ->fetchAll();
        if ($model) {
            foreach ($model as $key) {
                $result = $this->getNotification($key->id);
                array_push($list, $result);
            }
            return $list;
        }
        return null;
    }

    public function getVisibleNotifications() {
        $list = [];
        $model = $this->select('id')
            ->from('notification')
            ->where('visible = 1')
            ->orderBy('id desc')
            ->execute()
            ->fetchAll();
        if ($model) {
            foreach ($model as $key) {
                $result = $this->getNotification($key->id);
                array_push($list, $result);
            }
            return $list;
        }
        return null;
    }


    public function getNotification($id) {
        $model = $this->select()
            ->from('notification')
            ->where('id = '. $id)
            ->execute()
            ->fetch();
        if ($model) {
                $result['id'] = $model->id;
                $result['title'] = $model->title;
                $result['description'] = $model->description;
                $result['content'] = $model->content;
                $result['createdBy'] = $model->createdby;
                $result['timecreated'] = $model->timecreated;
                $user = new Users();
                $result['author'] = $user->idToName($model->createdby);
                $result['visible'] = $model->visible == '1' ? 'Hiện' : "Ẩn";
            return $result;
        }
        return null;
    }
}