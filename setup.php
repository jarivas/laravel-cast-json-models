<?php

$composerFile = '/workspace/laravel/composer.json';

$composerData = json_decode(file_get_contents($composerFile), true);
$composerData['autoload-dev']['psr-4']['CastModels\\'] = '../src/';

file_put_contents($composerFile, json_encode($composerData, JSON_PRETTY_PRINT));