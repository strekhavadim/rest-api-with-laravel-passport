## Деплой

- загрузить проект
- выполнить **composer update**
- скопировать **.env.example** в **.env** и внести информацию о базе данных
- выполнить **php artisan key:generate**
- запустить миграцию **php artisan migrate**
- создать запись о OAuth-клиенте **php artisan passport:client --password**

## Доступные методы

a. Register new user:

    [POST] /api/register
b. Authorize user (receive token):

    [POST] /api/login
    
c. Refresh token:

    [POST] /api/refresh
d. Logout user:

    [POST] /api/logout
e. Update current authorized user information:

    [PUT] /api/users/me
f. Update current user profile image:

    [PATCH] /api/users/me/profileimage
g. Get current user information:

    [GET] /api/users/me
h. Get All users:

    [GET] /api/users
i. Get specific user information (with likes):

    [GET] /api/user/{USER_ID}
j. Like User:

    [POST] /api/user/{USER_ID}/like
