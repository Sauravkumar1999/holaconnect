# Database Seeding Commands

## Run all seeders (includes AdminUserSeeder)
```bash
php artisan db:seed
```

## Run only the AdminUserSeeder
```bash
php artisan db:seed --class=AdminUserSeeder
```

## Fresh migration with seeding
```bash
php artisan migrate:fresh --seed
```

## Admin User Credentials
After seeding, you can login with:
- **Email:** admin@holaconnect.com
- **Password:** password123
- **User Type:** 0 (Admin)

## Note
The seeder checks if the admin user already exists before creating it, so you can run it multiple times safely.
