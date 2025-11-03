<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 📌 NXP HSL Labs – Provider Order Feature 

Design and build the foundation of a user-friendly dashboard for Licensed Providers to manage their supplement business.

**This module demonstrates:**
- Provider places wholesale product order  
- Order saved to DB  
- Inventory stock updated  
- Event fired → Email generated (logged instead of sent)  
- Clean architecture & test coverage

## Installation & Setup Commands

# Clone & enter project
git clone https://github.com/Anjana094/nxp-hsl-labs.git
cd nxp-hsl-labs

# Install dependencies
composer install

# Create .env file & generate key
cp .env.example .env
php artisan key:generate

# Configure database in .env, then:
php artisan migrate --seed

# Run server
php artisan serve

# Run tests
php artisan test

## Commands Used for Feature Development
# Models & Migrations
- php artisan make:model Provider -m
- php artisan make:model Inventory -m
- php artisan make:model Order -m

# Seeder & Factory
- php artisan make:seeder DatabaseSeeder
- php artisan make:factory ProviderFactory

# Controller + Service + Request
- php artisan make:controller Api/OrderController
- php artisan make:request OrderStoreRequest
- create /app/Services/OrderService.php   

# Policy (Authorization)
- php artisan make:policy OrderPolicy

Then register it in AuthServiceProvider.php.

# Event + Listener
- php artisan make:event OrderPlaced
- php artisan make:listener SendOrderPlacedEmail --event=OrderPlaced

# Mailable (HTML email view)
php artisan make:mail OrderConfirmationMail --markdown=emails.orders.confirmation

(We modified to use → ->view() instead of markdown() to avoid SMTP issues.)

# How Email Is Handled Without SMTP
- No SMTP / Mailtrap needed
- Listener does not send email
- Instead, it renders email HTML and logs it in: storage/logs/laravel.log
- To preview email HTML in API response → we added: 'email_preview' => (new OrderConfirmationMail($order))->render()

# Run the Feature (API Request)
- Endpoint:
POST /api/provider/orders

# Example Request JSON:
{
  "provider_id": 1,
  "quantity": 10
}

# cURL Example:
curl -X POST http://127.0.0.1:8000/api/provider/orders \
-H "Content-Type: application/json" \
-d '{"provider_id":1, "quantity":10}'

# Run tests:
php artisan test


## Useful Maintenance Commands
# Clear all caches
- php artisan cache:clear
- php artisan route:clear
- php artisan view:clear
- php artisan config:clear

# Rebuild event cache
- php artisan event:clear
- php artisan event:cache


## Project Structure (Relevant to Feature)
app/
 ├── Events/OrderPlaced.php
 ├── Listeners/SendOrderPlacedEmail.php
 ├── Mail/OrderConfirmationMail.php
 ├── Http/Controllers/Api/OrderController.php
 ├── Http/Requests/OrderStoreRequest.php
 ├── Services/OrderService.php
 ├── Policies/OrderPolicy.php
 ├── Models/{Provider, Order, Inventory}.php

routes/
 └── api.php

resources/views/emails/orders/confirmation.blade.php

database/
 ── migrations/
 ── seeders/DatabaseSeeder.php
 ── factories/ProviderFactory.php

tests/Feature/OrderFeatureTest.php


## Final Notes
- Entire flow works without SMTP
- Clean architecture (Controller → Service → Event → Mail)
- Fully testable using Postman & PHPUnit
