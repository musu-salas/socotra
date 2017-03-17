# Getting started
Running the project on the local machine requires to set up pre-packaged Vagrant box. It provides a wonderful development environment without necessity to install a web server, database and any other server software.

## Requirements
* [VirtualBox 5.x](https://www.virtualbox.org/wiki/Downloads) or [VMWare](https://www.vmware.com/);
* [Vagrant](https://www.vagrantup.com/downloads.html);
* Domain redirect in `/etc/hosts` file:  

```bash
192.168.10.10   socotra.local
```

## Setting up locally
* Install dependencies:

```bash
composer install
```

* Generate application key:

```bash
php artisan key:generate
```

* [Set environment configuration](#environment-configuration) in `.env` PHP dotenv file;

* Follow guidelines under [Daily usage section](#daily-usage) to create and boot Virtual Machine to start working on the project.

## Environment configuration
We're using `.env` PHP dotenv file for environment configuration.  
Here are some we wish to highlight, because these you shall receive/request from a project owner:

```dosini
# AWS key/secret, S3 bucket:
# (unlocks access to S3 bucket from your local development environment)
AWS_KEY=
AWS_SECRET=

# (this is where user generated static resources are stored)
S3_BUCKET=

# Full path to the cache dir required by AWS:
AWS_PHP_CACHE_DIR=/full/path/to/socotra/storage/app

# Facebook SDK app client/secret:
# (unlocks authentication via our Facebook app)
FACEBOOK_CLIENT=
FACEBOOK_SECRET=

# Google maps API key/secret:
# (to sign requests to Google Maps API)
GOOGLE_MAPS_KEY=
GOOGLE_MAPS_SECRET=
```

Feel free to poke project owner, if you haven't received these configurations or some doesn't work.

## Daily usage
* Pull latest source code from the remote repository via `git pull` command;
* Run `npm i` command to download latest dependencies of static assets;
* Run `vagrant up` command in project directory to create (if doesn't exist) and boot Virtual Machine;
* Open `http://socotra.local` in your favourite web browser;
* Please follow [our contribution guidelines](http://teamnik.org/simple-contribution-guidelines-for-programmers/#daily-routine). It explanes how to use branches and push your commits.

Now your project directory is in sync with the Homestead environment inside Vagrant VM. Any change applied to the source code is immediately available on `http://socotra.local` local website.  
_This synchronisation let's us work on the source code continuously without a necessity to restart the server._

> To just shut down Virtual Machine, run `vagrant halt` command in project directory. **Useful when temporary pausing your work** on the project with a plan to start over again soon (e.g. tomorrow);  
> To destroy Virtual Machine, use `vagrant destroy --force` command in project directory.


## Database migrations
> Migrations are like version control for your database, allowing your team to easily modify and share the application's database schema.  
> — [Laravel documentation](https://laravel.com/docs/5.4/migrations)

We manage database schemas and seeded test data using [Laravel Database Migrations](https://laravel.com/docs/5.4/migrations). All new migrations and database seeding are executed automatically every time Virtual Machine is created. Therefore, normally there's no need to connect to the VM via SSH and execute migrations manually.  
However, there are some use cases, migrations shall be run by hand:

* When your current VM was created before your received new migrations from a remote repository, other programmers pushed. Connect to the VM via SSH and execute migrations (see [Migrations basics](#migrations-basics)) to keep your database up to date. Or recreate (destroy and boot) your VM, so migrations are executed automatically;
* When you develop your own new migrations.

#### Migrations basics
Since our database is inside Vagrant box, migration commands shall be executed inside the box:

```bash
# Executing migrations to make VM database up to date:
vagrant ssh -- -t 'cd socotra && php artisan migrate'

# Rolling back recently executed migration(s):
vagrant ssh -- -t 'cd socotra && php artisan migrate:rollback'
```

Please refer to [Laravel documentation](https://laravel.com/docs/5.4/migrations) to learn how to develop your own database migrations.

## Connecting to VM via SSH
If there's a need to connect to the Vagrant box (Virtual Machine) via SSH command line interface, it's also possible:

```bash
vagrant ssh
```
This might be useful after you create an SQL migration file, which needs to be executed to populate database with modifications. See [Database migrations](#database-migrations) section.


## Connecting To Databases
The project is using Postgres database, which is created under Virtual Machine. It's possible to connect to the database via locally installed `psql` CLI or using any GUI database management tool (e.g. [PSequel](http://www.psequel.com/), [Navicat](https://www.navicat.com/products/navicat-for-postgresql)):

```bash
psql -U homestead -h 127.0.0.1 -p 54320 homestead
Password: secret
```
