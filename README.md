# Recipe App Backend

## About the project
This project is a school assignment in the form of a recipe app to learn how to work with API:s and Angular, which is used in the frontend part for this app. This is the backend part of the application. The API used for this project is the Spoonacular API, and in this backend part also a RESTful API is created to handle users and their recipe lists holding recipes. The users and their lists are saved in a MySQL database.

To read more about the functionality in the frontend part go to [https://github.com/louisehedman/recipeappfrontend](https://github.com/louisehedman/recipeappfrontend)

## How to get started
Since this is a Laravel project you need to have Docker installed on your machine and also Docker extension in your code editor before you begin. I have been using Visual Studio Code as code editor.

With that in place open your project in your code editor and run docker-compose up in terminal. With Docker up and running and if you use VS code you click on Docker symbol. Then find the running container for your project, right click on the line ending with _php and choose attach shell. You can attach several shells, and you will need more than one to serve on one and be able to make migrations and other things on another.

Then click on one of the running shells and run cd recipe-app-be/, which is the Laravel project name for this app. Then run php artisan serve --host 0.0.0.0 --port 8000 to start your local server. Then you can open your browser and type localhost:8000 to see the application home page.

To log in to the database you can use Adminer. You need to log in with the login details in the .env file (DB_CONNECTION, DB_HOST, DB_USERNAME, DB_PASSWORD). When you are logged in you need to create a database named for example RecipeApp, make sure you have the same database name in .env file. Then go back to your code editor and run php artisan migrate to get all the application tables into your database.

## Structure
The controllers used for this app are AuthController with functions for JWT based authentication of users, RecipeController and RecipeListController for CRUD on users RecipeLists and their recipes. 

The migrations are based on Recipe, RecipeList and User models and are connected with a pivot table in database. 

In routes folder you find api.php with all routes and API endpoints related to users and authentication, recipe lists and recipes. They are protected with auth middleware. 

## Deployed application
The backend part of the application is deployed on Heroku and live here: [https://randomrecipeappu06.herokuapp.com/](https://randomrecipeappu06.herokuapp.com/)

The frontend part of the application is deployed on Netlify and live here: [https://randomrecipeapp.netlify.app](https://randomrecipeapp.netlify.app)