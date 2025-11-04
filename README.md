# 🎓 School Management System

A comprehensive school management system built with Laravel 10, featuring role-based access control, grade management, and student administration.

![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue)
![License](https://img.shields.io/badge/License-MIT-green)

## ✨ Features

### 🔐 Authentication & Authorization
- Secure user authentication with Laravel Breeze
- Role-based access control using Spatie Permission
- Four user types: **Admin**, **Teacher**, **Student**, and **Parent**
- Separate authentication system for Parent Portal
- Role-specific dashboards and permissions
- Email verification support

### 👨‍💼 Admin Features
- **Student Management**
  - View all students with search functionality
  - Export student list to CSV
  - Bulk import students via CSV upload
  - View enrollment statistics
- **Assignment Management**
  - Create and manage assignments across all courses
  - Assign teachers to specific assignments
  - View all submissions and grades
  - Full administrative oversight
- **Fee Management**
  - Create and manage fee structures (Tuition, Library, Sports, etc.)
  - Assign fees to students individually or in bulk
  - Record payments with multiple payment methods
  - Track payment history and generate receipts
  - View fee statistics and outstanding amounts
  - Filter fees by status (pending, paid, partial, overdue)
- **Calendar & Events Management**
  - Create and manage academic events, holidays, and schedules
  - Event types: Academic, Holiday, Event, Exam, Meeting
  - Role-based visibility controls
  - Monthly calendar view with event details
  - Edit events directly from calendar view
- **PDF Report Generation**
  - Generate attendance reports with date range filtering
  - Create grade reports and student transcripts
  - Download printable PDF reports
  - Customized headers and footers with school branding
- **Announcements**
  - Create and publish announcements
  - Pin important notices
  - Audience targeting (All, Students, Teachers)
  - Category system (General, Academic, Event, Urgent)
- **Parent Portal Management**
  - Generate parent registration codes for individual students
  - Bulk generate registration codes for entire classes (Grade 1-12)
  - Download bulk registration codes as PDF for easy distribution
  - Manage parent-student relationships
  - Track code usage and expiration dates
  - Pre-approve email addresses for secure parent registration
- **System Management**
  - Manage users and roles
  - Access to all system features
  - View comprehensive system analytics
  - Customize application name and branding

### 👨‍🏫 Teacher Features
- **Course Management**
  - View assigned courses
  - Track student enrollments per course
- **Gradebook**
  - Add grades for students
  - Edit existing grades
  - View student averages and letter grades
  - Search students within courses
- **Assignment Management**
  - Create and manage assignments for courses
  - Set due dates and maximum scores
  - Accept file submissions and text content
  - Download student submission files
  - Grade submissions with feedback
  - View ungraded submissions with notification badges
  - Automatic gradebook integration
- **Student Directory**
  - View all students
  - Export student data to CSV

### 👨‍🎓 Student Features
- **Grade Viewing**
  - View grades for all enrolled courses
  - See individual assignment scores
  - Track course averages and letter grades
  - View instructor information
  - Download academic transcript as PDF
- **Assignment Submission**
  - View assignments for enrolled courses
  - Submit assignments with file uploads and text content
  - Track submission status (draft, submitted, graded)
  - Receive feedback and grades
  - View graded assignments with notification badges
  - Automatic notifications for graded work
- **Fee Management**
  - View all assigned fees
  - Track payment history and receipts
  - See outstanding balances
  - Filter fees by status
- **Attendance Tracking**
  - View personal attendance records
  - Track attendance percentage per course
  - See attendance status breakdown
- **Calendar & Events**
  - View school calendar
  - See upcoming events and holidays
  - Filter events by type
- **Course Information**
  - Access enrolled courses
  - View course details and descriptions
- **Announcements**
  - View published announcements
  - See pinned notices
  - Filter by category

### 👨‍👩‍👧‍👦 Parent Portal Features
- **Secure Parent Authentication**
  - Separate login system from school management
  - Registration code verification system
  - Admin-generated unique codes for authorized access
  - Email verification for enhanced security
- **Child Information Access**
  - View all linked children in one dashboard
  - Access comprehensive child information:
    - Academic grades by course with averages
    - Attendance records and percentages
    - Fee information and payment history
    - Recent school announcements
  - Multi-child support - view information for all your children
  - Automatic linking via registration codes

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
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js, Vue.js 3
- **Charts**: ApexCharts (for analytics dashboard)
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

### Parent Portal Credentials

After generating parent registration codes, parents can register at `/parent/register` with their unique code:

| Parent Account | Email | Password | Linked Student |
|----------------|-------|----------|----------------|
| Parent 1 | parent1@test.com | password | Student One |
| Parent 2 | parent2@test.com | password | Student Two |
| Parent 3 | parent3@test.com | password | Student Three, Student Four |

## 📊 Database Structure

### Key Tables
- **users**: Store all system users
- **roles**: Admin, Teacher, Student roles
- **role_user**: User role assignments
- **courses**: Course information
- **enrollments**: Student course enrollments
- **grades**: Student assignment grades
- **attendances**: Student attendance records
- **announcements**: School announcements and notices
- **events**: Calendar events and academic schedules
- **fee_structures**: Fee templates (Tuition, Library, etc.)
- **fees**: Individual fees assigned to students
- **payments**: Payment records with receipts
- **assignments**: Course assignments with due dates and instructions
- **submissions**: Student assignment submissions with content and files
- **submission_files**: Uploaded files for assignments
- **parent_users**: Parent accounts separate from school users
- **parent_student**: Many-to-many relationship linking parents to their children
- **parent_registration_codes**: Unique codes for parent registration with expiration dates

### Relationships
- Users have many enrollments, fees, courses (as teacher), assignments (as teacher), and submissions (as student)
- Courses have many enrollments, assignments, and events
- Enrollments have many grades and attendances
- Assignments have many submissions
- Submissions have many files
- Teachers have many courses and assignments
- Courses belong to teachers
- Fees belong to fee structures and students
- Payments belong to fees
- Submissions automatically create grades in the gradebook
- Parents have many students (via parent_student pivot table)
- Students have many parents (via parent_student pivot table)
- Parent registration codes belong to students and admins

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

## 🆕 Recent Features

### Parent Portal System
- Complete parent authentication and registration system
- Secure registration code verification (8-character unique codes)
- Admin interface to generate individual or bulk registration codes
- Bulk PDF generation for entire classes with expiration dates
- Parent dashboard to view all linked children
- Comprehensive child information access (grades, attendance, fees)
- Multi-child support for families
- Separate authentication guard for enhanced security
- Registration codes can be pre-approved with email addresses
- One-time use codes with expiration dates

### Assignment Submission System
- Complete assignment management for teachers and admins
- Student submission portal with file upload support
- Multiple file type support with size limits
- Draft saving and final submission workflow
- Inline grading interface for teachers
- Download submitted files directly
- Automatic gradebook integration - grades from assignments automatically appear in student gradebooks
- View tracking - notification badges disappear after viewing graded assignments
- Real-time notification badges:
  - Teachers see count of ungraded submissions
  - Students see count of newly graded assignments

### Fee Management System
- Complete fee management with 8 default fee types
- Payment tracking with receipt generation
- Multi-payment method support (Cash, Bank Transfer, Check, Online, Card)
- Fee assignment to students with flexible due dates
- Payment history and outstanding balance tracking

### Calendar & Events
- Monthly calendar view
- Event management with role-based visibility
- Multiple event types (Academic, Holiday, Event, Exam, Meeting)
- Edit events directly from calendar view (admin)
- Color-coded event display

### PDF Reports
- Attendance reports with date range filtering
- Grade reports by course or overall
- Student academic transcripts
- Parent registration codes bulk PDF generation
- Professional PDF formatting with school branding
- Improved pagination to prevent content splitting

### Real-Time Analytics Dashboard
- Interactive Vue.js-based analytics dashboard
- Attendance trends over time with date range filtering
- Grade distribution analysis
- Course performance metrics
- Attendance by course statistics
- ApexCharts integration for beautiful visualizations

### Enhanced Announcements
- Rich announcement system with categories
- Pinned announcements
- Audience targeting
- Published/draft status control

### Customization & Branding
- Customizable application name via environment variable
- Custom favicon support (SVG and ICO formats)
- School branding throughout the application

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Support

For support, email vyomtriv@gmail.com or open an issue in the GitHub repository.

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
