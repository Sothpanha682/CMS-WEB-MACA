-- Create roadshow_videos table
CREATE TABLE roadshow_videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roadshow_id INT NOT NULL,
    video_url VARCHAR(255) NOT NULL,
    video_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (roadshow_id) REFERENCES roadshow(id) ON DELETE CASCADE
);

-- Remove video_url column from roadshow table
ALTER TABLE roadshow DROP COLUMN video_url;
