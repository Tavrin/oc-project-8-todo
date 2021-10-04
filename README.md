ToDoList
========

Project 8 of OpenClassroom's Symfony developer cursus, improve an existing project.

## Requirements
- PHP 7.4.3 or higher
- Composer
- Symfony 4.4

## Installation
Check that the Symfony requirements are met
```
 composer require symfony/requirements-checker
```

Clone the repository and enter it
```
git clone https://github.com/Tavrin/oc-project-8-todo.git
cd ./oc-project-8-todo
```

Create an .env.local file into the root folder and add the environment type, dev to use the fixtures
```
APP_ENV=dev
KERNEL_CLASS='App\Kernel'
```

Configure the .env.local file to set a database url with the environment variable name being DATABASE_URL, preferably in MySQL
```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
```

Use the Makefile to automate the project installation
```
make install
```