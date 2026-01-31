# Online Exam

A full-featured **online examination platform** built with PHP and MySQL. Users can register, pay an exam fee, take a timed multi-stage exam, and view results. Admins can manage users, questions, and blog content.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [User Flow](#user-flow)
- [Database](#database)
- [Installation](#installation)
- [Configuration](#configuration)
- [Admin Access](#admin-access)
- [License](#license)

---

## Overview

This project is an **online exam system** (for Scholarship) that supports:

- **User registration & login** (email/password, OTP-based entry)
- **Password reset** via OTP sent to email
- **Exam fee payment** (Razorpay, ₹499)
- **Multi-stage exam** (25 questions, 30-minute timer)
- **Instant result** with score percentage
- **Admin dashboard** for users, questions, and blogs

---

## Features

### For Candidates

| Feature | Description |
|--------|-------------|
| **Landing & OTP entry** | Enter email → receive OTP → verify to start journey |
| **Login / Register** | Standard login or new registration (first name, last name, email, password) |
| **Forgot password** | Request OTP → verify OTP → set new password |
| **Stage registration** | Optional stage-wise registration (name, phone, password) after OTP |
| **Payment** | Pay exam fee (₹499) via Razorpay before accessing the exam |
| **Test instructions** | Read instructions (25 questions, 50 marks, 30 min) and start exam |
| **Multi-stage exam** | 5 stages: Quantitative Aptitude, Logical Reasoning, General Knowledge, English, Final Submit |
| **Timer** | 30-minute countdown; auto-submit when time ends |
| **Results** | View score (correct/total) and percentage after submission |

### For Admins

| Feature | Description |
|--------|-------------|
| **Admin dashboard** | Central hub with links to all admin tools |
| **Manage users** | List users, view answers by user, delete users |
| **Add questions** | Add questions by stage, answer type (text/custom options) |
| **Edit/Modify questions** | Update or delete existing questions |
| **Add blog** | Create new blog posts (title, category, image, description) |
| **Modify blog** | Edit or remove existing blogs |
| **Add image** | Upload images to gallery |
| **Logout** | Secure admin logout |

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP (sessions, prepared statements) |
| **Database** | MySQL (database: `online_exam`) |
| **Frontend** | HTML5, CSS3, Bootstrap 5.3.x |
| **Assets** | Custom theme CSS/JS, Font Awesome, Popper.js |
| **Payment** | Razorpay Checkout (JavaScript SDK) |
| **Email (OTP)** | PHPMailer + SMTP (e.g. for password reset OTP) |

---

## Project Structure

```
online_exam/
├── index.php                 # Landing page (OTP entry, login link)
├── login-registration.php    # Login & registration
├── forgot_password.php       # Request password reset (OTP to email)
├── verify_otp.php            # Verify OTP for password reset
├── reset_password.php        # Set new password after OTP
├── send_otp.php              # Sends OTP email (PHPMailer)
├── otp_handler.php           # API: send/verify OTP (index flow)
├── session_handler.php       # Session handling
├── logout.php                # Logout (user/admin)
│
├── stage_registration.php    # Stage-wise registration form
├── stages.php                # Post-login: exam status, pay/start exam
├── payment_gateway.php       # Razorpay payment (₹499)
├── payment_success.php       # Payment callback, mark user as Paid
├── test-intructions.php      # Exam instructions (after payment)
├── form.php                  # Exam form (stages 1–5, timer, submit)
├── result.php                # Score and percentage
│
├── admin_dashboard.php       # Admin home
├── manage_users.php          # List users, view answers, delete
├── add_questions.php         # Add new questions
├── edit_question.php         # Edit/delete questions
├── add-blog.php              # Add blog
├── modify-blog.php           # Modify blog
├── submit_blog.php           # Blog form submission
├── add-image.php             # Gallery image upload
│
├── assets/
│   ├── css/                  # theme.css, theme-rtl, min variants
│   ├── img/                  # logos, hero, icons, stage images
│   └── js/                   # theme.js, bootstrap-navbar.js
├── vendors/                  # Bootstrap, Font Awesome, Popper, is.min.js
└── README.md                 # This file
```

---

## User Flow

1. **Landing (`index.php`)**  
   User enters email → Get OTP → Verify OTP → Redirect to login/register or stage registration.

2. **Login / Register (`login-registration.php`)**  
   - New user: Register (name, email, password) → then login.  
   - Existing user: Login → redirect to `stages.php` (user) or `admin_dashboard.php` (admin).

3. **Stages (`stages.php`)**  
   - If not paid → redirect to **Payment** (`payment_gateway.php`).  
   - If paid → show “Start Exam” / “Continue Exam” / “View Result” based on `exam_end_time` and `exam_completed`.

4. **Payment (`payment_gateway.php`)**  
   Pay ₹499 via Razorpay → `payment_success.php` updates `payment_status = 'Paid'` and optionally stores in `payments` table.

5. **Instructions (`test-intructions.php`)**  
   User reads instructions (25 questions, 30 min) and clicks “Start Exam” → `form.php`.

6. **Exam (`form.php`)**  
   - 30-minute timer stored in DB (`exam_end_time`).  
   - Stages: Stage 1 → … → Stage 5 (Final Submit).  
   - Answers saved in `user_answers`.  
   - On timer expiry or final submit → `exam_completed = 1` and redirect to **Result**.

7. **Result (`result.php`)**  
   Compares `user_answers` with `correct_answers`, shows total questions, correct count, and percentage.

---

## Database

- **Database name:** `online_exam`
- **Typical tables (inferred from code):**

| Table | Purpose |
|-------|--------|
| `users` | id, first_name, last_name, email, password, role, phone, payment_status, exam_end_time, exam_completed, otp, otp_expiry |
| `users_otp` | OTP for index flow (email, otp_code, created_at, is_verified) |
| `questions` | id, stage, question_text, answer_type, custom_options |
| `correct_answers` | question_id, correct_answer |
| `user_answers` | user_email, question_id, answer (unique on user_email + question_id for ON DUPLICATE KEY UPDATE) |
| `payments` | user_email, payment_id, status, created_at |
| `blogs` | title, category, image, description |
| `gallery_images` | image_path |

- **Exam stages (in DB):**  
  - Stage 1: Quantitative Aptitude & Numerical Ability (6 Questions)  
  - Stage 2: Logical Ability & Reasoning (6 Questions)  
  - Stage 3: General Knowledge (7 Questions)  
  - Stage 4: English Language (6 Questions)  
  - Stage 5: Final Submission  

You need to create these tables and set `role = 'admin'` for at least one user to access the admin dashboard.

---

## Installation

### Requirements

- PHP 7.4+ (with MySQLi extension)
- MySQL 5.7+ or MariaDB
- Web server (Apache/Nginx) with PHP support, or PHP built-in server
- Composer (optional, for PHPMailer if installed via Composer)

### Steps

1. **Clone or download** the project into your web root (e.g. `htdocs/online_exam` or `public_html/online_exam`).

2. **Create database and user:**
   ```sql
   CREATE DATABASE online_exam;
   -- Create tables: users, users_otp, questions, correct_answers, user_answers, payments, blogs, gallery_images (see your schema or migrations).
   ```

3. **Configure database** in PHP files:  
   Replace `localhost`, `root`, `""`, `online_exam` with your DB host, username, password, and database name in:
   - `login-registration.php`
   - `stages.php`
   - `form.php`
   - `result.php`
   - `payment_gateway.php`
   - `payment_success.php`
   - `forgot_password.php`
   - `verify_otp.php`
   - `reset_password.php`
   - `otp_handler.php`
   - `add_questions.php`
   - `edit_question.php`
   - `manage_users.php`
   - `stage_registration.php`
   - and any other file that uses `new mysqli(...)`.

4. **PHPMailer (for OTP email):**  
   - Ensure `send_otp.php` can load PHPMailer (e.g. `require 'PHPMailer/src/...'` or Composer autoload).  
   - Update SMTP settings in `send_otp.php` (Host, Username, Password, Port) for your mail server.

5. **Razorpay:**  
   - In `payment_gateway.php` (and any backend verification script), set your Razorpay Key ID and Key Secret (preferably from environment or config, not hardcoded).  
   - Ensure payment success/callback URL points to `payment_success.php` with required query params (e.g. `payment_id`).

6. **Web server:**  
   - Point document root to the project folder and ensure `index.php` is the default.  
   - Or run: `php -S localhost:8000` and open `http://localhost:8000`.

7. **Create admin user:**  
   - Register a user from the site, then in MySQL:  
     `UPDATE users SET role = 'admin' WHERE email = 'your-admin@example.com';`

---

## Configuration

| Item | Where | Notes |
|------|--------|------|
| DB host, user, password, db name | All PHP files that use `mysqli` | Use a single config file and include it to avoid repetition. |
| SMTP (OTP emails) | `send_otp.php` | Host, port, username, password, from address. |
| Razorpay | `payment_gateway.php` (and verification) | Key ID, Key Secret; use env vars in production. |
| Exam duration | `form.php` | Currently 1800 seconds (30 minutes); change the value where `exam_end_time` is set. |
| Exam fee | `payment_gateway.php` | Amount in paise (e.g. 49900 for ₹499). |

---

## Admin Access

1. Log in with a user that has `role = 'admin'` in the `users` table.  
2. You will be redirected to `admin_dashboard.php`.  
3. From there you can: manage users, add/edit questions, add/modify blogs, add images, and logout.

---

## License

This project is provided as-is. Specify your preferred license (e.g. MIT, proprietary) in this section or in a separate `LICENSE` file.

---

## Summary

- **Online Exam** is a complete PHP/MySQL exam platform with OTP entry, registration, login, password reset, Razorpay payment, timed multi-stage exam (25 questions, 30 min), result calculation, and an admin panel for users, questions, and blogs.  
- For GitHub, ensure sensitive data (DB passwords, SMTP credentials, Razorpay keys) are not committed; use environment variables or a local config file excluded from version control.
