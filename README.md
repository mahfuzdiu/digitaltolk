## Project and Task Listing Assignment

## Run Using Docker

1. Git pull project and go inside digitaltolk directory
```angular2html
git pull git@github.com:mahfuzdiu/digitaltolk.git
```

2. Run below docker command. Wait a bit then visit ```http://localhost:8000/``` to check if the project is running
```angular2html
docker compose up -d
```

3. Run following command from terminal to run test
```angular2html
docker compose exec translation-service php artisan test
```

4. Postman collection [link](https://automatedpro-4694.postman.co/workspace/AutomatedPro-Workspace~9b69f21a-6ee8-4421-8799-4ad3e782085e/collection/10198154-ca6222f8-acea-46b0-a46d-2bf46728912b?action=share&creator=10198154&active-environment=10198154-5d9604f3-296a-4278-af61-85ad8a7e65d1) 

5. ```Swagger``` documentation is available in ```doc``` folder
