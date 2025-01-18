# Modern Blog System

## Overview
A complete blog management system built with core PHP and MySQL. Features a modern responsive design, user authentication, admin dashboard, and full CRUD functionality.

## Features
- User Authentication (Login/Register)
- Role-based Access Control (Admin/Author/User)
- Post Management with Rich Text Editor
- Category & Tag Management
- Comment System
- Responsive Design
- Search Functionality
- Image Upload
- Admin Dashboard
- SEO Friendly URLs

## Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache Web Server
- XAMPP (optional)
- Laragon (recommedned)

## Installation
1. a. Clone the repository to your laragon www folder:
```bash
cd /laragon/www
git clone [repository-url] blog-system
``` 
Or

1. b. Clone the repository to your XAMPP htdocs folder:
```bash
cd /xampp/htdocs
git clone [repository-url] blog-system
```

2. Create a new database in phpMyAdmin named 'blog_db'

3. Import the database schema:
```sql
CREATE DATABASE blog_db;
USE blog_db;

-- Tables will be created automatically by the application
```

4. Configure database connection:
- Navigate to `config/database.php`
- Update credentials if needed (default XAMPP credentials are preset)

5. Set up the upload directory:
```bash
mkdir uploads
chmod 777 uploads
```

6. Access the application using Laragon or XAMPP:
on Laragon :
```
http://blog-system.test
```

on XAMPP :
```
http://localhost/blog-system
```

Default admin credentials:
- Email: dummy.samr47@gmail.com
- Password: admin123

## File Structure
```
blog-system/
├── admin/
│   ├── dashboard.php
│   ├── posts/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   ├── categories/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   └── users/
│       ├── index.php
│       ├── create.php
│       ├── edit.php
│       └── delete.php
├── auth/
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   └── forgot-password.php
├── includes/
│   ├── functions.php
│   ├── header.php
│   ├── footer.php
│   └── sidebar.php
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   ├── admin.css
│   │   └── auth.css
│   ├── js/
│   │   └── main.js
│   └── images/
├── uploads/
├── config/
│   └── database.php
├── index.php
├── post.php
├── search.php
└── README.md
```

## Security Features
- Password Hashing
- SQL Injection Prevention
- XSS Protection
- CSRF Protection
- Input Validation
- Secure File Upload

## Maintenance
- Regularly backup your database
- Keep PHP and MySQL updated
- Monitor error logs
- Update passwords periodically

## Contributing
1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request


## Support
For support, email[sam12subedi34@gmail.com] or open an issue in the repository.