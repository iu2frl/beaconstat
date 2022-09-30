# The Beaconstat project
## Credits
This project was originally developed by IU3GNB and [IU2FRL](https://github.com/iu2frl/) in 2019 (yes, the pandemic year)

## Contributors
- [IU2FRL](https://github.com/iu2frl/)
- IU3GNB

## Features list
### Native features
- [x] Show beacons list by frequency
- [x] Show beacons list by mainteners approval
- [x] Manual approval of beacons
- [x] Manual toggle of beacon status
- [x] Map of beacons list
- [x] Map for single beacon

### Incoming features
- [x] Telegram notifications adding beacon
- [x] Telegram notifications adding report
- [x] Telegram notifications changing beacon status
- [x] Telegram notifications changing beacon confirmation
- [x] Protected by reCAPTCHA V3
- [x] Database duplicates and empty rows check
- [x] Backups of beacons and reports
- [x] Supports multiples languages in index.php 
- [x] Supports importing XLS files from ARI website
- [ ] Supports multiples languages across website
- [ ] Telegram control via Bot API
- [ ] Automatic bad words filtering
- [ ] Importing CSV from [BeaconSpotUK](https://www.beaconspot.uk/home.php)
- [ ] Automatic send of received reports to cluster
- [ ] Automatic changelog generation

## Instructions
- Clone repo to your web server
- PHP Config
  - Tested PHP versions:
    - `7.4.29` 
    - `8.0`
  - No special modules required
- SQL Config
  - Make sure you have MySQL reachable by the WebServer 
  - Create a new Database (any name is fine, you can use same DB for other applications)
  - Create two tables called `bs_beacon` and `bs_report`
  - Create a new user with read and write access
- reCAPTCHA V3 Config
  - Open [this link](https://www.google.com/recaptcha/admin/create)
  - Insert a lable for this project (any name you like)
  - Select CAPTCHA V3
  - Add your domain(s)
  - Add users that will handle settings
  - Send request
  - Copy the `SECRET_KEY` provided by Google in the config file (see below)
  - Copy the `PUBLIC_KEY` provided by Google in the config file (see below)
- Telegram Config:
  - Creating a new Telegram Bot
    - Open Telegram APP
    - Create a new chat with [@BotFather](https://telegram.me/botfather)
    - Create a new Bot following the instructions
    - Copy the `API KEY` in the last step and paste it in the config file (see below)
  - Create a new group chat
    - Add [@myidbot](https://telegram.me/myidbot)
    - Add the Bot you created before
    - Send a message containing `/getgroupid`
    - Copy the Group ID in the config file (see below)
- Create a config file in `config/bs_config.php` as follows:
```php
<?php
// Do not put any 'require ...' here
// ---------------------------------------------------------------------
// --- Website configuration
// Main Database name
$masterDbName = 'DATABASE-NAME-HERE';
// Database host/domain
$masterDbHost = 'DATABASE-HOST-HERE';
// Database username
$masterDbUserName = 'DATABASE-USER-HERE';
// Database password
$masterDbPassword = 'DATABASE-PASSWORD-HERE';
// Telegram BOT token
$telegramBotToken = 'TELEGRAM-API-TOKEN-HERE';
// Telegram chat ID to send notifications
$telegramChatId = 'TELEGRAM-CHAT-HERE';
// Google reCaptchaV3 API KEY
$captchaSecretKey = 'CAPTCHA-SECRET-HERE';
$captchaPublicKey = 'CAPTCHA-PUBLIC-KEY';
// ---------------------------------------------------------------------
// --- Advanced configuration, do not touch if you're not sure
// Enable debug mode
$debug = false;
// Enable mobile devices appearence
$enableMobile = true;
// Change the main title of the website
$masterSiteName = "Beaconstat";
```
- Create APIs to interact with Telegram commands
  - Create file `api/keys.php` as follows
  - Populate the different keys using long and complicated strings
```php
<?php
return array (
  "TelegramKey1",
  "TelegramKey2",
  "..."
);
```
- Upload the result to your preferred host

## External contributors
- SimpleXLSX php class v0.6.8 by [Sergey Schuchkin](http://www.sibvision.ru)
- DB_Backup by [daniloaz](https://github.com/daniloaz/myphp-backup)
- reCAPTCHA v3 by [Google](https://developers.google.com/recaptcha/docs/v3)

## License
This project has been released under the [GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007](./LICENSE)