# file-storage

An S3 based file storage with a focus on video files. A job template for AWS MediaConvert is included in this project.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### TLDR;

```
git clone https://github.com/amyisme13/file-storage.git
cd file-storage
composer install
cp .env.example .env
php artisan key:generate
php artisan mediaconvert:generate-endpoint
php artisan migrate
yarn
yarn dev
```

### Prerequisites

Please check the official laravel deployment guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/deployment)

### Installing

Clone the repository.

```
git clone https://github.com/amyisme13/file-storage.git
```

Switch to the repository folder.

```
cd file-storage
```

Install all the dependencies using composer.

```
composer install
```

Copy the example env file and make the required configuration changes in the .env file.

```
cp .env.example .env
```

Generate a new application key.

```
php artisan key:generate
```

Generate AWS MediaConvert endpoint for your accoount

```
php artisan mediaconvert:generate-endpoint
```

Run the database migrations (**Make sure you have set the database connection in .env before migrating**).

```
php artisan migrate
```

Install js packages

```
yarn
```

Build assets files

```
yarn dev
```

## Deployment

Some notes for deployment ([from laravel's website](https://laravel.com/docs/8.x/deployment)):

Instead of `composer install` you might want to run this command. This command will optimize autoloader.

```
composer install --optimize-autoloader --no-dev
```

Optimize configuration loading.

```
php artisan config:cache
```

Optimize route loading.

```
php artisan route:cache
```

Build production optimized assets

```
yarn prod
```

## Built With

- [Laravel](https://laravel.com) - The back-end framework used
- [Vue](https://vuejs.org/) - The front-end framework used
- And many awesome packages made by the open source community

## Authors

- **Azmi Makarima Y** - [amyisme13](https://github.com/amyisme13)
