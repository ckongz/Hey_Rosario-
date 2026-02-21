
BARANGAY ROSARIO WEBSITE - COMPLETE DOCUMENTATION
====================================================

PROJECT: Hey Rosario! - Digital Platform for Barangay Sto. Rosario
VERSION: 1.0
DATE: February 18, 2026
DEVELOPERS: Casey, Abygale, Ayenne, Prince

TABLE OF CONTENTS

1. Project Overview
2. System Requirements
3. Installation Instructions
4. Database Setup
5. File Structure
6. Test Accounts
7. Features Overview
8. Code Architecture
9. Security Implementation
10. Group Member Responsibilities
11. Troubleshooting
12. Technical Specifications

1. PROJECT OVERVIEW

Hey Rosario! is a comprehensive web-based digital platform for Barangay Sto. 
Rosario, Angeles City. The system provides:

- Government service applications
- Citizen reporting system
- Real-time announcements
- Emergency hotline directory
- Tourism information
- Transparency portal with budget data
- User authentication with role-based access
- Admin dashboard for management

2. SYSTEM REQUIREMENTS

SERVER REQUIREMENTS:
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- mod_rewrite enabled

DEVELOPMENT ENVIRONMENT:
- XAMPP 8.0+ (recommended) or WAMP Server
- Modern web browser (Chrome, Firefox, Edge)
- Text editor (VS Code, Sublime Text, etc.)

3. INSTALLATION

STEP 1: Extract Files
- Extract the barangay_rosario.zip file to your web server directory
- For XAMPP: C:\xampp\htdocs\barangay_rosario
- For WAMP: C:\wamp64\www\barangay_rosario

STEP 2: Import Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Click "Import" tab
3. Choose file: database-schema.sql
4. Click "Go" to import
5. Database "barangay_rosario" will be created with sample data

STEP 3: Configure Database Connection
1. Open: includes/db-connection.php
2. Update these values if needed:
   - DB_HOST: 'localhost'
   - DB_NAME: 'barangay_rosario'
   - DB_USER: 'root' (change if using custom MySQL user)
   - DB_PASS: '' (add password if MySQL has one)

STEP 4: Set Folder Permissions
Ensure write permissions for:
- assets/images/uploads/

STEP 5: Access Website
- Homepage: http://localhost/barangay_rosario/
- Admin Login: http://localhost/barangay_rosario/login.php

4. DATEBASE SETUP

DATABASE NAME: barangay_rosario

MAIN TABLES:
1. admins - Admin accounts (admin, captain, staff)
2. users - Citizen/regular user accounts
3. user_verification - ID verification records
4. announcements - Community updates and alerts
5. reports - Citizen incident reports
6. services - Government services catalog
7. service_applications - Service applications tracking
8. emergency_contacts - Emergency hotline directory
9. tourism_spots - Local attractions and businesses
10. budget_items - Budget and project transparency data
11. contact_messages - Contact form submissions
12. activity_logs - User activity tracking

RELATIONSHIPS:
- users.user_id → reports.user_id (CASCADE)
- admins.admin_id → announcements.author_id (CASCADE)
- services.service_id → service_applications.service_id (CASCADE)
- All foreign keys properly indexed for performance

5. FILE STRUCTURE

barangay_rosario/
├── assets/
│   ├── css/
│   │   └── styles.css (Main stylesheet - deep red & cream palette)
│   ├── js/
│   │   ├── script.js (Navigation and main interactions)
│   │   ├── form-validation.js (Form validation)
│   │   ├── map-integration.js (Google Maps)
│   │   └── report-tracker.js (Report status tracking)
│   └── images/
│       └── uploads/ (User uploaded files)
│
├── includes/
│   ├── db-connection.php (PDO database connection)
│   ├── session-check.php (Session management & security)
│   ├── header.php (HTML head and navbar)
│   ├── footer.php (Footer and scripts)
│   └── navbar.php (Navigation menu)
│
├── processes/
│   ├── login-process.php (Authentication handler)
│   ├── logout.php (Session destruction)
│   ├── register-process.php (User registration)
│   ├── submit-report.php (Report submission)
│   └── contact-form-process.php (Contact form handler)
│
├── admin/
│   └── admin-login.php (Separate admin login)
│
├── Main PHP Pages (16 files):
│   ├── index.php (Homepage)
│   ├── about.php (About Us)
│   ├── services.php (Government Services)
│   ├── report.php (Citizen Reporting)
│   ├── updates.php (Announcements)
│   ├── emergency.php (Emergency Hotlines)
│   ├── tourism.php (Tourism Guide)
│   ├── transparency.php (Transparency Portal)
│   ├── contact.php (Contact Us)
│   ├── login.php (User Login)
│   ├── register.php (User Registration)
│   ├── dashboard.php (Citizen Dashboard)
│   ├── dashboard2.php (Visitor Dashboard)
│   ├── admin-dashboard.php (Admin Dashboard)
│   ├── privacy.php (Privacy Policy)
│   └── terms.php (Terms of Service)
│
└── database-schema.sql (Complete database structure)

6. TEST ACCOUNTS

ADMIN ACCOUNTS (Login via login.php or admin-login.php):

1. System Administrator
   Email: admin@heyrosario.com
   Password: Admin123!
   Role: admin
   Access: Full system access

2. Barangay Captain
   Email: captain@heyrosario.com
   Password: Captain123!
   Role: captain
   Access: Full system access

3. Staff Member 1
   Email: staff1@heyrosario.com
   Password: Staff123!
   Role: staff
   Access: Limited admin functions

4. Staff Member 2
   Email: staff2@heyrosario.com
   Password: Staff123!
   Role: staff
   Access: Limited admin functions

CITIZEN ACCOUNTS:

5. Verified Citizen 1
   Email: citizen1@email.com
   Password: Citizen123!
   Status: Verified
   Access: Full citizen features

6. Verified Citizen 2
   Email: citizen2@email.com
   Password: Citizen123!
   Status: Verified
   Access: Full citizen features

7. Pending Verification User
   Email: pendinguser@email.com
   Password: Pending123!
   Status: Pending
   Access: Limited until verified

7. FEATURES OVERVIEW

USER AUTHENTICATION & MANAGEMENT:
✓ Secure login with password hashing (bcrypt)
✓ Role-based access control (admin, captain, staff, citizen, guest)
✓ Session management with 30-minute timeout
✓ CSRF token protection on all forms
✓ Account verification system

GOVERNMENT SERVICES:
✓ Online service applications
✓ Service catalog with requirements
✓ Application tracking
✓ Status updates

CITIZEN REPORTING (Dynamic Feature #1):
✓ Multi-category incident reporting
✓ Photo upload capability
✓ Unique tracking number generation
✓ Real-time status updates
✓ Admin resolution tracking
✓ Anonymous reporting option

ANNOUNCEMENTS (Dynamic Feature #2):
✓ Category-based announcements
✓ Emergency alert system
✓ Priority/pinned announcements
✓ Search and filter functionality
✓ Admin content management

ADMIN DASHBOARD:
✓ User management (approve/reject/delete)
✓ Report management with status updates
✓ Announcement creation and editing
✓ Service management
✓ Statistics and analytics
✓ Activity logs

EMERGENCY ACCESS:
✓ Categorized emergency contacts
✓ Quick-dial interface
✓ Operating hours display
✓ Location information

TOURISM GUIDE:
✓ Local attractions database
✓ Category filtering
✓ Operating hours and fees
✓ Featured spots

TRANSPARENCY PORTAL:
✓ Budget allocation display
✓ Project status tracking
✓ Financial transparency
✓ Document repository

8. CODE ARCHITECTURE

DATABASE CONNECTION (includes/db-connection.php):
- Uses PDO for secure database operations
- Prepared statements prevent SQL injection
- Helper functions: executeQuery(), fetchOne(), fetchAll()
- Error logging and user-friendly messages

SESSION MANAGEMENT (includes/session-check.php):
- Functions:
  * isLoggedIn() - Check if user logged in
  * isAdminLoggedIn() - Check if admin logged in
  * getUserType() - Get user type (user/admin)
  * getUserId() - Get current user ID
  * getUserRole() - Get user role
  * requireLogin() - Force login
  * requireAdmin() - Force admin login
  * requireRole() - Check specific roles
  * checkSessionTimeout() - Auto logout after inactivity
  * generateCSRFToken() - Create CSRF tokens
  * verifyCSRFToken() - Validate CSRF tokens
  * logActivity() - Track user actions

FORM PROCESSING:
All forms follow this pattern:
1. Check if POST request
2. Verify CSRF token
3. Sanitize and validate input
4. Execute database operation with prepared statements
5. Redirect with success/error message

SECURITY FEATURES:
- Password hashing with password_hash() and password_verify()
- Input sanitization with sanitizeInput()
- Output escaping with htmlspecialchars()
- Prepared statements for all queries
- CSRF protection on all forms
- Session timeout after 30 minutes
- Login attempt tracking
- Activity logging

9. SECURITY
    
PASSWORD SECURITY:
- All passwords hashed using PHP's password_hash() with bcrypt
- Cost factor: 10 (default)
- Passwords stored as 255-character strings
- Never stored or transmitted in plain text

SQL INJECTION PREVENTION:
- All database queries use prepared statements with PDO
- Parameters bound separately from query
- No string concatenation in queries
- Example:
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);

XSS PROTECTION:
- All user input sanitized with sanitizeInput()
- All output escaped with htmlspecialchars()
- ENT_QUOTES flag prevents quote-based attacks
- UTF-8 encoding specified

CSRF PROTECTION:
- Tokens generated per session
- Stored in $_SESSION['csrf_token']
- Verified on all form submissions
- Example usage:
  <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

SESSION SECURITY:
- Session IDs regenerated on login
- 30-minute inactivity timeout
- Secure session configuration
- HttpOnly cookies (recommended to enable)

10. GROUP ROLES

CASEY ONG - Project Lead & Frontend Developer
Responsibilities:
- Overall project coordination and timeline management
- HTML/CSS/JavaScript implementation
- Responsive design coding
- Cross-browser testing
- Quality assurance and bug tracking
- User manual creation
Defense Topics:
- Real-time Updates system (Dynamic Feature #2)
- Emergency Access Module implementation
- Transparency Portal frontend
- Client-side validation
- Cross-browser compatibility

ABYGALE - UI/UX Designer & Design Lead
Responsibilities:
- Complete interface design
- User experience flow
- Branding and color scheme (deep red & cream palette)
- Design system creation
- Wireframing and prototyping
- Visual asset creation
- Responsive design guidelines
- Tourism content curation
Defense Topics:
- Home Dashboard design
- Tourism Guide Module layout
- Design system and branding strategy
- UI/UX design decisions
- Responsive design implementation

AYENNE - Database Administrator & Documentation Lead
Responsibilities:
- Database design and schema creation
- SQL queries and optimization
- Data integrity and relationships
- Backup procedures
- Database normalization
- System documentation
- Technical writing
- ER diagrams
Defense Topics:
- Citizen Reporting System (Dynamic Feature #1)
- Complete database architecture
- CRUD operations implementation
- Data relationships and foreign keys
- Database optimization techniques

PRINCE - PHP Backend Developer
Responsibilities:
- Server-side logic implementation
- Form processing
- Session management
- Authentication system
- Backend validation
- File upload handling
- Database connectivity
- Security implementation
Defense Topics:
- Government Services backend
- User Account System
- Permit workflow logic
- Authentication and security
- File upload processing

11. TROUBLESHOOTING

COMMON ISSUES:

Issue: "Database connection failed"
Solution:
- Check if MySQL is running in XAMPP/WAMP
- Verify database credentials in includes/db-connection.php
- Ensure database exists (import database-schema.sql)

Issue: "Call to undefined function password_hash()"
Solution:
- Update PHP to version 5.5 or higher
- Check PHP version: php -v

Issue: "Session not working"
Solution:
- Ensure session_start() is called before any output
- Check if includes/session-check.php is included
- Verify session folder has write permissions

Issue: "CSS not loading"
Solution:
- Check file path in includes/header.php
- Ensure assets/css/styles.css exists
- Clear browser cache (Ctrl+F5)

Issue: "Login redirects to error page"
Solution:
- Verify test account credentials
- Check if database has sample data
- Review error logs in browser console

Issue: "File upload not working"
Solution:
- Check folder permissions for assets/images/uploads/
- Verify upload_max_filesize in php.ini
- Ensure form has enctype="multipart/form-data"

12. TECHNICAL

PROGRAMMING LANGUAGES:
- PHP 7.4+ (Backend)
- MySQL 5.7+ (Database)
- JavaScript ES6 (Frontend interactivity)
- HTML5 (Structure)
- CSS3 (Styling)

DESIGN SPECIFICATIONS:
Color Palette:
- Primary Red: #8B0000 (Dark Red)
- Secondary Red: #B22222 (Firebrick)
- Accent Red: #DC143C (Crimson)
- Light Red: #FF6B6B (Soft Red)
- Background Cream: #FFF8E7 (Cosmic Latte)
- Card Cream: #FFEBCD (Blanched Almond)
- Pastel Hover Pink: #FFD1D1
- Pastel Hover Peach: #FFE5D9
- Pastel Hover Lavender: #E6E6FA
- Pastel Hover Mint: #E0F2E0
- Pastel Hover Yellow: #FFF2CC

Typography:
- Headings: Poppins (Google Fonts)
- Body: Inter (Google Fonts)
- Icons: Font Awesome 6.0

RESPONSIVE BREAKPOINTS:
- Desktop: 1400px+
- Tablet: 768px - 1399px
- Mobile: < 768px

ANIMATIONS:
- Transition Duration: 0.3s ease
- Hover Effects: Scale, translateY, color change
- Loading Animations: Fade in, shimmer

DATABASE DESIGN:
- Engine: InnoDB (supports foreign keys and transactions)
- Charset: utf8mb4 (full Unicode support)
- Collation: utf8mb4_unicode_ci
- Indexing: Primary keys, foreign keys, frequently queried columns

SECURITY STANDARDS:
- Password Hashing: bcrypt (cost 10)
- CSRF Protection: Token-based
- SQL Injection Prevention: Prepared statements
- XSS Prevention: Output escaping
- Session Management: Timeout-based

TESTING

Before submitting or presenting:

□ Database imports successfully
□ All test accounts login correctly
□ Registration creates new users
□ Citizen can submit reports
□ Admin can view and update reports
□ Announcements display on updates page
□ Emergency contacts load properly
□ Services page shows available services
□ Tourism spots display with categories
□ Transparency page shows budget data
□ Contact form submits successfully
□ Logout redirects to homepage
□ Session timeout works after 30 minutes
□ All navigation links work
□ Mobile responsive design works
□ CSS loads properly (deep red & cream colors)
□ JavaScript interactions work (hamburger menu)
□ Forms validate input correctly
□ Error messages display appropriately
□ Success messages show after actions

PRESENTATION NOTES

RECOMMENDED DEMO FLOW:

1. Homepage Overview
   - Show hero section and quick access cards
   - Explain color scheme and design philosophy
   
2. User Registration & Login
   - Register a new account
   - Login with test account
   - Show session management
   
3. Citizen Features
   - Submit a report (Dynamic Feature #1)
   - View updates/announcements (Dynamic Feature #2)
   - Browse services
   - Check emergency contacts
   
4. Admin Dashboard
   - Login as admin
   - Show user management
   - Demonstrate report status update
   - Display statistics
   
5. Code Walkthrough
   - Database schema
   - Security implementation
   - Session management
   - Form processing

CONTACT & SUPPORT

For questions or issues:
- Check this documentation first
- Review code comments in PHP files
- Consult with team members based on their roles
- Test with provided test accounts

Development Team:
- Casey: Project Lead
- Abygale: Design Lead
- Ayenne: Database Administrator
- Prince: Backend Developer


