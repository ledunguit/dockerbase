<?php
namespace Application\Admin\Model;
use \Venus\Model as Model;
use \Venus\Admin as AdminLib;

class Admin extends Model {
    public function __construct() {
        parent::__construct('users');
    }

    public function authenticate() {
        if (isset($this->adminEmail) && $this->adminPassword) {
            $model = $this->select()
                ->from('users')
                ->where('email = :email')
                ->execute(array('email' => $this->adminEmail))
                ->fetch();
            if ($model && $model->password == md5($this->adminPassword) && $model->privilege == 5) {
                AdminLib::setId($model->id);
                AdminLib::setInfo($model);
                return true;
            } else if ($model) {
                return "No Privilege";
            }
        }
        return false;
    }

    public function getInfo($email) {
        $model = $this->select()
            ->from('users')
            ->where('email = :email')
            ->execute(array('email' => $email))
            ->fetch();
        return $model;
    }
}