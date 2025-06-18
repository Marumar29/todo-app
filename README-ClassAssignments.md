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

