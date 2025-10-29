# 🎓 School Management System

A comprehensive school management system built with Laravel 10, featuring role-based access control, grade management, and student administration.

![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue)
![License](https://img.shields.io/badge/License-MIT-green)

## ✨ Features

### 🔐 Authentication & Authorization
- Secure user authentication with Laravel Breeze
- Role-based access control using Spatie Permission
- Three user roles: **Admin**, **Teacher**, and **Student**
- Role-specific dashboards and permissions
- Email verification support

### 👨‍💼 Admin Features
- **Student Management**
  - View all students with search functionality
  - Export student list to CSV
  - Bulk import students via CSV upload
  - View enrollment statistics
- **System Management**
  - Manage users and roles
  - Access to all system features
  - View comprehensive system analytics

### 👨‍🏫 Teacher Features
- **Course Management**
  - View assigned courses
  - Track student enrollments per course
- **Gradebook**
  - Add grades for students
  - Edit existing grades
  - View student averages and letter grades
  - Search students within courses
- **Student Directory**
  - View all students
  - Export student data to CSV

### 👨‍🎓 Student Features
- **Grade Viewing**
  - View grades for all enrolled courses
  - See individual assignment scores
  - Track course averages and letter grades
  - View instructor information
- **Course Information**
  - Access enrolled courses
  - View course details and descriptions

### 🌐 Public Website
- Modern, responsive design
- Public pages:
  - Home
  - About
  - Academics
  - Admissions
  - Contact (with Livewire form and email notifications)
- Fast-loading local SVG images
- SEO-friendly with sitemap.xml and robots.txt

## 🛠️ Technology Stack

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Permissions**: Spatie Laravel Permission
- **Interactive Components**: Livewire 3
- **Build Tool**: Vite
- **Email**: Laravel Mail with SMTP support

## 📋 Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL >= 5.7
- Git

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/Vyom03/school-management-system.git
cd school-management-system
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install NPM Dependencies
```bash
npm install
```

### 4. Environment Setup
```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Database Configuration
Edit `.env` file and configure your database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 6. Mail Configuration (Optional)
Configure SMTP settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

CONTACT_RECIPIENT=admin@yourschool.com
```

### 7. Run Migrations and Seeders
```bash
# Run migrations
php artisan migrate

# Seed the database with roles, users, courses, and grades
php artisan db:seed
```

### 8. Build Assets
```bash
# For development
npm run dev

# For production
npm run build
```

### 9. Start the Application
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 👥 Default User Credentials

After running the seeders, you can log in with these credentials:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin1@test.com | password |
| Teacher | teacher1@test.com | password |
| Teacher | teacher2@test.com | password |
| Student | student1@test.com | password |
| Student | student2@test.com | password |
| Student | student3@test.com | password |
| Student | student4@test.com | password |
| Student | student5@test.com | password |

## 📊 Database Structure

### Key Tables
- **users**: Store all system users
- **roles**: Admin, Teacher, Student roles
- **role_user**: User role assignments
- **courses**: Course information
- **enrollments**: Student course enrollments
- **grades**: Student assignment grades

### Relationships
- Users have many enrollments
- Courses have many enrollments
- Enrollments have many grades
- Teachers have many courses
- Courses belong to teachers

## 🎨 Features in Detail

### Grade Management
- **Letter Grade System**: Automatic conversion from percentages
  - A: 90-100%
  - B: 80-89%
  - C: 70-79%
  - D: 60-69%
  - F: Below 60%
- **Weighted Averages**: Proper calculation based on max scores
- **Assignment Tracking**: Track individual assignment scores and comments

### Student Import/Export
- **CSV Export**: Download complete student roster with enrollment data
- **CSV Import**: Bulk upload students with validation
  - Format: Name, Email
  - Automatic role assignment
  - Duplicate detection
  - Error reporting

### Search & Filtering
- Real-time student search by name or email
- Course-specific student filtering
- Pagination for large datasets

## 🔒 Security Features

- CSRF protection on all forms
- Password hashing with bcrypt
- Email verification
- Role-based middleware protection
- SQL injection prevention via Eloquent ORM
- XSS protection with Blade templating

## 📱 Responsive Design

- Mobile-friendly interface
- Tailwind CSS utility classes
- Dark mode support
- Accessible navigation
- Touch-optimized controls

## 🧪 Testing

```bash
# Run tests (when available)
php artisan test
```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Support

For support, email support@yourschool.com or open an issue in the GitHub repository.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Livewire](https://livewire.laravel.com)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)

## 📸 Screenshots

### Admin Dashboard
- Manage students, courses, and system settings
- View comprehensive analytics

### Teacher Gradebook
- Add and edit grades
- View student progress
- Search and filter students

### Student Portal
- View grades and course information
- Track academic progress

---

**Built with ❤️ using Laravel**
