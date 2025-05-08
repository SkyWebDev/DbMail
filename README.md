## DbMail

### Overview

A Laravel package that allow your customers to edit email (blade) templates.

## Installation

### Requirements
The package has been developed and tested to work with the following minimum requirements:

- PHP 8.0
- Laravel 8.0

### Install the Package
You can install the package via Composer:

```bash
composer require skywebdev/laravel-db-view
```

### Migrate the Database
This package contains migration that add table: ``` blade_templates ```.
To run these migration, simply run the following command:
```bash
php artisan migrate
```

### Import existing blade templates
After migration you should run following seeder that will import email templates to database.

```bash
php artisan db:seed --class="SkyWebDev\Database\Seeders\AddBladeTemplatesSeeder"
```
You should run this seeder whenever new email template is added.

## Using

After installation your Mailable class will read body and subject from table ``` blade_templates ```. You can 
allow 
customers to change 
them on front using rich text and markdown editors.

### Routes

Package contains CRUD routes for maintaining ``` blade_templates ``` tables. Nothing special, I would just emphasize that 
delete is actually revert to original. Delete will revert content and subject to be same as defined in blade file.
Update and delete will clear view cache.

| Verb   | URI                             | Action  | Name                    |
|--------|---------------------------------|---------|-------------------------|
| GET    | /blade-templates                | index   | blade-templates.index   |
| POST   | /blade-templates                | create  | blade-templates.create  |
| GET    | /blade-templates/{bladeTemplate} | show    | blade-templates.show    |
| PUT    | /blade-templates/{bladeTemplate}                | update  | blade-templates.update  |
| DELETE | /blade-templates/{bladeTemplate}                | destroy | blade-templates.destroy |


