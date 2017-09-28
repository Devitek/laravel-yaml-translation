# Add Yaml file support for Laravel 5 TranslationServiceProvider

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/02b5c920-d03b-40f9-961a-cb00f79d2e77/mini.png)](https://insight.sensiolabs.com/projects/02b5c920-d03b-40f9-961a-cb00f79d2e77) [![Latest Stable Version](https://poser.pugx.org/devitek/yaml-translation/v/stable)](https://packagist.org/packages/devitek/yaml-translation) [![Total Downloads](https://poser.pugx.org/devitek/yaml-translation/downloads)](https://packagist.org/packages/devitek/yaml-translation) [![Latest Unstable Version](https://poser.pugx.org/devitek/yaml-translation/v/unstable)](https://packagist.org/packages/devitek/yaml-translation) [![License](https://poser.pugx.org/devitek/yaml-translation/license)](https://packagist.org/packages/devitek/yaml-translation)

This package uses Symfony/Yaml parser.

## Installing

Add ```"devitek/yaml-translation": "4.*"``` to your **composer.json** by running :

    composer require devitek/yaml-translation

And select version: ```4.*```

Finally, publish all vendor assets to create a `yaml-translation.php`: 

`php artisan vendor:publish`


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
