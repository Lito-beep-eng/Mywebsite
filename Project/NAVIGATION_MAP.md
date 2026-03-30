# Barangay San Juan Management System - Navigation Map

## ✅ VERIFIED: All Pages Connected and Working

### 1. **Homepage & Entry Points**

#### index.php (Main Homepage)
- **Login Status Aware**: Shows different content for logged-in vs logged-out users
- **Navigation Bar Links**:
  - Home → index.php
  - Announcements → index.php#section2
  - Online Services → index.php#section3
  - **Contact** → contact.php ✅ NEW
  - If Logged In:
    - Dashboard → dashboard.php
    - Logout (Username) → logout.php
  - If Not Logged In:
    - Login → login.php
    - Register → register.php
- **Service Links**:
  - Certificate Request → request.php (if logged in, otherwise login.php)
  - Facility Booking → booking.php (if logged in, otherwise login.php)
- **Footer**:
  - Contact link to contact.php
  - Phone: 0998 220 5844
  - Address: 1920 Tanchoco Ave, Taytay, 1920 Metro Manila

---

### 2. **Authentication Flow**

#### login.php
- **Entry From**: index.php, register.php, after logout
- **Features**:
  - Session validation (redirects to dashboard if already logged in)
  - CSRF token protection
  - Remember me functionality
  - Password verification with hashing
  - **Success Message**: Shows "Logged out successfully" if redirected from logout.php with ?logout=success parameter ✅ FIXED
- **Links**:
  - Register → register.php
  - Back to Home (via footer) → index.php
  - Auto-redirect to dashboard.php on successful login

#### register.php
- **Entry From**: index.php, login.php
- **Features**:
  - Complete user information collection (name, email, phone, address, birthdate, gender)
  - CSRF token protection
  - Email/phone/username validation
  - Password strength validation (min 8 chars)
  - Duplicate detection (username/email)
  - Profile image upload support
- **Links**:
  - Back to Home → index.php
  - Already have account? → login.php
  - Auto-redirect to login.php on successful registration

#### logout.php
- **Entry From**: Logout links on any authenticated page
- **Features**:
  - Session destruction
  - Cookie clearing
  - Audit logging
  - Secure token handling
- **Redirect**: login.php?logout=success (shows success message)

---

### 3. **User Dashboard & Main Hub**

#### dashboard.php
- **Access**: Authenticated users only (redirects to login if not authenticated)
- **Features**:
  - User role detection (regular user vs admin/staff)
  - Personalized welcome message
  - Card-based navigation layout
  - Role-based visibility (admin/staff cards only for those roles)
- **Navigation Links**:
  - Home → index.php
  - Logout → logout.php
  - **User Cards**:
    - Request Certificate → request.php
    - Book Facility → booking.php
    - View My Requests → view_requests.php
    - View My Bookings → view_bookings.php
  - **Admin/Staff Cards** (visible only to admin/staff):
    - Manage Requests → admin_requests.php
    - Manage Bookings → admin_bookings.php

---

### 4. **Certificate Request Feature**

#### request.php (Submit Form)
- **Access**: Authenticated users only
- **Features**:
  - 4 certificate types: Indigency, Clearance, Solo Parent, Birth Certificate
  - CSRF protection
  - User information pre-filled
  - Purpose/details collection
- **Navigation**:
  - Top Nav: Home → index.php, Dashboard → dashboard.php, Logout → logout.php
  - Submit Button → Inserts to database
  - Success Message displayed
  - Links: View My Requests → view_requests.php, Book Facility → booking.php

#### view_requests.php (Status Tracker)
- **Access**: Authenticated users only
- **Features**:
  - Displays all user's certificate requests
  - Shows status with color badges (pending=yellow, approved=green, completed=blue, rejected=red)
  - Sorting by creation date (newest first)
- **Navigation**:
  - Top Nav: Home → index.php, Dashboard → dashboard.php, Logout → logout.php
  - New Request Button → request.php
  - Links: Request Another → request.php, View Bookings → view_bookings.php, Back to Dashboard → dashboard.php

---

### 5. **Facility Booking Feature**

#### booking.php (Submit Form)
- **Access**: Authenticated users only
- **Features**:
  - 4 facilities: Basketball Court, Multipurpose Hall, Sound System, Barangay Vehicle
  - Comprehensive validation:
    - Date cannot be in past
    - End time must be after start time
    - Attendees >= 1
    - Phone number format validation
  - CSRF protection
  - Responsive layout
- **Navigation**:
  - Top Nav: Home → index.php, Dashboard → dashboard.php, Logout → logout.php
  - Submit Button → Inserts to database
  - Success Message displayed
  - Links: My Bookings → view_bookings.php, Request Certificate → request.php

#### view_bookings.php (Status Tracker)
- **Access**: Authenticated users only
- **Features**:
  - Card-based display of all bookings
  - Shows facility, event, date, time, attendees, status
  - Status color badges (pending=yellow, approved=green, completed=blue, cancelled=red)
  - Sorted by event date (newest first)
- **Navigation**:
  - Top Nav: Home → index.php, Dashboard → dashboard.php, Logout → logout.php
  - New Booking Button → booking.php
  - Links: Book Another → booking.php, View Requests → view_requests.php, Back to Dashboard → dashboard.php

---

### 6. **Admin/Staff Management**

#### admin_requests.php (Manage Certificates)
- **Access**: admin or staff role only (403 error if unauthorized)
- **Features**:
  - Table view of all certificate requests
  - Shows requestor name, email, certificate type, purpose, status
  - Status update modal with remarks
  - Statuses: pending, approved, completed, rejected
- **Navigation**:
  - Top Nav: Dashboard → dashboard.php, Manage Bookings → admin_bookings.php, Logout → logout.php
  - Action Buttons: Update status for each request

#### admin_bookings.php (Manage Facilities)
- **Access**: admin or staff role only (403 error if unauthorized)
- **Features**:
  - Table view of all facility bookings
  - Shows resident info, facility, event details, date/time, status
  - Status update modal with remarks
  - Statuses: pending, approved, completed, cancelled
- **Navigation**:
  - Top Nav: Dashboard → dashboard.php, Manage Requests → admin_requests.php, Logout → logout.php
  - Action Buttons: Update status for each booking

---

### 7. **Additional Services**

#### contact.php (Contact Form)
- **Entry From**: index.php footer, navigation menu
- **Features**:
  - Office information display (address, phone, email updated)
  - Contact form for general inquiries
  - Message submission to database
- **Navigation**:
  - Return to Home → index.php
  - Success/Error message on submit

#### services.php (Legacy Service Requests) ✅ UPDATED
- **Access**: Authenticated users only
- **Features**:
  - Alternative service request form
  - 5 service types available
  - CSRF token protection ✅ FIXED
  - Properly includes config.php, utils.php, db.php
- **Navigation**:
  - Top Nav: Home → index.php, Dashboard → dashboard.php, Logout → logout.php
  - Links: Request Certificate → request.php, Book Facility → booking.php, Back to Dashboard → dashboard.php

---

## 🔄 Complete User Journeys

### Journey 1: New User (Visitor → Registered User)
```
index.php 
  → Register → register.php 
  → (or) Back Home → index.php
  → Success → login.php (auto or manual)
  → Success → dashboard.php
```

### Journey 2: Returning User (Login)
```
index.php 
  → Login → login.php 
  → Enter credentials
  → Success → dashboard.php
```

### Journey 3: Certificate Request
```
dashboard.php 
  → Request Certificate → request.php 
  → Fill form
  → Submit → view_requests.php 
  → See status
  → View another → request.php (or)
  → Back to dashboard → dashboard.php
```

### Journey 4: Facility Booking
```
dashboard.php 
  → Book Facility → booking.php 
  → Fill form
  → Submit → view_bookings.php 
  → See status
  → Book another → booking.php (or)
  → Back to dashboard → dashboard.php
```

### Journey 5: Admin Review
```
dashboard.php 
  → Manage Requests → admin_requests.php 
  → Review/Update requests
  → View Bookings → admin_bookings.php 
  → Review/Update bookings
  → Back to Dashboard → dashboard.php
```

### Journey 6: Logout
```
Any authenticated page (Logout link)
  → logout.php 
  → Clear session
  → Redirect → login.php (shows success message)
  → Login again (or) → index.php
```

---

## 📋 Navigation Verification Checklist

- [x] index.php has links to all main features
- [x] login.php properly handles authentication
- [x] register.php collects all required user data
- [x] logout.php properly destroys session and shows success message
- [x] dashboard.php shows role-appropriate cards
- [x] request.php has CSRF protection and navigation
- [x] view_requests.php shows status tracking
- [x] booking.php has validation and CSRF protection
- [x] view_bookings.php shows status tracking
- [x] admin_requests.php restricted to admin/staff
- [x] admin_bookings.php restricted to admin/staff
- [x] contact.php links back to home
- [x] services.php properly fixed with CSRF tokens
- [x] All pages have consistent navigation headers
- [x] index.html duplicate removed
- [x] Logout success message implemented
- [x] Contact link added to main navigation

---

## 🔐 Security Features Implemented

- [x] CSRF token verification on all forms
- [x] Session-based authentication
- [x] Password hashing with password_verify()
- [x] Input sanitization with htmlspecialchars()
- [x] SQL injection prevention with prepared statements
- [x] Role-based access control (admin/staff pages)
- [x] Cookie security flags (httponly)
- [x] User authentication checks on protected pages
- [x] Password strength validation (min 8 chars)
- [x] Email and phone format validation

---

## 📊 Database Tables

1. **users** - User account information with role-based access
2. **certificate_requests** - Certificate request tracking with status
3. **bookings** - Facility booking tracker with approval workflow
4. **contact_messages** - General inquiry messages
5. **service_requests** - Legacy service request form submissions

All tables properly indexed with foreign keys and timestamps.

---

## 🎯 System Status: FULLY CONNECTED ✅

All pages are responsive, properly linked, and secured. The system provides a complete workflow for residents to request services and administrators to review and approve them.
