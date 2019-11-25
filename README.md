# Laravel mGovSMS Intergation package

[![Latest Stable Version](https://poser.pugx.org/bexvibi/laravel-logger/v/stable)](https://packagist.org/packages/uithread/mgov-sms)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

#### READY FOR USE!
- [About](#about)
- [Features](#features)
- [Requirements](#requirements)
- [Laravel Installation Instructions](#laravel-installation-instructions)
- [Configuration](#configuration)
    - [Configuration File](#configuration-file)
- [File Tree](#file-tree)
- [License](#license)

### About
Laravel mGovSMS Intergation package is for your Laravel or Lumen application. It has default sms log tables. Laravel mGovSMS is easily configurable and customizable. Supports Laravel 5.5, 5.6, 5.7, 5.8, and 6.0+

### Features

| Laravel mGovSMS Features  |
| :------------ |
|Send Single SMS|
|Send OTP SMS|
|Send Bulk SMS|
|Send Unicode Single SMS|
|Send Unicode OTP SMS|
|Send Unicode Bulk SMS|
|Log SMS in database|
|Easy Configuration|

### Requirements
* [Laravel 5.5, 5.6, 5.7, 5.8, and 6.0+](https://laravel.com/docs/installation)

### Laravel Installation Instructions
1. From your projects root folder in terminal run:

```bash
    composer require uithread/mgov-sms
```

2. Register the package

* Laravel 5.5 and up
Uses package auto discovery feature, no need to edit the `config/app.php` file.


4. Publish the packages config file and migration files by running the following from your projects root folder:

```bash
    php artisan vendor:publish --provider="Uithread\MgovSMS\MgovSMSServiceProvider"  
```

3. Run the migration to add the table to sms log:

```php
    php artisan migrate
```

### Configuration
Laravel mGovSMS is configured in directly in `/config/mgov-sms.php`.


##### Configuration File
Here are the `mgov-sms.php` file variables available:

```dotenv
    'userName' => 'USER NAME',//username of the department
    'senderId' => 'SENDER ID',//password of the department
    'password' => 'PASSWORD',//senderid of the deparment
    'secureKey' => 'Generated Secure Key',// Secure key generated at https://services.mgov.gov.in/
```

### File Tree

```bash
├── LICENSE
├── README.md
├── composer.json
└── src
    ├── MgovSMS.php
    ├── MgovSMSServiceProvider.php
    ├── config
    │   └── mgov-sms.php
    ├── Facades
    │   └── MgovSMSFacade.php
    ├── migrations
    │   └── 2019_08_20_083308_create_t_sms_logs_table.php
    └── Models
        └── TSmsLog.php
```

### License
Laravel-logger is licensed under the MIT license. Enjoy!

