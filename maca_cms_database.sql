-- Create database (uncomment if you need to create the database)
-- CREATE DATABASE IF NOT EXISTS maca_cms;
-- USE maca_cms;

-- Drop tables if they exist to avoid errors
DROP TABLE IF EXISTS team_members;
DROP TABLE IF EXISTS media;
DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS announcements;
DROP TABLE IF EXISTS site_settings;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create announcements table
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create news table
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    summary TEXT NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create media table
CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create team_members table
CREATE TABLE team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    bio TEXT NOT NULL,
    image_path VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create site_settings table
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, email, role, is_active) VALUES 
('admin', '$2y$10$8WxmVFxS5mZ.Rq.Vl0XUOuLp9m5BmHRWH6tM3wFOlWUyNLFIFzJlK', 'admin@maca.edu', 'admin', 1);

-- Insert sample announcements
INSERT INTO announcements (title, content, is_active, created_at) VALUES 
('Registration for Fall Semester Now Open', '<p>Registration for the Fall 2023 semester is now open for all students. Please log in to the student portal to register for your courses.</p><p>Early registration is available for seniors and juniors from June 1-15, and for all other students from June 16-30.</p><p>For assistance with registration, please contact the Registrar\'s Office.</p>', 1, '2023-05-25 10:00:00'),
('Scholarship Applications Due Next Week', '<p>This is a reminder that scholarship applications for the upcoming academic year are due by June 15, 2023.</p><p>All required documents must be submitted by the deadline to be considered for scholarships.</p><p>For more information, please visit the Financial Aid Office or contact scholarships@maca.edu.</p>', 1, '2023-06-08 14:30:00'),
('Campus Closure for Independence Day', '<p>Please note that the campus will be closed on July 4, 2023, in observance of Independence Day.</p><p>All classes and administrative offices will be closed. Regular operations will resume on July 5, 2023.</p><p>We wish everyone a safe and happy holiday!</p>', 1, '2023-06-20 09:15:00');

-- Insert sample news
INSERT INTO news (title, summary, content, is_active, created_at) VALUES 
('MACA Students Win National Competition', 'A team of MACA students has won first place in the National Academic Challenge, bringing home the prestigious award for the first time in the institution\'s history.', '<p>A team of five MACA students has won first place in the National Academic Challenge, bringing home the prestigious award for the first time in the institution\'s history.</p><p>The team, consisting of Sarah Johnson, Michael Chen, Emily Rodriguez, David Kim, and Lisa Patel, competed against 50 other institutions from across the country in a rigorous three-day competition that tested their knowledge in various academic disciplines.</p><p>"We are incredibly proud of our students for this remarkable achievement," said Dr. James Wilson, President of MACA. "Their success is a testament to their hard work, dedication, and the quality of education at MACA."</p><p>The National Academic Challenge is one of the most prestigious competitions for higher education institutions, with a focus on critical thinking, problem-solving, and interdisciplinary knowledge.</p><p>The winning team will be honored at a special ceremony on campus next month.</p>', 1, '2023-05-15 11:45:00'),
('New Career Center Opening Next Month', 'MACA is excited to announce the opening of a state-of-the-art Career Center designed to provide students with comprehensive career guidance and resources.', '<p>MACA is excited to announce the opening of a state-of-the-art Career Center designed to provide students with comprehensive career guidance and resources.</p><p>The new facility, located in the Student Services Building, will offer career counseling, resume and interview workshops, job search assistance, and networking opportunities with industry professionals.</p><p>"Our goal is to ensure that every MACA student is well-prepared for the job market and has the resources they need to succeed in their chosen career," said Maria Gonzalez, Director of Career Services.</p><p>The Career Center will also feature a technology lab with specialized software for career exploration and job searching, as well as private rooms for virtual interviews.</p><p>The grand opening ceremony will take place on July 15, 2023, at 10:00 AM, and all students, faculty, and staff are invited to attend.</p>', 1, '2023-06-10 13:20:00'),
('MACA Launches New Online Learning Platform', 'In response to the growing demand for flexible learning options, MACA has launched a new online learning platform that offers a wide range of courses and programs.', '<p>In response to the growing demand for flexible learning options, MACA has launched a new online learning platform that offers a wide range of courses and programs.</p><p>The platform, called MACA Online, features interactive learning materials, video lectures, discussion forums, and virtual collaboration tools designed to provide an engaging and effective learning experience.</p><p>"We recognize that many students need flexibility in their education due to work, family, or other commitments," said Dr. Sarah Thompson, Vice President of Academic Affairs. "MACA Online allows us to meet those needs while maintaining the high academic standards that MACA is known for."</p><p>The initial offering includes 50 courses across various disciplines, with plans to expand the catalog in the coming months. Students can enroll in individual courses or complete entire programs online.</p><p>For more information about MACA Online, visit the Academic Affairs Office or check the MACA website.</p>', 1, '2023-06-18 09:30:00');

-- Insert default team members
INSERT INTO team_members (name, position, bio, is_active, display_order) VALUES 
('Dr. Sarah Johnson', 'Founder & Director', 'With over 20 years of experience in education, Dr. Johnson founded MACA to help students navigate their educational journey.', 1, 1),
('Prof. Michael Chen', 'Academic Advisor', 'Prof. Chen specializes in career development and helps students align their education with their career goals.', 1, 2),
('Emily Rodriguez', 'Career Counselor', 'Emily has helped hundreds of students find internships and job opportunities in their desired fields.', 1, 3),
('David Kim', 'Online Learning Director', 'David oversees our online learning platform and ensures students have access to quality education from anywhere.', 1, 4);

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_name', 'MACA - Education, Majors and Careers'),
('site_description', 'MACA provides comprehensive educational resources, career guidance, and internship opportunities.');
