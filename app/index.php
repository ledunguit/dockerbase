<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");
require_once(dirname(__FILE__) . '/venus/autoload.php');
require_once (dirname(__FILE__) .'/publics/plugins/tfpdf/tfpdf.php');
require_once (dirname(__FILE__) .'/publics/plugins/phpmailer/src/PHPMailer.php');
require_once (dirname(__FILE__) .'/publics/plugins/phpmailer/src/Exception.php');
require_once (dirname(__FILE__) .'/publics/plugins/phpmailer/src/OAuth.php');
require_once (dirname(__FILE__) .'/publics/plugins/phpmailer/src/POP3.php');
require_once (dirname(__FILE__) .'/publics/plugins/phpmailer/src/SMTP.php');
\Venus\Session::start();
\Venus\Venus::app(require_once(dirname(__FILE__).'/config/application.php'))->run();