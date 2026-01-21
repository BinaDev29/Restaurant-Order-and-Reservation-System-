-- Update roles to distinguish between Waiter and Chef
ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'waiter', 'chef', 'customer') DEFAULT 'customer';

