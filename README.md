# CalmMind Consultation

A mental health consultation platform built with PHP and MySQL for ICT726 Assignment 4.

## Features

- **User Authentication**: Register, login, logout with role-based access (admin/client)
- **Consultation Management**: Clients submit requests, admins approve/reject
- **Contact Form**: Public contact form saves inquiries to database
- **Secure Password Storage**: Bcrypt hashing with PHP's `password_hash()`
- **Form Validation**: Both client-side (JavaScript) and server-side (PHP)
- **Responsive Design**: Mobile-first CSS with breakpoints at 960px, 768px, 480px
- **Accessibility**: Semantic HTML, ARIA attributes, skip links, keyboard navigation
- **SEO Optimized**: Meta descriptions, proper heading hierarchy

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher (or MariaDB 10.3+)
- Web server (Apache, Nginx, or PHP built-in server)

## Project Structure

```
CalmMind-Consultations-Static-Website-/
├── index.html              # Homepage
├── about.html              # About page
├── services.html           # Services page
├── resources.html          # Resources with image gallery
├── contact.php             # Contact form (saves to database)
├── login.php               # User login
├── register.php            # User registration
├── logout.php              # Session logout
├── css/
│   └── style.css           # Main stylesheet
├── js/
│   └── script.js           # Client-side validation & interactions
├── images/                 # Site images
├── includes/
│   ├── config.php          # Database configuration
│   ├── db.php              # PDO database connection
│   └── auth.php            # Authentication helpers
├── admin/
│   ├── dashboard.php       # Admin consultation management
│   └── update-status.php   # Update consultation status
├── client/
│   ├── dashboard.php       # Client consultation history
│   └── submit.php          # Submit new consultation
└── database/
    └── schema.sql          # Database schema
```

## Local Development Setup

### 1. Clone the Repository

```bash
git clone <repository-url>
cd CalmMind-Consultations-Static-Website-
```

### 2. Create the Database

**Option A: Using MySQL root with sudo (Ubuntu/Debian)**

```bash
sudo mysql < database/schema.sql
```

**Option B: Using MySQL with password**

```bash
mysql -u root -p < database/schema.sql
```

**Option C: Manual setup**

```bash
mysql -u root -p
```

Then run inside MySQL:

```sql
SOURCE /path/to/database/schema.sql;
```

### 3. Create a Database User (Recommended)

```sql
CREATE USER 'calmmind'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON calmmind_db.* TO 'calmmind'@'localhost';
FLUSH PRIVILEGES;
```

### 4. Configure Database Connection

Edit `includes/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'calmmind_db');
define('DB_USER', 'calmmind');        // Your database user
define('DB_PASS', 'your_password');   // Your database password
```

### 5. Start the Development Server

```bash
php -S localhost:8000
```

### 6. Access the Application

- Homepage: http://localhost:8000
- Login: http://localhost:8000/login.php
- Register: http://localhost:8000/register.php

### Default Admin Credentials

- **Email**: `admin@calmmind.example`
- **Password**: `password`

## Deployment to Web Hosting

### Shared Hosting (cPanel, Plesk, etc.)

#### 1. Upload Files

Upload all project files to your `public_html` directory (or subdirectory) via FTP or File Manager.

#### 2. Create Database

1. Go to **MySQL Databases** in cPanel
2. Create a new database (e.g., `username_calmmind`)
3. Create a new user with a strong password
4. Add user to database with **All Privileges**

#### 3. Import Schema

1. Go to **phpMyAdmin**
2. Select your database
3. Click **Import** tab
4. Upload `database/schema.sql`
5. Click **Go**

#### 4. Update Configuration

Edit `includes/config.php` with your hosting credentials:

```php
define('DB_HOST', 'localhost');           // Usually localhost
define('DB_NAME', 'username_calmmind');   // Your database name
define('DB_USER', 'username_dbuser');     // Your database user
define('DB_PASS', 'your_secure_password'); // Your database password
```

#### 5. Set File Permissions

```
includes/config.php: 644 (or 640)
All PHP files: 644
Directories: 755
```

### VPS / Cloud Server (DigitalOcean, AWS, etc.)

#### 1. Install LAMP/LEMP Stack

**Ubuntu/Debian:**

```bash
sudo apt update
sudo apt install apache2 mysql-server php php-mysql libapache2-mod-php
```

**Or with Nginx:**

```bash
sudo apt install nginx mysql-server php-fpm php-mysql
```

#### 2. Configure Virtual Host

**Apache (`/etc/apache2/sites-available/calmmind.conf`):**

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/calmmind

    <Directory /var/www/calmmind>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/calmmind_error.log
    CustomLog ${APACHE_LOG_DIR}/calmmind_access.log combined
</VirtualHost>
```

Enable the site:

```bash
sudo a2ensite calmmind.conf
sudo systemctl reload apache2
```

**Nginx (`/etc/nginx/sites-available/calmmind`):**

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/calmmind;
    index index.php index.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/calmmind /etc/nginx/sites-enabled/
sudo systemctl reload nginx
```

#### 3. Set Up Database

```bash
sudo mysql
```

```sql
CREATE DATABASE calmmind_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'calmmind'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON calmmind_db.* TO 'calmmind'@'localhost';
FLUSH PRIVILEGES;
SOURCE /var/www/calmmind/database/schema.sql;
EXIT;
```

#### 4. Set Ownership and Permissions

```bash
sudo chown -R www-data:www-data /var/www/calmmind
sudo find /var/www/calmmind -type d -exec chmod 755 {} \;
sudo find /var/www/calmmind -type f -exec chmod 644 {} \;
```

#### 5. Enable HTTPS (Recommended)

```bash
sudo apt install certbot python3-certbot-apache  # For Apache
# OR
sudo apt install certbot python3-certbot-nginx   # For Nginx

sudo certbot --apache -d yourdomain.com
# OR
sudo certbot --nginx -d yourdomain.com
```

## Security Considerations

### For Production Deployment

1. **Change default admin password** immediately after deployment

2. **Use HTTPS** - Install SSL certificate (Let's Encrypt is free)

3. **Secure config file** - Move `includes/config.php` outside web root or restrict access:

   ```apache
   # .htaccess
   <Files "config.php">
       Require all denied
   </Files>
   ```

4. **Update PHP settings** in `php.ini`:

   ```ini
   display_errors = Off
   log_errors = On
   session.cookie_httponly = 1
   session.cookie_secure = 1
   ```

5. **Set strong database passwords** - Never use default or weak passwords

6. **Regular backups** - Back up both files and database regularly

## Troubleshooting

### "Access denied" MySQL error

- Verify credentials in `includes/config.php`
- Ensure database user has proper privileges
- On Ubuntu, try `sudo mysql` instead of `mysql -u root -p`

### PHP Parse error with "match" expression

The code requires PHP 7.4+. If you see errors about `match`, your PHP version may be too old. Check with:

```bash
php -v
```

### 500 Internal Server Error

1. Check PHP error logs:
   ```bash
   sudo tail -f /var/log/apache2/error.log
   # OR
   sudo tail -f /var/log/nginx/error.log
   ```

2. Verify file permissions (644 for files, 755 for directories)

3. Ensure PHP extensions are installed:
   ```bash
   sudo apt install php-mysql php-pdo
   ```

### Sessions not working

- Ensure `session_start()` is called (handled in `includes/auth.php`)
- Check that the session directory is writable
- Verify PHP session settings in `php.ini`

## License

This project was created for educational purposes as part of ICT726 Assignment 4.

## Contributors

- Yadav Sarkar
- Tasnia Tehsin

