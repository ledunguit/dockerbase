<?php
namespace Venus;
use Application\Admin\Model\Setting as Setting;
class Helper {
    public static function convertQuesName(string $quesName) {
        $quesName = substr($quesName, 0, 200);
        $quesName .= '...';
        return $quesName;
    }

    public static function webInfo(){
        $setting = new Setting();
        return $setting->getInfo();
    }

}