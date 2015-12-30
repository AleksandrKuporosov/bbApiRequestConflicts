<?php

require_once 'vendor/autoload.php';

use bbApiRequestConflicts\Conflicts;

$conflicts = new Conflicts([
    'login' => 'login',
    'password' => 'password',
    'owner' => 'owner',
    'slug' => 'production',
    'state' => 'OPEN',
]);

$links = $conflicts->getLinks();

print_r($links);