# Add Yaml file support for Laravel 5 TranslationServiceProvider

This package uses Symfony/Yaml parser.

## Installing

Add ```"devitek/yaml-translation": "1.*"``` to your **composer.json** by running :

    php composer.phar require devitek/yaml-translation

And select version : ```1.*```

## Add support in Laravel

You have to replace

`'Illuminate\Translation\TranslationServiceProvider',`

with

`'Devitek\Core\Translation\TranslationServiceProvider',`

in **app/config/app.php**.

## How to use

Just use regular **php** files or use **yml** or **yaml** files instead.

**PHP** :

```php
<?php

return [
	'hello' => 'Hello :name',
    'author' => 'Devitek',
];
```

Will be equivalent to :

**YAML**

```yaml
hello: Hello :name
author: Devitek
```

Enjoy it ! Feel free to fork :) !
