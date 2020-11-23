<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->title ?></title>
    <script type="text/javascript" src="<?= \Venus\Venus::$baseUrl ?>/publics/js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="<?= \Venus\Venus::$baseUrl ?>/publics/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?= \Venus\Venus::$baseUrl ?>/publics/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= \Venus\Venus::$baseUrl ?>/publics/fontawesome/js/all.min.js"></script>
    <script type="text/javascript" src="<?= \Venus\Venus::$baseUrl ?>/publics/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="<?= \Venus\Venus::$baseUrl ?>/publics/datetime/jquery.datetimepicker.js"></script>
    <link rel="stylesheet" href="<?= \Venus\Venus::$baseUrl ?>/publics/css/jquery-ui.min.css">
    <link rel="stylesheet" href="<?= \Venus\Venus::$baseUrl ?>/publics/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= \Venus\Venus::$baseUrl ?>/publics/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= \Venus\Venus::$baseUrl ?>/publics/css/style.css">
    <link rel="stylesheet" href="<?= \Venus\Venus::$baseUrl ?>/publics/datetime/jquery.datetimepicker.css">
    <link rel="shortcut icon" type="image/ico" href="<?= \Venus\Venus::$baseUrl ?>/publics/images/favicon.ico"/>
</head>
<body>
<?= $this->placeholder() ?>
<script src="<?= \Venus\Venus::$baseUrl ?>/publics/js/app.js"></script>
</body>
</html>