# BASE_LARAVEL_PROJECT

## REQUIRED

- [Laravel 9](https://github.com/laravel/laravel/tree/9.x)
- PHP 8.1

## INCLUDED PACKAGE
- [spatie/laravel-fractal@^6.0](https://github.com/spatie/laravel-fractal)
- [spatie/laravel-query-builder@^5.2](https://github.com/spatie/laravel-query-builder)
- [tymon/jwt-auth@^2.0](https://github.com/tymondesigns/jwt-auth)
- [laravel/socialite@^5.6](https://github.com/laravel/socialite)

## USAGE

### Installation

- SSH into the Docker container with `make ssh` and run the following.
    - `composer create-project`
    - `php artisan key:generate`
    - `php artisan jwt:secret`
    - `php artisan migrate`
    - `php artisan make:migration table_name_changes`
- Exit from Docker container with `CTRL+C` or `exit`.
- Rename `docker-compose.local.yaml` to `docker-compose.override.yaml`
- Start the local development server with `make up`.
- Run tests with `make dev-test`.
- Run `make` to see available commands.

#### Create new user

- `make ssh`
- `php artisan ti`
- `App\Models\User::factory()->create(['email' => 'user@gmail.com', 'password' => '123456789', 'name' => 'Test'])`

### Configuration

- Edit `.env` file for environment variables.
- Edit the files in `config` directory for application configuration.

#### Send error to slack
- Edit `config/logging.php`
    ```php
    <?php

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'slack'],  //Add slack
            'ignore_exceptions' => false,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),  //Add slack webhook url https://api.slack.com/messaging/webhooks
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'error'),
        ],
    ]
    ```

#### JWT Authentication

- Create Bearer Token

```
curl --request POST 'http://localhost:8000/api/auth/login' \
    --header 'Content-Type: application/json' \
    --data-raw '{
        "email": "user@gmail.com",
        "password": "123456789"
    }'
```

Example Bearer Token -

```
eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE2ODg2NTExMDQsImV4cCI6MTY4ODY1NDcwNCwibmJmIjoxNjg4NjUxMTA0LCJqdGkiOiJRTzNHUjVqeTNJUE5HS0pCIiwic3ViIjoiMTUiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.HPxgfYdt75dqXBI4rVNbv1cc6CBlJzx0xUV8zNaPiIg
```

Bearer Token need to passed in the request header as 

```
Authorization: Bearer <token>
```

- Get Current User

```
curl --request GET 'http://127.0.0.1:8000/auth/me' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE2ODg2NTExMDQsImV4cCI6MTY4ODY1NDcwNCwibmJmIjoxNjg4NjUxMTA0LCJqdGkiOiJRTzNHUjVqeTNJUE5HS0pCIiwic3ViIjoiMTUiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.HPxgfYdt75dqXBI4rVNbv1cc6CBlJzx0xUV8zNaPiIg'
```

#### Social Authentication
- Edit `.env`
- Edit `config/services.php`
    ```php
    <?php

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID', ''),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', ''),
        'redirect' => env('FACEBOOK_REDIRECT', 'http://localhost:8000/api/auth/facebook/callback'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', ''),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', ''),
        'redirect' => env('GOOGLE_REDIRECT', 'http://localhost:8000/api/auth/google/callback'),
    ],
    ```

- Example
```
curl --location 'http://127.0.0.1:8000/api/auth/facebook'
```

## RUN SERVER COMMAND

- `php artisan serve`
