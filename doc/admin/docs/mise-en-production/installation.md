# Installation

!!! note
    Toutes les actions ci-dessous sont à réaliser avec un utilisateur ayant les accès sudo.
    Dans le cas d'un serveur OVH, il s'agit de l'utilisateur **debian**.

    Les commandes a executer avec l'utilisateur de l'application seront précisées. 

## Sous-domaine
Se rendre sur l'[administration Amen](https://controlpanel.amen.fr/) pour faire pointer le sous-domaine vers l'IP du serveur.
Rubrique Domaine et DNS > Configuration DNS > Ajouter une Zone A.
```
crm.{boutique}.cnsi-sd.fr
```

## PHP8.2
```bash
# Upgrade paquet existants
sudo apt update
sudo apt upgrade -y

# Installation des repository PHP
sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
sudo sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
sudo apt update
sudo apt upgrade

# Installation PHP8.2
sudo apt install -y php8.2 php8.2-mbstring php8.2-zip php8.2-gd php8.2-fpm php8.2-xml php8.2-mysql php8.2-curl php8.2-bcmath php8.2-soap php8.2-imap
```

## Utilisateur SSH
```bash
sudo adduser crm
```

| Question                    | Réponse    |
|-----------------------------|------------|
| New password                | Aléatoire  |
| Retype new password         | Aléatoire  |
| Full Name []                | Par défaut |
| Room Number []              | Par défaut |
| Work Phone []               | Par défaut |
| Home Phone []               | Par défaut |
| Other []                    | Par défaut |
| Is the information correct? | Y          |


```bash
sudo chmod 711 -R /home/crm
sudo usermod -g www-data crm
sudo groupdel crm
```

Ajouter des alias pour l’utilisateur :
```bash
sudo nano /home/crm/.bash_aliases
```
```bash
alias cdw='cd /var/www/html/crm/current'
alias ll='ls -lah'
```

Connecté en tant que **crm**, autoriser la connection en SSH par clé:
```bash
mkdir /home/crm/.ssh
nano /home/crm/.ssh/authorized_keys
```
Copier/coller la clé ssh public de l’utilisateur à autoriser l’accès. Sauvegarder et quitter.

Connecté en tant que **crm**, modifier le prompt :
```bash
nano /home/crm/.bashrc
```
```bash
# Custom variables
PHP="/bin/php8.2"
HTTPDIR="/var/www/html/crm/current"
ARTISAN="/var/www/html/crm/current/artisan"

# change CMD prompt (replace BOUTIQUE)
PS1="\[\e]0;\u@\h: \w\a\]${debian_chroot:+($debian_chroot)}\[\033[01;32m\]\u@\h \[\033[01;33m\]CRM BOUTIQUE\[\033[00m\]\[\033[00m\]:\[\033[01;34m\]\w\[\033[00m\]\$ "
```
Attention à bien remplacer BOUTIQUE dans PS1

## Configurer MySQL
```bash
sudo mariadb
```
```sql
CREATE DATABASE IF NOT EXISTS crm CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER crm IDENTIFIED BY "sqlpassword";
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES, EXECUTE, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, ALTER ROUTINE, EVENT, TRIGGER ON `crm`.* TO 'crm'@'%';
FLUSH PRIVILEGES;
exit
```
Enregistrer le mot de passe sqlpassword, il sera à renseigner plus tard dans le fichier d'environnement et sur Keeper.

## Configurer PHP FPM
```bash
sudo cp /etc/php/8.2/fpm/pool.d/www.conf /etc/php/8.2/fpm/pool.d/crm.conf
sudo nano /etc/php/8.2/fpm/pool.d/crm.conf
```

- Remplacer [www] par [crm]
- Remplacer listen = /run/php/php8.2-fpm.sock par /run/php/crm.sock

## Configurer Nginx
Créer le répertoire de l’application et paramétrer les règles d’accès

```bash
HTTPDIR="/var/www/html/crm/"
sudo mkdir -p $HTTPDIR

HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1)
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:crm:rwX $HTTPDIR
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:crm:rwX $HTTPDIR
```

Créer un Server Block
```bash
sudo nano /etc/nginx/sites-available/crm.{boutique}.cnsi-sd.fr.conf
```
```nginxconf
server {
    listen *:80;

    server_name crm.{boutique}.cnsi-sd.fr;
    root /var/www/html/crm/current/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    set $ssl off;

    #send_timeout 600;
    fastcgi_buffers 16 16k;
    fastcgi_buffer_size 32k;

    error_log /var/log/nginx/crm.{boutique}.cnsi-sd.fr/error.log;
    access_log /var/log/nginx/crm.{boutique}.cnsi-sd.fr/access.log;

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/crm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/crm.{boutique}.cnsi-sd.fr.conf /etc/nginx/sites-enabled/
sudo mkdir /var/log/nginx/crm.{boutique}.cnsi-sd.fr

sudo systemctl reload nginx
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

## Python PIP
Requis pour build la documentation
```bash
# Avec l'utiliasteur debian
sudo apt install python3-pip -y
# Avec l'utilisation crm
pip3 install mkdocs
```

## Configurer le déploiement
!!! warning
    Les commandes de cette section sont à executer avec l'utilisateur **crm**.

```bash
ssh-keygen -t rsa -b 2048 -C "crm-{boutique}-production"
```

Dans la [configuration Gitlab](https://git.cnsi-sd.net/RoyalPriceTeam/crm/-/settings/repository#js-deploy-keys-settings) du projet, autoriser la clé SSH générée à cloner le projet.

| Option               | Valeur                       |
|----------------------|------------------------------|
| Title                | TKG CRM PROD                 |
| Key                  | contenu de ~/.ssh/id_rsa.pub |
| Write access allowed | Non                          |

Désactiver la vérification de l’hôte
```bash
echo -e "Host *\n\tStrictHostKeyChecking no\n\n" > ~/.ssh/config
```

Copier la clé privée, qui sera utilisée pour se connecter à notre serveur en tant qu'utilisateur déployeur avec SSH (crm)

```bash
cat ~/.ssh/id_rsa.pub >> ~/.ssh/authorized_keys
```
Ajouter la clé SSH privé de l'utilisateur **crm** dans une [variable de déploiement Gitlab](https://git.cnsi-sd.net/RoyalPriceTeam/crm/-/settings/ci_cd) SSH_PRIVATE_KEY_{BOUTIQUE}

Lancé un nouveau déploiement sur GitLab > CI/CD > Pipelines > New. Le job devrait échouer, car les variables d’environnement non pas été mises sur le serveur.

Connecté en crm, créer le fichier d’environnement shared/.env
```bash
nano /var/www/crm.cnsi-sd.fr/shared/.env
```

Un nouveau déploiement peut être relancé manuellement sur GitLab > CI/CD > Pipelines > New, il doit finir avec succès.


## Activer le HTTPS

🚩 Une fois le fonctionnement en HTTP fonctionnel à tous les niveaux, effectuer les actions suivantes pour activer le HTTPS. Il est important de ne pas l’activer pendant les phases de tests car Let’s Encrypt a un quota sur la génération de certificats.

### Certificat HTTPS
https://certbot.eff.org/instructions
```bash
sudo apt install snapd -y
sudo snap install core; sudo snap refresh core
sudo snap install --classic certbot
sudo ln -s /snap/bin/certbot /usr/bin/certbot
sudo certbot --nginx
```
| Question                               | Réponse                 |
|----------------------------------------|-------------------------|
| Enter email address	                   | webmaster@royalprice.fr |
| Do you agree? (Terms of Service)       | Y                       |
| Share email with Let’s Encrypt project | N                       |

### Nginx

Dans les fichiers suivants, remplacer set $ssl off; par set $ssl on;
- /etc/nginx/sites-enabled/crm.{boutique}.cnsi-sd.fr.conf

Puis redémarrer Nginx
```bash
sudo systemctl reload nginx
sudo systemctl restart nginx
```

## Worker (traitement de la queue)
Créer le fichier de configuration systemd :
```bash
sudo nano /etc/systemd/system/crm@.service
```
```bash
[Unit]
Description=CRM queue worker

[Service]
User=crm
Group=www-data
Restart=always
ExecStart=php8.2 /var/www/html/crm/current/artisan queue:work
StandardOutput=null
RestartSec=10

[Install]
WantedBy=multi-user.target
```
Recharger la configuration systemd :
```bash
sudo systemctl daemon-reload
```

Activer un worker :
```bash
sudo systemctl enable crm@1
sudo systemctl start crm@1
```

Autoriser l’utilisateur crm à gérer son worker :
```bash
sudo nano /etc/sudoers.d/crm
```

```bash
crm ALL=NOPASSWD: /usr/bin/systemctl * crm@*.service
crm ALL=NOPASSWD: /usr/bin/journalctl -u crm@*.service
crm ALL=NOPASSWD: /usr/bin/journalctl -fu crm@*.service
```

## Backup

Mettre la clé SSH publique de debian sur le NAS dans .ssh/authorized_keys.

Puis créer le script de sauvegarde :

```bash
cd ~
nano backup_sql.sh
```

```bash
# Local export configuration
local_backup_directory="/home/debian/backup/sql/"
date=$(date +%F_%T.sql)
export_file="${local_backup_directory}crm_${date}"
keep_file_x_days=7

# Distant export configuration
rsync_user="royalprice"
rsync_host="cnsihinlais.synology.me"
rsync_directory="/volume1/BACKUP/{BOUTIQUE}/CRM/"

# Database configuration
db_host="localhost"
db_user="crm"
db_password="DB_PASSWORD"
db_name="crm"

echo "### Création du dossier de backup"
mkdir -p $local_backup_directory

echo "### Suppression des fichiers de plus de jours"
find $local_backup_directory -mtime +$keep_file_x_days -exec rm {} \;

echo "### Création du dump de la BDD"
mysqldump -h$db_host -u$db_user -p$db_password $db_name > $export_file

echo "### Compression du dump de la BDD"
gzip $export_file

echo "### Exportation du dump de la BDD"
rsync -vzr --delete --delete-before -e 'ssh -p 2222' $local_backup_directory $rsync_user@$rsync_host:"$rsync_directory"
```

Ajouter dans le crontab :
```bash
# Backup SQL
00 04,13 * * * /bin/bash /home/debian/backup_sql.sh
```
