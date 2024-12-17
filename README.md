<p>Download the project (or clone using GIT)</p>
<p>Copy .env.example into .env and configure your database credentials</p>
<p>Go to the project's root directory using terminal window/command prompt</p>
<p>Run composer install</p>
<p>Set the application key by running php artisan key:generate --ansi</p>
<p>Run migrations php artisan migrate</p>
<p>Start local server by executing php artisan serve</p>
<p>Visit here http://127.0.0.1:8000/app to test the application</p>
<p>php artisan shield:generate --all</p>
<p>php artisan shield:super-admin</p>

list :
- [x] 1. Create a new Laravel project
- [x] 2. Create a new database
- [x] 3. Configure the database connection in the .env file
- [x] 4. Create a new migration for the user table
- [x] 5. Create a new model for the user table
- [x] 6. Create a homepage
- [x] 7. install filament
- [x] 8. install filament panel 'admin' dan 'app'
- [x] 9. install filament language dan env dan timezone 'id'
- [x] 10. filament panel app dan admin - login
- [x] 11. Setting Tenant pada dashboard admin
- [x] 12. create tenant pada dashboard app
- [x] 13. install filament shield
- [x] 14. install filament themes
- [x] 15. panel app resource user
- [x] 16. panel app resource role
- [x] 17. panel app resource permission
- [x] 18. Resource bahan dan alat
- [x] 19. Resource produk
- [x] 20. Resource pelanggan
- [x] 21. Resource transaksi
- [x] 22. Resource POS
- [x] 23. Dashboard app
- [x] 24. Dashboard admin
- [x] 25. Relasi Vendor
- [] 26. perbaikian pos dan databasenya

Berikut perbedaan komponen-komponen Laravel dalam bahasa Indonesia:

## Laravel Components

### Job

-   Background/queue tasks
-   Suitable for heavy processes like sending emails, generating PDFs
-   Can run asynchronously and delayed

### Service

-   Classes that handle business logic
-   Separates logic from controllers
-   Reusable across different parts
-   Focuses on single business feature/domain

### Trait

-   Collection of reusable methods
-   More flexible than inheritance
-   Can be added to various classes
-   Examples: HasFactory, Notifiable

### Scope

-   Custom query builder for models
-   Simplifies commonly used data filters
-   Examples: whereActive(), latest()

### Observer

-   Handles model lifecycle events
-   Automatically called on create/update/delete
-   Suitable for triggers/hooks

### Request

-   Validates user input
-   Separates validation logic from controllers
-   Supports custom rules and messages

### Event

-   Notifications when something occurs
-   Can trigger multiple listeners
-   Enables loose coupling between components

### Rule

-   Custom validation rules
-   Extends Laravel's built-in validation
-   Reusable across different forms

### Middleware

-   Filters/validates requests before reaching controller
-   Examples: auth, throttle, cors

### Provider

-   Service container registration
-   Application bootstrap
-   Global configuration

### Factory

-   Generates dummy data for testing
-   Simplifies database seeding

### Channel

-   Manages realtime broadcasting
-   Websocket and private channels

### Resource

-   Transforms model data to JSON/API
-   Consistent response formatting

### Policy

-   Manages authorization/permissions
-   Validates user access rights

### Interface

-   Contract for implementation
-   Dependency injection

### Repository

-   Database access abstraction
-   Separates queries from models

### Action

-   Single action classes
-   Reusable business logic
-   Lighter than services

### Helper

-   Global functions
-   Utility functions

### Macro

-   Extends Laravel's built-in functions
-   Custom methods for facades

### Collection

-   Array/data manipulation
-   Method chaining

### Presenter

-   Data display formatting
-   View logic

### Performance Optimizations

-   Implement caching strategies (Redis/Memcached)
-   Use eager loading to prevent N+1 queries
-   Implement query caching for frequently accessed data
-   Use database indexing strategically
-   Implement rate limiting for APIs

### Code Organization

-   Follow PSR standards
-   Use value objects for complex data types
-   Implement DTOs (Data Transfer Objects)
-   Create form requests for complex validations
-   Use enums for static options/states

### Development Practices

-   Write comprehensive tests (Unit, Feature, Browser)
-   Use static analysis tools (PHPStan, Larastan)
-   Implement CI/CD pipelines
-   Use code formatting tools (PHP CS Fixer)
-   Document APIs using OpenAPI/Swagger

### Security Enhancements

-   Implement API authentication (Sanctum/Passport)
-   Use signed URLs for sensitive routes
-   Implement rate limiting
-   Add security headers
-   Regular dependency updates

### Monitoring & Logging

-   Use Laravel Telescope for debugging
-   Implement proper error handling and logging
-   Use monitoring tools (New Relic, Scout APM)
-   Set up error reporting (Sentry, Bugsnag)

### Frontend Optimization

-   Use Laravel Mix/Vite for asset compilation
-   Implement lazy loading
-   Use proper caching headers
-   Optimize images and assets
