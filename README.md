<h1>SETUP INSTRUCTION</h1>

1. Download docker from https://www.docker.com/get-started/
2. Install it, leave all checkboxes as they are during installation, when asked to sign in, choose continue without signing in or something like that
3. Open this repository in VS code or other code editor
4. In terminal type <code>docker compose up -d</code>. make sure you are in the root of repository
5. Go back to docker. In "Containers" section you will see container "zlagoda" with 3 sub-containers: php-1, myphpadmin-1 and database-1. If you did everything right they all should have status Running. You can also see that php-1 runs on port 8080:80, it's the site's port. And phpmyadmin-1 is on 9090:80, it's MyPhpAdmin (database)  
  ![image](https://github.com/RitaKr/zlagoda/assets/46822688/f66577d6-0542-464e-b417-7fed6310cdd2)

<h2>Where can I see the site?</h2>
If your php-1 container is running, open localhost:8080 in browser and you will se content of the site (it is index.php in content folder)

<h2>How can I access database admin panel? (MyPhpAdmin)</h2>
if your database-1 and myphpadmin-1 containers are running, open localhost:9090 in browser. You will be prompted login and password. You can find them in docker-compose.yml (MYSQL_USER, MYSQL_PASSWORD)

<h3><code>Please, note that Docker has to be launched and containers have to be running in order for links to work. But you can also stop the containers when you are not working with them, if you want to save some memory and CPU</code></h3>

<h3>Also note that we use gulp to bundle styles and scripts!</h3>
It means that we can create multiple styles and scripts and they all will bundle in one file. But you have to start gulp first:
1. In terminal type: <code>cd content</code> to move to content folder
2. Then type <code>npm install</code> to install all required dependencies, including gulp
3. When the installation is over, type <code>npm start</code> and gulp will start watching files in src/scss and src/js and update build files on every change

