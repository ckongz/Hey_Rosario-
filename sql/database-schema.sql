-- =====================================================
-- BARANGAY ROSARIO DATABASE SCHEMA - FIXED VERSION
-- With Working Password Hashes
-- =====================================================

DROP DATABASE IF EXISTS barangay_rosario;
CREATE DATABASE barangay_rosario CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE barangay_rosario;

-- =====================================================
-- ADMINS TABLE
-- =====================================================
CREATE TABLE admins (
    admin_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    role ENUM('admin', 'captain', 'staff') NOT NULL DEFAULT 'staff',
    contact_number VARCHAR(20),
    profile_picture VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- USERS TABLE
-- =====================================================
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    suffix VARCHAR(10),
    birth_date DATE,
    age INT,
    gender ENUM('Male', 'Female', 'Other'),
    contact_number VARCHAR(20),
    alternate_contact VARCHAR(20),
    house_number VARCHAR(50),
    street VARCHAR(100),
    purok VARCHAR(50),
    barangay VARCHAR(100) DEFAULT 'Sto. Rosario',
    city VARCHAR(100) DEFAULT 'Angeles City',
    province VARCHAR(100) DEFAULT 'Pampanga',
    zip_code VARCHAR(10) DEFAULT '2009',
    profile_picture VARCHAR(255),
    role ENUM('citizen', 'guest') NOT NULL DEFAULT 'citizen',
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    email_verified BOOLEAN DEFAULT FALSE,
    email_verification_token VARCHAR(255),
    reset_password_token VARCHAR(255),
    reset_token_expiry DATETIME,
    last_login DATETIME,
    login_attempts INT DEFAULT 0,
    locked_until DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_verification_status (verification_status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- OTHER TABLES (Same as before)
-- =====================================================

CREATE TABLE user_verification (
    verification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    id_type ENUM('National ID', 'Passport', 'Drivers License', 'SSS ID', 'UMID', 'PhilHealth ID', 'Voters ID', 'Postal ID', 'Other') NOT NULL,
    id_number VARCHAR(100),
    id_file_path VARCHAR(255) NOT NULL,
    selfie_path VARCHAR(255) NOT NULL,
    additional_docs TEXT,
    remarks TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    reviewed_by INT,
    reviewed_at DATETIME,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES admins(admin_id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE announcements (
    announcement_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    summary TEXT,
    category ENUM('General', 'Emergency', 'Events', 'Health', 'Infrastructure', 'Government Services', 'Public Safety', 'Environmental', 'Youth', 'Senior Citizen') NOT NULL DEFAULT 'General',
    author_id INT NOT NULL,
    featured_image VARCHAR(255),
    attachments TEXT,
    target_audience ENUM('All', 'Citizens', 'Verified Only', 'Admin Only') DEFAULT 'All',
    is_pinned BOOLEAN DEFAULT FALSE,
    is_archived BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES admins(admin_id) ON DELETE CASCADE,
    INDEX idx_category (category),
    INDEX idx_created_at (created_at),
    INDEX idx_is_pinned (is_pinned),
    INDEX idx_is_archived (is_archived)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reports (
    report_id INT PRIMARY KEY AUTO_INCREMENT,
    tracking_number VARCHAR(50) UNIQUE NOT NULL,
    user_id INT,
    report_type ENUM('Infrastructure', 'Sanitation', 'Safety', 'Noise', 'Stray Animals', 'Other') NOT NULL,
    incident_type VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location_description TEXT NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    date_time_of_incident DATETIME,
    witnesses TEXT,
    evidence_paths TEXT,
    status ENUM('Pending', 'Reviewing', 'In Progress', 'Resolved', 'Closed', 'Requires Clarification') DEFAULT 'Pending',
    priority ENUM('Low', 'Medium', 'High', 'Urgent') DEFAULT 'Medium',
    assigned_to INT,
    admin_notes TEXT,
    resolution_details TEXT,
    is_anonymous BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    resolved_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to) REFERENCES admins(admin_id) ON DELETE SET NULL,
    INDEX idx_tracking_number (tracking_number),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_report_type (report_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE services (
    service_id INT PRIMARY KEY AUTO_INCREMENT,
    service_code VARCHAR(50) UNIQUE NOT NULL,
    service_name VARCHAR(255) NOT NULL,
    description TEXT,
    requirements TEXT,
    procedures TEXT,
    processing_time VARCHAR(100),
    fee DECIMAL(10, 2) DEFAULT 0.00,
    is_free BOOLEAN DEFAULT TRUE,
    category ENUM('Permit', 'Clearance', 'Certificate', 'Registry', 'Tax', 'Other') NOT NULL,
    online_available BOOLEAN DEFAULT TRUE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_service_code (service_code),
    INDEX idx_category (category),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE service_applications (
    application_id INT PRIMARY KEY AUTO_INCREMENT,
    reference_number VARCHAR(50) UNIQUE NOT NULL,
    service_id INT NOT NULL,
    user_id INT NOT NULL,
    applicant_name VARCHAR(255) NOT NULL,
    applicant_address TEXT NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    email VARCHAR(255),
    application_data JSON,
    requirements_submitted TEXT,
    status ENUM('Submitted', 'Reviewing', 'Processing', 'For Pickup', 'Completed', 'Rejected', 'Cancelled') DEFAULT 'Submitted',
    payment_status ENUM('Unpaid', 'Paid', 'Waived', 'Not Required') DEFAULT 'Not Required',
    payment_amount DECIMAL(10, 2),
    payment_date DATETIME,
    payment_reference VARCHAR(100),
    processing_notes TEXT,
    approved_by INT,
    approved_date DATETIME,
    released_date DATETIME,
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES admins(admin_id) ON DELETE SET NULL,
    INDEX idx_reference_number (reference_number),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE emergency_contacts (
    contact_id INT PRIMARY KEY AUTO_INCREMENT,
    department VARCHAR(100) NOT NULL,
    agency_name VARCHAR(255) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    hotline VARCHAR(20),
    mobile_number VARCHAR(20),
    email VARCHAR(255),
    address TEXT,
    operating_hours VARCHAR(100),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    category ENUM('Police', 'Fire', 'Medical', 'Barangay', 'Disaster', 'Other') NOT NULL,
    priority INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_priority (priority)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tourism_spots (
    spot_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    short_description VARCHAR(500),
    category ENUM('Historical', 'Cultural', 'Religious', 'Food', 'Business', 'Recreation', 'Other') NOT NULL,
    address TEXT,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    contact_number VARCHAR(20),
    website VARCHAR(255),
    operating_hours VARCHAR(255),
    entrance_fee VARCHAR(100),
    image_paths JSON,
    featured_image VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_is_featured (is_featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE budget_items (
    budget_id INT PRIMARY KEY AUTO_INCREMENT,
    fiscal_year YEAR NOT NULL,
    category VARCHAR(100) NOT NULL,
    sub_category VARCHAR(100),
    description TEXT NOT NULL,
    allocated_amount DECIMAL(15, 2) NOT NULL,
    expended_amount DECIMAL(15, 2) DEFAULT 0.00,
    remaining_amount DECIMAL(15, 2) GENERATED ALWAYS AS (allocated_amount - expended_amount) STORED,
    project_status ENUM('Planning', 'Ongoing', 'Completed', 'On Hold', 'Cancelled') DEFAULT 'Planning',
    completion_percentage INT DEFAULT 0,
    contractor VARCHAR(255),
    implementation_period VARCHAR(100),
    document_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fiscal_year (fiscal_year),
    INDEX idx_category (category),
    INDEX idx_project_status (project_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contact_messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    contact_number VARCHAR(20),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    category ENUM('General', 'Services', 'Report', 'Support', 'Feedback', 'Complaint', 'FOI', 'Other') DEFAULT 'General',
    is_read BOOLEAN DEFAULT FALSE,
    read_by INT,
    read_at DATETIME,
    replied BOOLEAN DEFAULT FALSE,
    reply_message TEXT,
    replied_by INT,
    replied_at DATETIME,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (read_by) REFERENCES admins(admin_id) ON DELETE SET NULL,
    FOREIGN KEY (replied_by) REFERENCES admins(admin_id) ON DELETE SET NULL,
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE activity_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    admin_id INT,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (admin_id) REFERENCES admins(admin_id) ON DELETE SET NULL,
    INDEX idx_created_at (created_at),
    INDEX idx_action (action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERT SAMPLE DATA WITH PLAIN TEXT PASSWORDS
-- NOTE: Run hash_passwords.php AFTER importing this!
-- =====================================================

-- Insert admins with PLAIN passwords (will be hashed by PHP script)
INSERT INTO admins (email, password, first_name, last_name, middle_name, role, contact_number, is_active) VALUES
('admin@heyrosario.com', 'Admin123!', 'System', 'Administrator', 'Chief', 'admin', '0917-123-4567', TRUE),
('captain@heyrosario.com', 'Captain123!', 'Juan', 'Dela Cruz', 'Santos', 'captain', '0917-234-5678', TRUE),
('staff1@heyrosario.com', 'Staff123!', 'Maria', 'Santos', 'Reyes', 'staff', '0917-345-6789', TRUE),
('staff2@heyrosario.com', 'Staff123!', 'Pedro', 'Garcia', 'Lopez', 'staff', '0917-456-7890', TRUE);

-- Insert users with PLAIN passwords (will be hashed by PHP script)
INSERT INTO users (email, password, first_name, last_name, middle_name, birth_date, age, gender, contact_number, house_number, street, purok, verification_status, email_verified) VALUES
('citizen1@email.com', 'Citizen123!', 'Anna', 'Rodriguez', 'Cruz', '1990-05-15', 34, 'Female', '0917-567-8901', '123', 'Rizal Street', 'Purok 1', 'verified', TRUE),
('citizen2@email.com', 'Citizen123!', 'Carlos', 'Martinez', 'Diaz', '1985-08-20', 39, 'Male', '0917-678-9012', '456', 'Gomez Avenue', 'Purok 3', 'verified', TRUE),
('pendinguser@email.com', 'Pending123!', 'Sofia', 'Ramos', 'Torres', '1995-03-10', 29, 'Female', '0917-789-0123', '789', 'Santos Boulevard', 'Purok 5', 'pending', FALSE);

-- Insert sample services
INSERT INTO services (service_code, service_name, description, requirements, processing_time, fee, is_free, category, online_available) VALUES
('SVC-001', 'Business Permit Application', 'Application for new business permit or renewal', 'Valid ID, Business Registration, DTI/SEC Certificate, Proof of Address', '5-7 business days', 500.00, FALSE, 'Permit', TRUE),
('SVC-002', 'Barangay Clearance', 'Certificate of barangay clearance for employment or other purposes', 'Valid ID, 2x2 Photo, Proof of Residency', '3-5 business days', 50.00, FALSE, 'Clearance', TRUE),
('SVC-003', 'Certificate of Residency', 'Certification that applicant is a resident of Barangay Sto. Rosario', 'Valid ID, Utility Bill or Lease Contract', '2-3 business days', 30.00, FALSE, 'Certificate', TRUE),
('SVC-004', 'Certificate of Indigency', 'Certificate for financially disadvantaged residents', 'Valid ID, Proof of Income/Unemployment, Supporting Documents', '3-5 business days', 0.00, TRUE, 'Certificate', TRUE),
('SVC-005', 'Community Tax Certificate (Cedula)', 'Annual community tax certificate', 'Valid ID, Income Declaration', '1-2 business days', 25.00, FALSE, 'Tax', TRUE);

-- Insert sample emergency contacts
INSERT INTO emergency_contacts (department, agency_name, contact_number, hotline, category, priority, is_active) VALUES
('Police', 'National Emergency Hotline', '911', '911', 'Police', 1, TRUE),
('Police', 'Angeles City Police Station', '(045) 322-3333', '(045) 322-3333', 'Police', 2, TRUE),
('Fire', 'Bureau of Fire Protection - Angeles City', '(045) 322-2222', '160', 'Fire', 1, TRUE),
('Medical', 'Angeles City Emergency Medical Services', '(045) 455-3000', '(045) 455-3000', 'Medical', 1, TRUE),
('Medical', 'Angeles University Foundation Medical Center', '(045) 625-2888', '(045) 625-2888', 'Medical', 2, TRUE),
('Barangay', 'Barangay Sto. Rosario Emergency Hotline', '(045) XXX-XXXX', '(045) XXX-XXXX', 'Barangay', 1, TRUE),
('Disaster', 'Angeles City Disaster Risk Reduction Office', '(045) 888-2200', '(045) 888-2200', 'Disaster', 1, TRUE);

-- Insert sample announcements
INSERT INTO announcements (title, slug, content, summary, category, author_id, is_pinned) VALUES
('Heavy Rainfall Warning - February 11, 2026', 'heavy-rainfall-warning-feb-11-2026', 'PAGASA has issued a heavy rainfall warning for Angeles City and surrounding areas. Expect moderate to heavy rains starting 2:00 PM today through tomorrow morning.\n\nWhat to do:\n- Stay indoors if possible\n- Avoid low-lying flood-prone areas\n- Keep emergency supplies ready\n- Monitor official updates\n\nEvacuation Centers (if needed):\n- Sto. Rosario Barangay Hall\n- Sto. Rosario Elementary School Gymnasium', 'Weather warning for heavy rainfall in Angeles City area. Stay safe and monitor updates.', 'Emergency', 1, TRUE),
('Barangay Assembly - February 20, 2026', 'barangay-assembly-feb-20-2026', 'All residents of Barangay Sto. Rosario are invited to attend our quarterly Barangay Assembly.', 'Quarterly barangay assembly on February 20, 2026. All residents invited.', 'Events', 1, FALSE),
('Free Vaccination Program - February 18-19', 'free-vaccination-program-feb-18-19', 'The Barangay Health Center, in partnership with the City Health Office, will conduct a free vaccination drive.', 'Free vaccination program on February 18-19 at Barangay Health Center.', 'Health', 1, FALSE);

-- Insert sample reports
INSERT INTO reports (tracking_number, user_id, report_type, incident_type, title, description, location_description, status, priority) VALUES
('RPT-2026-001', 1, 'Infrastructure', 'Broken Streetlight', 'Non-functional streetlight on Rizal Street', 'The streetlight near corner of Rizal Street and Gomez Avenue has been non-functional for 3 days.', 'Corner of Rizal Street and Gomez Avenue', 'Resolved', 'High'),
('RPT-2026-002', 2, 'Sanitation', 'Waste Collection Issue', 'Missed garbage collection on Santos Boulevard', 'Garbage collection was missed on our street yesterday.', 'Santos Boulevard, Purok 3', 'In Progress', 'Medium'),
('RPT-2026-003', 1, 'Infrastructure', 'Road Damage', 'Large pothole on Flores Street', 'There is a large pothole on Flores Street that has damaged several vehicles.', 'Flores Street', 'Pending', 'High');

-- Insert sample tourism spots
INSERT INTO tourism_spots (name, slug, description, short_description, category, address, operating_hours, entrance_fee, is_featured) VALUES
('San Agustin Parish Church - Sto. Rosario Chapel', 'san-agustin-parish-church', 'Built in 1896, this Spanish colonial-era chapel has stood witness to over a century of faith, resilience, and community.', 'Historic Spanish colonial chapel built in 1896', 'Religious', 'Sto. Rosario Plaza, Angeles City', 'Daily Mass: 6:00 AM and 6:00 PM', 'Free', TRUE),
('Heritage House of Don Pablo Santos', 'heritage-house-don-pablo-santos', 'This ancestral home, built in 1920, belonged to Don Pablo Santos.', 'Historic ancestral home from 1920', 'Historical', 'Sto. Rosario, Angeles City', 'By appointment only', 'Free (donations welcome)', TRUE),
('Nanay Ising\'s Lugaw at Goto', 'nanay-isings-lugaw-goto', 'What started as a humble pushcart in 1985 has become a beloved institution.', 'Legendary lugaw spot since 1985', 'Food', 'Corner of Rizal St. & Gomez Ave.', '6:00 PM - 2:00 AM daily', 'Php 35-50', FALSE);

-- Insert sample budget items
INSERT INTO budget_items (fiscal_year, category, sub_category, description, allocated_amount, expended_amount, project_status, completion_percentage) VALUES
(2026, 'Infrastructure & Development', 'Road Improvement', 'Flores Street Road Widening & Resurfacing', 2500000.00, 1000000.00, 'Ongoing', 40),
(2026, 'Infrastructure & Development', 'Public Facilities', 'Sto. Rosario Multi-Purpose Evacuation Center', 8000000.00, 0.00, 'Planning', 0),
(2026, 'Infrastructure & Development', 'Street Lighting', 'LED Streetlight Installation', 1200000.00, 960000.00, 'Ongoing', 80),
(2026, 'Social Services', 'Health Programs', 'Barangay Health Center Operations', 1500000.00, 450000.00, 'Ongoing', 30);
