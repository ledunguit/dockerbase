<?php
namespace Application\Admin\Model;
use \Venus\Model as Model;
use \Application\Frontend\Model\Questions as Questions;
use \Application\Frontend\Model\Attempts as Attempts;

class Setting extends Model {
    public function getInfo() {
        $model = $this->select()
            ->from('setting')
            ->where('id = 1')
            ->execute()
            ->fetch();
        if($model){
            return $model;
        }
        return null;
    }

    public function updatePart1($webName, $webDes) {
        try {
            $model = $this->update('setting')
            ->set('websitename = :websitename, description = :description')
            ->where('id = 1')
            ->execute(array('websitename' => $webName, 'description' => $webDes));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updatePart2($homeName, $homeShort, $powerBy) {
        try {
            $model = $this->update('setting')
            ->set('homename = :homename, homeshort = :homeshort, poweredby = :poweredby')
            ->where('id = 1')
            ->execute(array('homename' => $homeName, 'homeshort' => $homeShort, 'poweredby' => $powerBy));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updatePart3($address, $phone, $email, $facebook, $instagram, $twitter) {
        try {
            $model = $this->update('setting')
            ->set('address = :address, phone = :phone, email = :email, facebook = :facebook, instagram = :instagram, twitter = :twitter')
            ->where('id = 1')
            ->execute(array('address' => $address, 'phone' => $phone, 'email' => $email, 'facebook' => $facebook, 'instagram' => $instagram, 'twitter' => $twitter));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateIntro($intro) {
        try {
            $model = $this->update('setting')
            ->set('introduction = :introduction')
            ->where('id = 1')
            ->execute(array('introduction' => htmlspecialchars($intro)));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function updateHelp($help) {
        try {
            $model = $this->update('setting')
            ->set('help = :help')
            ->where('id = 1')
            ->execute(array('help' => htmlspecialchars($help)));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}