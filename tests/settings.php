<?php
$settings = require __DIR__.'/../src/settings.php';
$settings['settings']['db']['dsn'] = 'mysql:host=127.0.0.1;dbname=sufel_dev';
$settings['settings']['jwt']['secret'] = 'jsAkl34Oa2Tyu';

return $settings;
