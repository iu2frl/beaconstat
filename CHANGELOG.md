# The Beaconstat Project
## Changelog

### 2022/11/30
- Added manual `CHANGELOG.md`
- Added changelog parser
- Improved `index.php` footer
- Set default query of `index.php` to `ORDER BY 'qrg' ASC`
- Added locator length check on `analyze_db.php`
- Backup is now automatically performed before any import

### 2022/11/29
- Added import from RSGB Beacons CSV
- Fixed bug in importing process which may lead to wrong beacon import (no band check were performed)
- Replaced SVG in `img` folder
- Added favicon

### 2022/11/28
- Handled exception when no files were present in uploads folder
- Fixed a bug which lead to skipping lines after a malformed beacon in `xls/process.php`
- Added Telegram statistics after import of CSV or XLS
- Fixed a bug in `mapfull.php` which lead to no beacons shown in case of a malformed locator

### 2022/09/30
- Created public repository on IU2FRL GitHub profile

### 2022/09/29
- Improving W3C validation on `index.php`
- Handling empty variables from DB
- Added custom title to any page
- Added automatic translations detection

### 2022/09/28
- Added feature to backup database to `.tar.gz` file
- Moved global settings to `config` folder
- Fixed `require_once` in `xls/process.php`
- Fixed some relative paths
- Testing W3C validation on `index.php` page

### 2022/09/25
- First public release from the original project by IU2FRL and IU3GNB