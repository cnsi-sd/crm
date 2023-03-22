<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# PHP8.1 install
```bash
sudo apt install php8.1 php8.1-dom php8.1-curl php8.1-bcmath php8.1-xml php8.1-mysql php8.1-gd php8.1-fpm php8.1-soap php8.1-raphf php8.1-imap
```

# Node.js v18.x install
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash - &&\
sudo apt-get install -y nodejs
```

# Clone project
```bash
cd /var/www/html/
git clone ssh://git@git.cnsi-sd.net:2222/RoyalPriceTeam/crm.git
```

# Database setup
```bash
sudo mysql
```
```mysql
create database `crm` character set UTF8mb4 collate utf8mb4_general_ci;
exit;
```

# Environment setup
```bash
cp .env.example .env
nano .env
```
- Set your DB_USERNAME
- Set your DB_PASSWORD

# Project setup
```bash
php8.1 composer.phar install
php8.1 artisan key:generate
npm install
npm run dev
php8.1 artisan migrate --seed
php8.1 artisan db:safe
php8.1 artisan serve
```

# Safe database
La commande `db:safe` va modifier/censurer les différentes informations sensibles de la base de données.
Elle doit être utilisée après chaque import d'une base de données de production.

# Git
Dans le fichier suivant `.git/config` remplacer `filemode = true` par `filemode = false`

# Mailhog
Outil qui fait un faux serveur SMTP en local. [Installation et configuration](https://docs.google.com/document/d/1ldrS1BUNCsOweyQBWgi59p-xYSW8J0Gz10Wjs-5hknM/edit)

# Documentation MkDocs
La documentation est écrite en Markdown et est compilée avec [MkDocs](https://www.mkdocs.org/). 
## Installation
```bash
sudo apt update
sudo apt install python3 python3-pip -y
sudo pip install mkdocs
```
## Serveur de développement
```bash
cd doc/user-doc
mkdocs serve
```

