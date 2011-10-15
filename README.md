# Codeigniter template

## Details
* Current system version: *Codeigniter 2.0.3*

## What is it?
This is my base codeigniter template for quickly starting any new apps, assuming they will need to connect to a database and have users login.

## Features
* Custom MY Controller to restrict access levels throughout areas of the site.
* Custom MY Model to help with the basic CRUD database setup
* User registration and login methods (home::register & dashboard::login)
* [Template library](http://williamsconcepts.com/ci/codeigniter/libraries/template/) for helping with the view files.
* [Baseless css](https://github.com/peteyhawkins/baseless) for basic styles to aid with prototyping
* Bcrypt library, password hashing done the proper way.
* [Toast](http://jensroland.com/projects/toast/) unit testing library installed.
* jQuery and jQuery tools
* site config for a few custom config items
* .htaccess for apache mod_rewrite

## Setup instructions
You can set this up just like any other codeigniter install, the only things you will need to do are:
1. Create a database.
2. Put the details into `app/config/database.php` and remove my local ones ;)
3. Create a users table, syntax below.

```sql
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `display_name` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT '',
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
```