# Jukebox

This is a simple challenge app that simulates a Jukebox where users can select songs and add them to a playlist. The app
uses a database to store the songs in the playlist and provides a console interface to interact with the user.

## Let's dance

To run the code without worrying about local dependencies, you can use a Docker environment. Alternatively, you can
follow the installation steps below to run it on your local machine.

Create the image using the following command:

```bash
docker build -t jukebox . 
```

After the image is created, you can create a container and run the app using the following command:

```bash
docker run -it --rm jukebox 
```

## Development environment

### Docker environment

The project provides a php runtime environment using [Docker](https://www.docker.com/) and docker compose. To start the
docker environment,
just run the following command.

```bash
docker compose up -d
```

After the environment is up and running, you can access the container using the following command.

```bash
docker compose exec php sh
```

### Local environment

This step is for users without docker installed. To run the project locally, you need to follow the steps below.

- PHP 8.2 is required to run this project.
- Install the php dependencies using [Composer](https://getcomposer.org/doc/00-intro.md).

### Dependencies

The project use composer to manage the dependencies. Run the following command to install the dependencies.

```bash
composer install
```

### Migrations

The project utilizes SQLite as the database to eliminate the requirement for a database server. The migration scripts
can be found in the `/migrations` directory. To create the database and tables, execute the following command.

```bash
./bin/console doctrine:migrations:migrate
```

### Running the app

If tou arrive here, you have all the dependencies installed and the database created. Now you can run the app using the
next command.

```bash
./bin/console jukebox
```

## Project structure

The project structure is inspired in hexagonal architecture, which may seem like an overkill for this particular
project. However, I believe that it is a well-known structure that facilitates understanding of each part and its
purpose.

It's not the place to explain the hexagonal architecture in detail, but I will give a brief overview. The main idea is
to separate the application into layers, each one with its own responsibility:

- The Application layer is responsible for handling the uses cases.
- The Domain layer is responsible for the business rules and contains the Model, Repository, and Service.
- The Infrastructure layer is responsible for the implementation of the interfaces defined in the Domain layer.
- The Presentation layer is responsible for the user interface.

```
  |-- src
  |   |-- Application
  |   |   |-- UseCase
  |   |-- Domain
  |   |-- Infrastructure
  |   |-- Presentation
  |       |-- Console
 
```

## Code style

I prefer tidy code, and a tool like [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) is excellent for
maintaining clean and consistent code according to coding standards like PSR-1, PSR-2 ... Use the following command to
fix the code style.

```bash
composer fix
```

## Static analysis

Static analysis is a great tool to find bugs and improve the quality of the code. [PHPStan](https://phpstan.org/) is a
great solution for this. Use the following command to run the static analysis.

```bash
composer analyse
```

## Tests

The project provides a small set of unit and integration tests. The tests are written
using [PHPUnit](https://phpunit.de/).
Use the following command to run the tests.

```bash
composer test
```
