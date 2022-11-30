<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# PHP8.1 install
```bash
sudo apt install php8.1 php8.1-dom php8.1-curl php8.1-bcmath php8.1-xml php8.1-mysql php8.1-gd php8.1-fpm php8.1-soap
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
php8.1 artisan migrate
php8.1 artisan serve
```

# Git
Dans le fichier suivant `.git/config` remplacer `filemode = true` par `filemode = false`

# Mailhog
Outil qui fait un faux serveur SMTP en local

## Debian / Ubuntu Go < v1.18
```bash
sudo apt-get -y install golang-go
go get github.com/mailhog/MailHog
```

## Go >= v1.17 (Debian Bookworm)
```bash
sudo apt-get -y install golang-go
go install github.com/mailhog/MailHog@latest
```

Commande pour lancer le serveur SMTP : `~/go/bin/MailHog`.
Visualisation des mails sur http://127.0.0.1:8025/
