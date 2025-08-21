# Beaconstat 2.0

Welcome to the next generation of the Beaconstat project.

This second version was rewritten from scratches, but keeps the same database structure, so you can safely upgrade your existing application.

The app is now built with CodeIgniter v4 which provides better security and easier developent.

> [!WARNING]
> This is still a work in progress and it is not considered stable yet, a lot of features are still missing

## Features

- [x] Multi-language support
- [ ] Supports user registration
- [ ] Every user can submit a new beacon
- [ ] Every user can submit a new report
- [ ] Better beacons map with both valid and invalid beacons
- [ ] Automatically calculated bearings for each beacon based on user's location
- [ ] New administration panel
      - [ ] Users management
      - [ ] Beacons management
      - [ ] Database backup/restore
      - [ ] Customizable notifications

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

Make sure to include:

```
app.baseURL = 'http://beaconstat.localhost/'
app.contactEmail = 'contact@beaconstat.com'
database.default.hostname = localhost
database.default.database = database-name
database.default.username = database-user
database.default.password = database-pass
```

## Development

1. Clone this repository
2. Set Apache to the right folder using a virtual host
3. Configure the `.env` file as in the [setup](#setup) chapter

### Apache `httpd.conf`

```conf
<Directory "C:\Users\Administrator\Documents\GitHub\beaconstat\public">
   Order allow,deny
   Allow from all
   # New directive needed in Apache 2.4.3: 
   Require all granted
   AllowOverride All
</Directory>
```

### Apache `https-vhosts.conf`

```conf
<VirtualHost *:80>
    ServerAdmin beaconstat.localhost
    DocumentRoot "C:\Users\Administrator\Documents\GitHub\beaconstat\public"
    ServerName beaconstat.localhost
    ErrorLog "logs/beaconstat-localhost-error.log"
    CustomLog "logs/beaconstat-localhost-access.log" common
</VirtualHost>
```

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
