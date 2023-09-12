<?php
// Step 1: Install Composer dependencies
echo shell_exec('composer install');

// Step 2: Install Node.js dependencies
echo shell_exec('npm install');


// Step 3: Run database migrations
echo shell_exec('php bin/console doctrine:migrations:migrate');

// Step 4: Load data fixtures
echo shell_exec('php bin/console doctrine:fixtures:load');

// Step 5: Compile assets with webpack for development
echo shell_exec('npm run encore dev');
?>