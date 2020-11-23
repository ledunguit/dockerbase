<?php
namespace Venus;
class Admin extends Session {
    public static function setId($id) {
        self::set('Admin_id', $id);
    }

    public static function getId() {
        return self::get('Admin_id');
    }

    public static function setInfo($info) {
        self::set('Admin_info', $info);
    }

    public static function getInfo() {
        return self::get('Admin_info');
    }

    public static function logged() {
        if(self::get('Admin_id') !== null)
            return true;
        return false;
    }

    public static function logout() {
        self::delete('Admin_id');
        if(self::get('Admin_id') !== null)
            return false;
        return true;
    }
}