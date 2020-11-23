<?php
namespace Venus;
class Base {
    public static $module;
    public static $controller;
    public static $action;
    public static $param;
    public static $config;
    public static $baseUrl;
    public static $adminUrl;
    public static $resourceUrl;

    public function __construct($inputConfig) {
        Base::$config = $inputConfig;
        Base::$baseUrl = $this->getBaseUrl();
        Base::$adminUrl = $this->getAdminUrl();
        Base::$resourceUrl = Base::$baseUrl . '/' . Base::$config['resourceFolder'];
    }

    private function getBaseUrl() {
        $currentPath = $_SERVER['PHP_SELF'];
        $pathInfo = pathinfo($currentPath);
        $hostName = $_SERVER['HTTP_HOST'];
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
        return $protocol . $hostName . ($pathInfo['dirname'] != '/' && $pathInfo['dirname'] != '/quizlearning.com' ? $pathInfo['dirname'] : '');
    }

    private function getAdminUrl(){
        $currentPath = $_SERVER['PHP_SELF'];
        $pathInfo = pathinfo($currentPath);
        $hostName = $_SERVER['HTTP_HOST'];
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
        return $protocol . $hostName . ($pathInfo['dirname'] != '/' && $pathInfo['dirname'] != '/quizlearning.com' ? $pathInfo['dirname'] : '');

    }

    public function run() {
        $module = Base::$config['defaultModule'];
        $controller = Base::$config['defaultController'];
        $action = Base::$config['defaultAction'];
        $param = array();

        if (isset($_GET['url'])) {
            $url = explode('/', filter_var(trim($_GET['url'], '/')));
            if ($url[0] !== '' && is_dir('application' . Base::$config['ds'] . $url[0])) {
                $module = $url[0];
                array_shift($url);
            }
            if (isset($url[0])) {
                $filePath = 'application' . Base::$config['ds'] . strtolower($module) . Base::$config['ds'] . 'controller' . Base::$config['ds'] . $url[0] . '.php';
                if (file_exists($filePath)) {
                    $controller = $url[0];
                    array_shift($url);
                }
            }
            if (isset($url[0])) {
                $class = 'Application\\'.ucfirst($module).'\Controller\\'.ucfirst($controller);
                if (method_exists($class, $url[0])) {
                    $action = $url[0];
                    array_shift($url);
                }
            }
            if (isset($url[0])) {
                $param = $url;
            }
        }
        Base::$module = $module;
        Base::$controller = $controller;
        Base::$action = $action;
        Base::$param = $param;
        if($module != Base::$config['defaultModule']){
			Base::$baseUrl .= '/' . $module;
		}
        $class = 'Application\\'.ucfirst($module).'\Controller\\'.ucfirst($controller);
        $controller = new $class();
		if(method_exists($controller, 'init')){
			$controller->init();
        }
        $controller->$action($param);
    }

    public static function app($inputConfig){
        return new self($inputConfig);
    }
}