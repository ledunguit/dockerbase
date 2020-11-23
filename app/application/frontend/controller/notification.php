<?php

namespace Application\Frontend\Controller;

use \Venus\User as UserLib;
use \Venus\Venus as VenusLib;
use Application\Frontend\Model\Notification as Notifi;
use \Venus\Request as RequestLib;
use \Venus\Session as SessionLib;

class Notification extends Base {
    public function index($params = null) {
        $notifi = new Notifi();
        $this->view->list = $notifi->getListNotifications();
        if($params != null){
            $this->view->item = (new Notifi())->getNotification($params[0]);
            $this->view->setTitle('Thông báo: ' . $this->view->item['title']);
            $this->view->render('details');
        }
        else {
            $this->view->list = (new Notifi())->getVisibleNotifications();
            $this->view->setTitle('Thông báo từ Ban quản trị');
            $this->view->render('index');
        }
    }
}