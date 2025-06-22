-- Add site_settings table for storing hero image and about banner
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Add team_members table
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    bio TEXT NOT NULL,
    image_path VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default team members
INSERT INTO team_members (name, position, bio, is_active, display_order) VALUES 
('Dr. Sarah Johnson', 'Founder & Director', 'With over 20 years of experience in education, Dr. Johnson founded MACA to help students navigate their educational journey.', 1, 1),
('Prof. Michael Chen', 'Academic Advisor', 'Prof. Chen specializes in career development and helps students align their education with their career goals.', 1, 2),
('Emily Rodriguez', 'Career Counselor', 'Emily has helped hundreds of students find internships and job opportunities in their desired fields.', 1, 3),
('David Kim', 'Online Learning Director', 'David oversees our online learning platform and ensures students have access to quality education from anywhere.', 1, 4);
