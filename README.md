[![Dependency Status](https://www.versioneye.com/user/projects/5683de54eb4f47003c000b2a/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5683de54eb4f47003c000b2a) [![Build Status](https://scrutinizer-ci.com/g/AleksandrKuporosov/bbApiRequestConflicts/badges/build.png?b=master)](https://scrutinizer-ci.com/g/AleksandrKuporosov/bbApiRequestConflicts/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/AleksandrKuporosov/bbApiRequestConflicts/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AleksandrKuporosov/bbApiRequestConflicts/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/aleksandr-kuporosov/bb-api-request-conflicts/v/stable)](https://packagist.org/packages/aleksandr-kuporosov/bb-api-request-conflicts) [![Total Downloads](https://poser.pugx.org/aleksandr-kuporosov/bb-api-request-conflicts/downloads)](https://packagist.org/packages/aleksandr-kuporosov/bb-api-request-conflicts) [![Latest Unstable Version](https://poser.pugx.org/aleksandr-kuporosov/bb-api-request-conflicts/v/unstable)](https://packagist.org/packages/aleksandr-kuporosov/bb-api-request-conflicts) [![License](https://poser.pugx.org/aleksandr-kuporosov/bb-api-request-conflicts/license)](https://packagist.org/packages/aleksandr-kuporosov/bb-api-request-conflicts)

## Install
```
composer require aleksandr-kuporosov/bb-api-request-conflicts
```

## Usage
```
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
```
