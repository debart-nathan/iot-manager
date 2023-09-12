# IOT Manager

## Introduction

IOT Manager is a Symfony project that uses Docker for environment setup. The project includes scripts for setting up both development and production environments.

## Getting Started

### Prerequisites

Before you begin, ensure you have met the following requirements:

- You have installed the latest version of composer
- You have installed the latest version of npm
- You have installed the latest version of Docker and Docker Compose.
- You have a suitable database system installed and ready to use.

### Setup

1. Clone the repository to your local machine.

2. Create a copy of the `.env` file and name it `.env.local`. This file will contain your local environment variables.

3. In the `.env.local` file, set the database connection details. Make sure to create an empty database that corresponds to the data in the `.env.local` file.

4. Set the `.env` `APP_ENV` to dev or prod depending of your use case

4. You can modify the data fixtures as per your requirements.

5. Run the initial setup script for your environment:

   - For development environment, run: `php script/dev-initialSetup.php`
   - For production environment, run: `php script/prod-initialSetup.php`

These scripts will install Composer dependencies, Node.js dependencies, and Webpack Encore. They also set up environment variables, run database migrations, load data fixtures, and compile assets with webpack for development.

6. Add the pictures of your module types into the `target_pictures` directory.

## Contributing

If you want to contribute to this project, please fork the repository and use a feature branch. Pull requests are warmly welcome.