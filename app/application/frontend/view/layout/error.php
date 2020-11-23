<?php

use Venus\Venus;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <link rel="stylesheet" href="<?= \Venus\Venus::$baseUrl ?>/publics/error-page/css/style.css"/>
    <link rel="shortcut icon" type="image/ico" href="<?= Venus::$baseUrl ?>/publics/images/favicon.ico"/>
    <title>404 Not Found</title>
</head>
<body>
<?= $this->placeholder() ?>
<script src="<?= \Venus\Venus::$baseUrl ?>/publics/error-page/js/main.js"></script>
</body>
</html>