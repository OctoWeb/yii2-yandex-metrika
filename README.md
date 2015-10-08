Yii2 Yandex Metrika module
==========================
Module to rule your yandex metrika counters.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist octo-web/yii2-yandex-metrika "*"
```

or add

```
"octo-web/yii2-yandex-metrika": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply activate module in config file:

```php
<?php
    ......
   'modules' => [
        'yandex-metrika' => [
            'class' => 'octo-web\YandexMetrika\Module'
        ],
    ],
    ......

?>
```

Run migration. Module will create own table for migration history tbl_ym_migrations
```php
yii migrate/up --migrationPath=@vendor/octo-web/yii2-yandex-metrika/migrations --migrationTable=tbl_ym_migrations
```
.