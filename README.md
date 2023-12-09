<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://th.bing.com/th/id/R.a6023e9f1aef01dbe8489dc6f2acbe9f?rik=33FX%2byx9mXim7w&pid=ImgRaw&r=0" width="400" alt="A lofi room"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## To do list API

The Task List API is a RESTful web service designed to manage your daily tasks efficiently.

- [Organize your life](https://checkify.com/blog/what-is-a-todo-list/).


## Instalation
This project uses environment variables for configuration. Copy the `.env.example` file to a new file called `.env` and fill in the necessary environment variables.
```bash
cp .env.example .env
```

After cloning the repository and configuring the `.env` file, you will need to install Composer dependencies and generate an application key: 
```bash
composer install
php artisan key:generate
```

Then you can run the database migrations:
```bash
php artisan migrate
```

Finally, you can start the development server:
```bash
php artisan serve
```

## Routes 
- Documentation - WIP

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
