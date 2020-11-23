<?php
namespace Application\Admin\Model;
use \Venus\Model as Model;

class User extends Model {
    public function __construct() {
        parent::__construct('users');
    }

    public function getAllUsers() {
        $model = $this->select()
            ->from($this->table)
            ->where('id > 1')
            ->execute()
            ->fetchAll();
        return $model;
    }

    public function disabledUser($id) {
        try {
            $model = $this->update('users')
                ->set('privilege = 0')
                ->where('id = :id')
                ->execute(array('id' => $id));
                return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function enableUser($id) {
        try {
            $model = $this->update('users')
                ->set('privilege = 1')
                ->where('id = :id')
                ->execute(array('id' => $id));
                return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAllAttemptIdByUserId($id) {
        try {
            $model = $this->select('id')
            ->from('attempts')
            ->where('userid = :userid')
            ->execute(array('userid' => $id))
            ->fetchAll();
            return $model;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function deleteAttemptDetailById($id) {
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

    public function deleteUser($id) {
        try {
            $allAttemptIds = $this->getAllAttemptIdByUserId($id);
            foreach ($allAttemptIds as $attemptId) {
                $this->deleteAttemptDetailById($attemptId->id);
                $this->deleteAttemptById($attemptId->id);
            }
            $model = $this->delete()
                ->from('users')
                ->where('id = :id')
                ->execute(array('id' => $id));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}