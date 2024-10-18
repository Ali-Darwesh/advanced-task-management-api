# Advanced Task Management System

I create this project to make the management of my projects easiest.
I can create projects and add users to it with differente roles :

-   Manager to make tasks for the project
-   Developer to do tasks

## Pastman

-   postman/endPointTask6.postman_collection.json

## create the project

-   composer create-project --prefer-dist laravel/laravel advanced-task-management-system "10.\*"
-   update env file to init database conniction
-   php artisan migration --seed
    admin accout will be created and roles and parmissions

## RelationShips

I use:

-   morphMany
