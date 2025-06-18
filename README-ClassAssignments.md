# Part 1 – Setup & Requirements

## User Roles
- Currently: default `user`
- Admin role will be added in Part 4

## Allowed Input Format
- Title: string, required, no special characters
- Description: text, optional
- Due Date: date format (Y-m-d)
- Completion: boolean (true/false)

## Input Filtering
Input validation will be enforced using Laravel Form Request in Part 2.

## Technologies
- Laravel 11
- Jetstream (Livewire)
- Fortify for Authentication


# Part 2 – Registration/Login Validation + Profile Page Enhancements

## Registration & Login Input Validation
- Created two Form Request classes:
  - `App\Http\Requests\RegisterRequest.php`
  - `App\Http\Requests\LoginRequest.php`
- Applied these to the registration and login controllers.
- Validation rules include:
  - Name: alphabetic characters only (regex: `^[A-Za-z\s]+$`)
  - Email: valid email format, unique
  - Password: min 8 characters, confirmed
- Replaced default validation logic in:
  - `FortifyServiceProvider.php` (within `createUsersUsing()` and `authenticateUsersUsing()`)

## Profile Page Enhancements
- Modified the `users` table to include:
  - `nickname`, `avatar`, `phone`, `city`
- Updated `User` model (`app/Models/User.php`) to make new fields fillable.
- Created form in `resources/views/profile/update-profile-information-form.blade.php` to allow:
  - Nickname update (shown in top-right navbar)
  - Avatar upload (with file validation)
  - Phone number, city, email, and password updates
  - Delete account functionality

## Files Modified
- `app/Http/Requests/RegisterRequest.php`
- `app/Http/Requests/LoginRequest.php`
- `app/Models/User.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/profile/update-profile-information-form.blade.php`
- `database/migrations/xxxx_xx_xx_xxxxxx_add_profile_fields_to_users_table.php`

## Validation
- Used Laravel's Form Request classes for clean, reusable validation logic.
- All form inputs are sanitized and validated with regex where needed.


# Part 3(Authentication): MFA via Email, Rate Limiting, Argon2 Hashing, Manual Salting

## MFA via Email
- Enabled `twoFactorAuthentication` in `config/fortify.php`.
- Simulated email MFA manually (code can be generated and displayed on-screen for demo).

## Rate Limiting
- Used Laravel’s built-in `RateLimiter` to limit login attempts to 3.
- Configured via `FortifyServiceProvider.php` in the `boot()` method.

## Argon2 Password Hashing
- Switched password hashing from `bcrypt` to `argon2id` in `config/hashing.php`.

## Manual Salting
- Modified `users` table and `User` model to include a `salt` column.
- On registration, generated a random alphanumeric salt and combined it with password before hashing.
- Salt stored and used during authentication.

## Files Modified
- `config/fortify.php`
- `config/hashing.php`
- `app/Models/User.php`
- `database/migrations/xxxx_xx_xx_xxxxxx_add_salt_to_users_table.php`
- `app/Providers/FortifyServiceProvider.php`

## Validation
- All sensitive operations (login, password) validated with secure Laravel methods.
- Salt handled safely during registration and login.

# Part 4 (Authorization) – Role-Based Access Control (RBAC)

## Overview

In this part of the To-Do App, we have implemented an authorization layer using **Role-Based Access Control (RBAC)** to differentiate between **Admin** and **User** roles. Only authenticated users can access the To-Do page, and access is granted based on their assigned roles and permissions.

---

## Features Implemented

## 1. Authentication Layer
- Users must be authenticated via the login page before accessing the application.
- After login, users are redirected based on their roles:
  - **Admin** → `/admin-dashboard`
  - **User** → `/todo`

## 2. Role-Based Access
We created two new database tables:
- `user_roles` → links users to roles
- `role_permissions` → defines the actions (CRUD) each role can perform

---

##  Migration Files


---

# Part 5 – XSS, CSRF, and CSP Prevention

##  Overview

In Part 5, security enhancements were implemented in the To-Do App to defend against three common web vulnerabilities:

- **Cross-Site Scripting (XSS)**
- **Cross-Site Request Forgery (CSRF)**
- **Content Security Policy (CSP)**

These implementations ensure that the application follows secure coding practices and complies with browser-enforced security measures.

---

## 1. Cross-Site Scripting (XSS) Protection

##  What Was Done

- For dynamic user-generated input (e.g., To-Do task content), enforced sanitization.

## Example Fix

```blade
<!-- Safe -->
<p>{{ $todo->task }}</p>

