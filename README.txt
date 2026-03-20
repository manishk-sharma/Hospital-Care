Hospital Care PHP Website

Pages:
- index.php (Home)
- about.php
- service.php
- blog.php
- contact.php
- login.php
- register.php
- forgot-password.php

Shared layout:
- includes/header.php
- includes/footer.php

Styles:
- assets/css/style.css

Open index.php in a PHP server (XAMPP, WAMP, or PHP built-in server).

Database setup (MySQL):
1. Create database and tables by importing:
	- database/schema.sql
2. Update DB values if needed in:
	- config/database.php

Default DB connection values:
- Host: 127.0.0.1
- Port: 3306
- Database: hospital_care
- User: root
- Password: (empty)

Auth pages with DB integration:
- register.php -> stores user with hashed password
- login.php -> verifies hashed password and starts session
- forgot-password.php -> stores reset token hash in password_resets
