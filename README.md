# Budget control manager v.1.0.0
Quick and easy manager for control and predict yours budget.

### Entities:
Invoice - marked note for your control. Basically have required name, type and cost.
For additional it can take values - description, deadline and tags

Invoice types:
- Task - non costable note(cost = 0)
- Income - note with pos cost
- Expense - note with neg cost

Tags - conditionally categories for your invoices

### Features:
Attaching any created tags to your invoices

Summary - get value of your expensies by dates range

Export and Import all DB of you entities to JSON file.

## Used packages:

- PHP 8.3.2
- Laravel 10.43.0
- Vite 5.0.0
- Xdebug 3.3.1
- SwaggerUI L5 5.12.0
- MySQL 5.7.44

## How to deploy:

1. `php artisan migrate`
2. `php artisan db:seed`
3. `php artisan serve`

## Documentation:

- http://.../api/documentation