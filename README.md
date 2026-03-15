# 📋 Attendance Management System

A web-based **Attendance Management System** built with PHP and MySQL. It supports three user roles — **Admin**, **Class Teacher**, and **Student** — each with their own dedicated dashboard.

---

## 🚀 Features

### 👨‍💼 Admin
- Manage users (students, teachers)
- Create & manage classes, class arms, and subjects
- Create sessions & terms
- Assign subjects and teachers to classes
- Approve student and teacher registrations
- Upload students/teachers via Excel
- Export attendance reports
- View attendance shortage reports
- Manage timetables

### 👩‍🏫 Class Teacher
- Take attendance for assigned classes
- View student attendance records
- Upload chapter materials
- Download attendance records
- View today's attendance report

### 🎓 Student
- View personal attendance records
- Export attendance data
- Access chapter materials
- Manage profile

---

## 🛠️ Tech Stack

| Layer      | Technology          |
|------------|---------------------|
| Backend    | PHP 5.6+            |
| Database   | MySQL               |
| Frontend   | HTML, CSS, JavaScript, SCSS |
| Server     | XAMPP (Apache)      |
| Libraries  | Composer (PHP dependencies) |

---

## ⚙️ Installation & Setup

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (PHP 5.6 or newer)
- A modern web browser

### Steps

1. **Clone or copy** the project into your XAMPP `htdocs` folder:
   ```
   C:\xampp\htdocs\attendance-php\
   ```

2. **Import the database:**
   - Open [phpMyAdmin](http://localhost/phpmyadmin)
   - Create a new database named `attendancemsystem`
   - Import the SQL file located in the `DATABASE FILE/` folder

3. **Start XAMPP** — ensure **Apache** and **MySQL** are running.

4. **Open the app** in your browser:
   ```
   http://localhost/attendance-php/
   ```

---

## 🔐 Default Login Credentials

> ⚠️ **Change these credentials after your first login.**

| Role    | Email                    | Password    |
|---------|--------------------------|-------------|
| Admin   | admin@mail.com           | Chinnu      |
| Teacher | teachername@gmail.com    | rgmcet123   |
| Student | *(Register via the registration page)* | pass123 (default) |

---

## 📁 Project Structure

```
attendance-php/
├── Admin/              # Admin dashboard pages
├── ClassTeacher/       # Class teacher dashboard pages
├── Student/            # Student dashboard pages
├── Includes/           # Shared PHP includes (DB connection, headers, etc.)
├── DATABASE FILE/      # SQL database file
├── css/                # Global stylesheets
├── js/                 # Global JavaScript files
├── scss/               # SCSS source files
├── img/                # Images and assets
├── font/               # Font files
├── uploads/            # Uploaded files (profile pictures, documents)
├── vendor/             # Composer dependencies
├── index.php           # Main login page
├── studentRegister.php # Student registration page
├── teacherRegister.php # Teacher registration page
├── forgotPassword.php  # Password reset page
└── logout.php          # Logout handler
```

---

## 📄 License

This project is licensed under the terms found in the [LICENSE](LICENSE) file.

---

## 🙏 Credits

- Original project by **Sodiq Ahmed** via [CodeAstro](https://codeastro.com)
- Customized and maintained by **Gobbarakallu Surendra**
