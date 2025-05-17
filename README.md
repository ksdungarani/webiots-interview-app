<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# webiots-interview-app

1. Install the Shopify Package
bash
Copy
Edit
composer require kyon147/laravel-shopify
2. Publish the Package Configuration
bash
Copy
Edit
php artisan vendor:publish --tag=shopify-config
3. Define Shopify Environment Variables
Update your .env file with the necessary Shopify credentials and app configuration.

4. Setup Ngrok (for local development)
Start Ngrok on port 8000:

bash
Copy
Edit
ngrok http 8000
You will receive a secure https URL. For example:

cpp
Copy
Edit
https://2055-2405-201-200d-9200-5940-31d1-e3ca-6482.ngrok-free.app
Use this URL to configure your environment:

env
Copy
Edit
APP_URL=https://2055-2405-201-200d-9200-5940-31d1-e3ca-6482.ngrok-free.app
Also, update your Shopify app settings with this URL:

Allowed redirection URL:
https://2055-2405-201-200d-9200-5940-31d1-e3ca-6482.ngrok-free.app/authenticate

5. Insert Subscription Plans
Use a database seeder to insert your subscription plans.

Then, apply the billable middleware to the home route to enforce plan selection:

php
Copy
Edit
Route::get('/', [HomeController::class, 'index'])->middleware('billable');
6. Implement the Plan Selection Flow
After authentication (from /authenticate/token), users should be redirected to a Choose Plan route.
However, redirection to the plan selection view may not currently be working.

On the plan selection view:

Display available plans

On plan selection, send a GraphQL recurring billing mutation to Shopify to initiate billing

7. Render Products
Create a view to display all Shopify products using the GraphQL Products API, with support for pagination.

8. Add Product Functionality
Implement an "Add Product" tab with a form to input:

Title

Description

Image

Quantity

Variants (Size, Color, Price, Quantity)

On form submission:

Use AJAX to send the form data to the product/store route

Store the product via Shopify's GraphQL API

❗Note: Currently, the request fails due to CSRF token mismatch. Ensure the CSRF token is passed in your AJAX request headers to resolve this issue.
