##Introduction

Laravel localization is an component which easy will help you to detect your locale throught various detectors
**(see below)**. And first detector which will return an detector will be used as found locale. 

### Instalation
You can use the `composer` package manager to install. From console run:

```
  $ php composer.phar require parfumix/laravel-localization "v1.0"
```

or add to your composer.json file

    "parfumix/laravel-meta": "v1.0"

You have to publish package files using

```
  $ php artisan vendor:publish
```

### Configuration

To register package you have to follow standart procedure registering serviceProvider class .Open your configuration file located in **config/app.php** and search for array of providers:

```php
  'providers' => [
        // Add that line at the end of array ..
        'Localization\LocaleServiceProvider'
      ]  
```

##Basic usage
To edit your localization component you have to publish first your configuration file using below command

```
  $ php artisan vendor:publish
```

If you want to add ore remove some of locale you can easy open your configuration file and edit that section
```yaml
locales:
   en:
    title: Enlgish
    country: US
    active: true
   ro:
    title: Romanian
    country: RO
    active: false
   ru:
    title: Russian
    active: false
```

How does component try to detect locale. It goes throught all detectors declared in configuration file and if someone of class will return an valid locale it will be used as founded locale and set up to the application 

```yaml
detectors:
   - Localization\Detectors\Request
   - Localization\Detectors\Browser
   - Localization\Detectors\System
```

As you can see here list of the classes so you can easy change the order of them or add new detectors. See below how to add new detector classes.

###Extending
You can place you detector anywhere you want, but the must important rule is to implement *Localization\Detectable* interface which will force you to implement an function *detect()*

Example of detector:
```php
<?php

namespace Your/Namespace;

use Localization\Detectable;

class YourDetector implements Detectable {

    public function detect($request) {
        // return founded locale .
    }

}
```

and add that class to your configuration file

```yaml
# here will be described class detectors, they will be called in the same order
detectors:
   - Your\Namespace\YourDetector
   - Localization\Detectors\Browser
   - Localization\Detectors\System
```

As you can see your detector will be called first.

##Laravel integration
To use that package with laravel routing package you have to edit your configuration file and exclude detectors which you don't need it

```yaml

# That mean Localization will walk through all detectors in the same order from the top to bottom and first locale which will be detected will be set as active locale App::locale('active_locale')

detectors:
   request: Localization\Detectors\Request // will detect locale from first url segment site.com/ru - ru will 
   browser: Localization\Detectors\Browser // will detect locale from request headers * optional
   system: Localization\Detectors\System // will detect locale from system * optional
```

For segmentation you have to edit your **routes.php** file and follow that structure

```php

// Here we will try detect and set as prefix first url segment . if not founded will be set null and will try to detect from below detectors .
Route::group(['prefix' => Localization\get_detector_locale('request')], function() {
  
  Route::get('/', function() {
    return view('welcome.blade.php')
  })
  
});
```

###Helpers

By default you can use some of helpers which will help you easy to manipulate with locales. Let me describe that helpers .

```php
Localization\get_locales() // return all registered locales in config file

Localization\get_default_locale() // will return default locale

Localization\get_active_locale() // will return active detected locale

Localization\format_locale() // format your locale and return formatter instance
```
