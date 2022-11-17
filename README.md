## Make Commands
```bash
composer install
composer dump-autoload 
php artisan migrate --seed
php artisan optimize:clear
```

## swagger editor
```bash
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate 
```

##swagger-ui
```bash
/api/documentation 
``` 

## GIT

Every new task should start with new branch named type(feature, hotfix and etc)/task_id(or short desc)
E. g. :
*     1. git checkout develop
*     2. git pull origin develop
*     3. git checkout -b feature/description_of_feature

Right after you will finish with your task you need to:
*     1. Merge **current develop into your branch** 
        1.1 git checkout develop
        1.2 git pull origin develop
        1.3 git checkout feature/description_of_feature
        1.4 git merge develop
*     2. Resolve merge conflicts and make sure everything works and nothing gone to hell :)
*     3. Push your branch to remote repositoty.
        3.1 git push origin feature/description_of_feature
*     4. Go to the pull request section and create a new pull request
