# Symfony 7.3 Project

## Project Overview
This is a study project showcasing the features of Symfony 7.3.

## Requirements
- PHP 8.3 or higher
- Symfony CLI (recommended)
- Composer
- Database (MySQL)

## Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd <project-name>
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Configuration
Create a `.env.local` file and configure your environment variables:
```bash
nano .env.local
```

### 4. Database Setup
```bash
# Create the database
php bin/console doctrine:database:create

# Run migrations
php bin/console doctrine:migrations:migrate
```

### 5. Web Server Configuration
#### For Development
```bash
# Run the development server
symfony server:start
# or
php -S localhost:8000 -t public/
```

#### For Production
Configure your web server (Apache/Nginx) to point to the `public/` directory.

### 6. Asset Compilation (using AssetMapper)
```bash
# AssetMapper handles assets automatically
# No need to run npm commands
# Assets can be compiled and optimized thanks to the following command
php bin/console asset-map:compile
```

## Deployment

### Production Deployment Steps
1. **Prepare the environment:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. **Clear cache:**
   ```bash
   php bin/console cache:clear --env=prod
   ```

3. **Warm up the cache:**
   ```bash
   php bin/console cache:warmup --env=prod
   ```

4. **Set proper permissions:**
   ```bash
   chmod -R 775 var/
   chmod -R 775 public/
   ```

5. **Set up the web server:**
   - Point your web server document root to `public/`
   - Configure rewrite rules for Symfony

## Environment Variables
Create a `.env.local` file with the following variables:
```env
APP_ENV=prod
APP_SECRET=<your-secret>
DATABASE_URL=mysql://user:password@127.0.0.1:3306/database_name
```

## Development
### Useful Commands
```bash
# Clear cache
php bin/console cache:clear

# Generate entities from database
php bin/console doctrine:mapping:import --force App\\Entity

# Create migration
php bin/console doctrine:migrations:diff

# Run migrations
php bin/console doctrine:migrations:migrate
```

## Directory Structure
```
app/                 # Application configuration
assets/              # Frontend assets (managed by AssetMapper)
config/              # Configuration files
public/              # Web root
src/                 # Source code
templates/           # Twig templates
tests/               # Test files
var/                 # Cache and logs
vendor/              # Composer dependencies
```

## AssetMapper Information
This project uses Symfony's AssetMapper for asset management instead of Webpack Encore:
- Assets are automatically discovered and managed
- No need to run `npm install` or `npm run build`
- Assets are compiled and optimized during deployment
- Supports modern asset features like CSS imports, JavaScript modules, and more

## Security
- Keep your `APP_SECRET` secure
- Regularly update dependencies
- Follow Symfony security best practices

## Troubleshooting
### Common Issues
1. **Permission errors**: Ensure proper permissions on `var/` and `public/` directories
2. **Cache issues**: Clear cache with `php bin/console cache:clear`
3. **Database connection**: Verify `DATABASE_URL` in `.env.local`

## License
This project is licensed under the MIT License (by now;).