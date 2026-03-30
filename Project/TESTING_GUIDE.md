# Quick Start Testing Guide

## 🚀 How to Test the Complete System

### 1. **Initial Setup (First Time)**
```
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Create database "project" (if not exists)
3. Run SQL: Import db_init.sql
4. Go to: http://localhost/website/Project/index.php
```

### 2. **Test New User Registration**
```
1. Click "Register" on homepage
2. Fill form:
   - Name: Juan Dela Cruz
   - Email: juan@example.com
   - Phone: 09991234567
   - Address: 123 Main St, Taytay
   - Birthdate: 1990-01-01
   - Gender: Male
   - Username: juancruz
   - Password: Password123! (min 8 chars)
3. Click "Submit"
4. Auto-redirects to login page
5. Login with juancruz / Password123!
```

### 3. **Test Certificate Request Workflow**
```
After login → Dashboard
  → Click "Request Certificate"
  → Select type: "Certificate of Indigency"
  → Enter purpose: "For school"
  → Click "Submit Request"
  → View My Requests (shows "pending" status)
  → Links work: Request another, Book facility
```

### 4. **Test Facility Booking Workflow**
```
After login → Dashboard
  → Click "Book Facility"
  → Select facility: "Basketball Court"
  → Event name: "Basketball Tournament"
  → Event date: (future date)
  → Start time: 09:00
  → End time: 17:00
  → Attendees: 20
  → Phone: 09991234567
  → Purpose: "Youth activity"
  → Click "Submit Booking"
  → My Bookings (shows "pending" status)
  → Links work: Book another, Request certificate
```

### 5. **Test Admin Functions (Special)**
```
Create admin user (via phpMyAdmin):
  - INSERT into users table with role='admin'
  
Login as admin → Dashboard
  → See 2 extra cards: "Manage Requests" & "Manage Bookings"
  → Click "Manage Requests"
  → See all users' requests in table
  → Click action button next to any request
  → Modal opens: Change status, add remarks
  → Save → Status updates
  → Switch to "Manage Bookings" (link in nav)
  → Same workflow for bookings
```

### 6. **Test Contact & Services**
```
From Homepage:
  → Click "Contact" in nav bar
  → OR Click "Send a message" in footer
  → contact.php loads
  → Shows: Address (1920 Tanchoco Ave)
  → Shows: Phone (0998 220 5844)
  → Fill contact form
  → Submit → Shows success message
  → Click "Return to Home" → back to index.php
```

### 7. **Test Logout Workflow**
```
After login (any authenticated page):
  → Click "Logout" in nav
  → logout.php processes (session destroyed)
  → Redirects to login.php
  → Shows green message: "You have been logged out successfully."
  → Can login again
```

### 8. **Test Security Features**
```
Access Control:
  → Try visiting admin_requests.php while logged in as regular user
  → Should get 403 "Access Denied" error
  
Authentication:
  → Try visiting dashboard.php without login
  → Auto-redirects to login.php
  
CSRF Protection:
  → View page source on any form
  → Should see hidden csrf_token field
```

### 9. **Test Responsive Design**
```
Mobile viewport:
  → Right-click → Inspect → Toggle device toolbar
  → Test at iPhone (375px), Tablet (768px), Desktop (1200px)
  → All pages should be readable and functional
```

### 10. **Test Navigation Links (Complete Loop)**
```
Click through these paths to verify no broken links:

index.php 
  → register.php → back to home (link) → login.php 
  → login with credentials 
  → dashboard.php → request.php 
  → submit form 
  → view_requests.php → back to dashboard 
  → booking.php → submit form 
  → view_bookings.php → back to dashboard 
  → contact.php (if admin: admin_requests.php) 
  → logout.php 
  → back at login.php with success message
```

---

## 📊 Expected Results

### ✅ Database Operations
- [x] User registration saves to users table
- [x] Login queries users correctly
- [x] Certificate requests save and display
- [x] Bookings save and display
- [x] Admin updates persist to database
- [x] Contact messages save

### ✅ Page Navigation
- [x] All nav links work (no 404 errors)
- [x] Login required pages redirect properly
- [x] Admin pages show 403 for non-admin
- [x] Success messages display on form submission
- [x] Status badges color-coded correctly

### ✅ Form Functionality
- [x] Forms validate inputs (client & server)
- [x] Required fields enforced
- [x] CSRF tokens present and valid
- [x] Password strength validated
- [x] Phone number format checked
- [x] Email format checked
- [x] Date validations work (no past dates for booking)

### ✅ Security
- [x] Passwords hashed (can't see in DB)
- [x] Sessions created on login
- [x] Sessions cleared on logout
- [x] CSRF tokens in all forms
- [x] HTML entities escaped in output
- [x] SQL injection prevented (prepared statements)

---

## 🔧 Common Test Data

### Admin User (Create via phpMyAdmin)
```sql
INSERT INTO users (fullname, email, phone, address, birthdate, gender, username, password, role)
VALUES ('Admin User', 'admin@example.com', '09991111111', 'Admin Address', '1980-01-01', 'Male', 
        'admin', '$2y$10$[hashed_password]', 'admin');
```
*Use password_hash('admin123', PASSWORD_BCRYPT) for password field*

### Staff User (Create via phpMyAdmin)
```sql
INSERT INTO users (fullname, email, phone, address, birthdate, gender, username, password, role)
VALUES ('Staff Member', 'staff@example.com', '09992222222', 'Staff Address', '1985-01-01', 'Female', 
        'staff', '$2y$10$[hashed_password]', 'staff');
```

### Test Credentials (After Registration)
- Username: juancruz
- Password: Password123!
- Email: juan@example.com
- Phone: 09991234567

---

## ⚠️ Troubleshooting

### "Page not found" or 404 errors
→ Check if file exists in Project folder
→ Verify URL uses .php extension
→ Check Apache is running (XAMPP Control Panel)

### "Access Denied" on admin pages
→ Normal! Regular users shouldn't see admin pages
→ Login as admin/staff user to access

### Forms not submitting
→ Check browser console for JavaScript errors
→ Verify form method="POST" and action attributes
→ Ensure CSRF token in hidden field

### Database connection error
→ Verify MySQL running in XAMPP
→ Check credentials in config.php (user: root, pass: empty)
→ Ensure database "project" exists

### Logout success message not showing
→ Clear browser cache (Ctrl+F5)
→ Check logout.php properly redirects with ?logout=success parameter
→ Verify login.php checks for this parameter

---

## 📱 Browser Compatibility

Tested on:
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

---

## 🎯 System Full Workflow (Complete)