# Modern Blog System

## Overview
A complete blog management system built with core PHP and MySQL. Features a modern responsive design, multi-user authentication, role-based access control, and full CRUD functionality.

## Features
- Multi-User Authentication System
  - User Registration and Login
  - Password Reset Functionality
  - Role-based Access Control (Admin/Author/User)
- Post Management
  - Rich Text Editor (TinyMCE)
  - Featured Image Upload
  - Draft/Published Status
  - SEO-friendly URLs
  - View Count Tracking
- Author Dashboard
  - Post Creation and Management
  - Comment Moderation
  - Profile Management
- Admin Dashboard
  - Full User Management
  - Category Management
  - Global Comment Moderation
  - Post Management
- Comment System
  - Nested Comments
  - Comment Moderation
  - Spam Protection
- Media Management
  - Secure Image Upload
  - Profile Picture Support
  - Featured Image Management
- Profile System
  - User Profile Pages
  - Bio and Personal Info
  - Activity History
- Frontend Features
  - Responsive Design
  - Advanced Search
  - Category Filtering
  - Recent Posts
  - Popular Posts
  - Social Media Integration

## Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache Web Server
- Laragon (recommended) or XAMPP

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

5. Set up the upload directory, Navigate to the project's root directory:
on Windows:
```bash
mkdir uploads
mkdir uploads/posts
mkdir uploads/profiles
```

on Linux:
```bash
mkdir uploads
mkdir uploads/posts
mkdir uploads/profiles
chmod -R 777 uploads
```
6. Access the application using Laragon or XAMPP:
using Laragon :
```
http://blog-system.test
```

using XAMPP :
```
http://localhost/blog-system
```

Default admin credentials:
- Email: dummy.samr47@gmail.com
- Password: admin123

## File Structure
```
blog-system/
├── admin/               # Admin panel files
│   ├── dashboard.php
│   ├── posts/
│   ├── categories/
│   ├── comments/
│   └── users/
├── author/             # Author panel files
│   ├── dashboard.php
│   ├── posts/
│   └── comments/
├── auth/               # Authentication files
│   ├── login.php
│   ├── register.php
│   └── forgot-password.php
├── assets/             # Static assets
│   ├── css/
│   ├── js/
│   └── images/
├── config/             # Configuration files
├── includes/           # Common includes
├── uploads/            # User uploads
│   ├── posts/
│   └── profiles/
└── various PHP files   # Core functionality

```

## Security Features
- Secure Password Hashing
- SQL Injection Prevention
- XSS Protection
- CSRF Protection
- Input Validation
- Secure File Upload
- Role-based Access Control
- Session Security
- Secure Password Reset

## Development
- Built with vanilla PHP for easy customization
- Modular code structure
- Clean and documented code
- Easy to extend and modify

## Maintenance
- Regular database backups recommended
- Keep PHP and MySQL updated
- Monitor error logs
- Update security credentials periodically
- Check file permissions regularly

## Contributing
1. Fork the repository
2. Create feature branch (git checkout -b feature/AmazingFeature)
3. Commit changes (git commit -m 'Add some AmazingFeature')
4. Push to branch (git push origin feature/AmazingFeature)
5. Open a Pull Request


## Support
For support:

Open an issue in the repository.
Email: [SAMRAT](mailto:info@subedi-samrat.com.np)