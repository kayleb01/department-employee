## Department-Employee API
# 👋 Hello 
This is a Department/Employee API

## Project Setup

```sh
composer install
```
## ENV
Create a .env file from the .env.example

## Migrate

run ```sh
php artisan migrate
```
JWT Token was used to handle authentication on here so run
```sh
php artisan jwt:secret
```
to generate the key

## Test Coverage
Well, 😣 I did my best to write test for almost all the endpoints but when I ran the test coverage it gave me 86.6%😤
![Test coverage](https://github.com/kayleb01/department-employee/blob/main/public/coverage.png).

## API Documentation
Find the API documentation [here](https://documenter.getpostman.com/view/13614601/2s9YkjA3dX)