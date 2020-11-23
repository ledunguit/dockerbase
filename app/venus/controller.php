<?php
namespace Venus;
use \Venus\View as View;
class Controller {
    public $view;

    public function __construct() {
        $this->view = new View;
    }
}