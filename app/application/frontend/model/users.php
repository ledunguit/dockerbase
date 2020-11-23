<?php
namespace Application\Frontend\Model;
use \Venus\Model as Model;
use \Venus\User as User;
use \Datetime;


class Users extends Model {
    public function __construct() {
        parent::__construct('users');
    }

    public function validate($data) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }
        if ($data['password'] == null || $data['password'] == '') {
            return "Invalid password";
        }
        if ($data['password'] !== $data['repassword']) {
            return "Password and RePassword are not the same";
        }
        if ($data['gender'] < 0 || $data['gender'] > 2) {
            return "Invalid gender";
        }
        try {
            $birthday = new \DateTime($data['birthday']);
        } catch (\Exception $e) {
            return "Invalid birthday";
        }
        return true;
    }

    public function addUser($userData) {
        if ($this->validate($userData) !== true) {
            return $this->validate($userData);
        }
        try {
            $model = $this->select('id')
            ->from('users')
            ->where('email = :email')
            ->execute(array('email' => $userData['email']))
            ->fetch();
            if (isset($model->id) && $model->id !== null) {
                return "Email này đã được sử dụng trên hệ thống. Vui lòng chọn email khác.";
            }
            $model = $this->insertInto(
                'users',
                'email,
                password,
                lastname,
                firstname,
                gender,
                birthday,
                phone,
                address,
                identification,
                description,
                organization,
                department,
                privilege,
                firstaccess,
                lastlogin',
                ':email,
                :password,
                :lastname,
                :firstname,
                :gender,
                :birthday,
                :phone,
                :address,
                :identification,
                :description,
                :organization,
                :department,
                :privilege,
                :firstaccess,
                :lastlogin'
                )
            ->execute(array(
                'email'=> $userData['email'],
                'password'=> MD5($userData['password']),
                'lastname'=> $userData['lastname'],
                'firstname'=> $userData['firstname'],
                'gender'=> $userData['gender'],
                'birthday'=> (isset($userData['birthday']) ? (new \DateTime($userData['birthday']))->format("Y-m-d H:i:s") : null),
                'phone'=> (isset($userData['phone']) ? $userData['phone'] : null),
                'address'=> (isset($userData['address']) ? $userData['address'] : null),
                'identification'=> (isset($userData['identification']) ? $userData['identification'] : null),
                'description'=> (isset($userData['description']) ? htmlspecialchars($userData['description']) : null),
                'organization'=> (isset($userData['organization']) ? $userData['organization'] : null),
                'department'=> (isset($userData['department']) ? $userData['department'] : null),
                'privilege'=> 1,
                'firstaccess'=> null,
                'lastlogin'=> null
            ));
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function checkLogin() {
        if (isset($this->email) && $this->password) {
            $model = $this->select()
                ->from('users')
                ->where('email = :email')
                ->execute(array('email' => $this->email))
                ->fetch();
            if ($model && $model->password == md5($this->password) && $model->privilege !== '0') {
                User::setId($model->id);
                User::setInfo($model);
                $this->setLastLogin();
                $this->checkFirstAccess();
                return true;
            }
            if ($model->privilege == '0') {
                return "lock";
            }
        }
        return false;
    }

    public function checkDuplicateEmail($email) {
        $model = $this->select()
            ->from('users')
            ->where('email = :email')
            ->execute(array('email' => $email))
            ->fetch();
        if ($model) {
            return "true";
        } else {
            return "false";
        }
    }

    public function getInfo($email) {
        $model = $this->select()
            ->from('users')
            ->where('email = :email')
            ->execute(array('email' => $email))
            ->fetch();
        return $model;
    }

    public function getInfoById($id) {
        $model = $this->select()
            ->from('users')
            ->where('id = '. (int) $id)
            ->execute()
            ->fetch();
        if($model){
            return $model;
        }
    }

    public function setLastLogin() {
        $currentTime = date('Y-m-d H:i:s', time());
        $model = $this->update('users')
            ->set('lastlogin = :currenttime')
            ->where('email = :email')
            ->execute(array('currenttime' => $currentTime, 'email' => User::getInfo()->email));
        if ($model) {
            return true;
        }
        return false;
    }

    public function checkFirstAccess() {
        try {
            $model = $this->select('firstaccess')
            ->from('users')
            ->where('email = :email')
            ->execute(array('email' => User::getInfo()->email))
            ->fetch();
            if ($model->firstaccess === null) {
                $currentTime = date('Y-m-d H:i:s', time());
                $model = $this->update('users')
                ->set('firstaccess = :firstaccess')
                ->where('email = :email')
                ->execute(array('firstaccess' => $currentTime, 'email' => User::getInfo()->email));
                return true;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function idToName($id) {
        $model = $this->select('lastname, firstname')
            ->from('users')
            ->where('id = :id')
            ->execute(array('id' => $id))
            ->fetch();
        if ($model) {
            return ($model->lastname . ' ' . $model->firstname);
        } else {
            return null;
        }
    }

    public function idToEmail($id) {
        $model = $this->select('email')
            ->from('users')
            ->where('id = :id')
            ->execute(array('id' => $id))
            ->fetch();
        if ($model) {
            return ($model->email);
        } else {
            return null;
        }
    }

    public function checkOldPass($userId, $oldPass) {
        $model = $this->select()
            ->from('users')
            ->where('id = :id')
            ->and('password = :oldPass')
            ->execute(array('id' => $userId, 'oldPass' => md5($oldPass)))
            ->fetch();
        if ($model) {
            return true;
        }
        return false;
    }

    public function changePassword($oldPass, $newPass) {
        $checkPass = $this->checkOldPass(User::getInfo()->id, $oldPass);
        if ($checkPass) {
            $newPass = md5($newPass);
            $model = $this->update('users')
                ->set('password = :password')
                ->where('id = :id')
                ->execute(array('password' => $newPass, 'id' => User::getInfo()->id));
            if ($model) {
                return "success";
            }
            return "notuser";
        } else {
            return "wrongpass";
        }
    }

    public function updateUser($id, $lastname, $firstname, $gender, $birthday, $phone, $address, $organization, $department, $description) {
        $model = $this->update('users')
                ->set('lastname = :lastname, firstname = :firstname, gender = :gender, birthday = :birthday, phone = :phone, address = :address, organization = :organization, department = :department, description = :description')
                ->where('id = :id')
                ->execute(array('lastname' => $lastname, 'firstname' => $firstname, 'gender' => $gender, 'birthday' => $birthday, 'phone' => $phone, 'address' => $address, 'organization' => $organization, 'department' => $department, 'description' => $description, 'id' => $id));
        if ($model) {
            return true;
        } else {
            return false;
        }
    }
}