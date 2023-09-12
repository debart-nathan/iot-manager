<?php
// Step 1: Install Composer dependencies
echo shell_exec('composer install --no-dev');

// Step 2: Install Node.js dependencies
echo shell_exec('npm install --omit=dev');

// Step 3: Install Webpack Encore
echo shell_exec('npm install @symfony/webpack-encore');

// Step 4: Set up the environment variables for production

// Step 5: Run database migrations
echo shell_exec('php bin/console doctrine:migrations:migrate --no-debug');

// Step 6: Load data fixtures
echo shell_exec('php bin/console doctrine:fixtures:load --no-debug');

// Step 7: Compile assets with webpack for production
echo shell_exec('yarn encore production');
