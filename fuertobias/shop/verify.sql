ALTER TABLE users
ADD COLUMN token VARCHAR(255) DEFAULT NULL,
ADD COLUMN status ENUM('pending', 'verified') DEFAULT 'pending';
