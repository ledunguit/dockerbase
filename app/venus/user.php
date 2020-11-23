<?php
namespace Venus;
class User extends Session {
    public static function setId($id) {
        self::set('User_id', $id);
    }

    public static function getId() {
        return self::get('User_id');
    }

    public static function setInfo($info) {
        self::set('User_info', $info);
    }

    public static function getInfo() {
        return self::get('User_info');
    }

    public static function logged() {
        if(self::get('User_id') !== null)
            return true;
        return false;
    }

    public static function logout() {
        self::delete('User_id');
        if(self::get('User_id') !== null)
            return false;
        return true;
    }
}