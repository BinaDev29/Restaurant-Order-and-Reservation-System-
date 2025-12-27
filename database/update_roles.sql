-- Update roles to distinguish between Waiter and Chef
ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff', 'waiter', 'chef', 'customer') DEFAULT 'customer';

-- Ensure we have a way to track order update times for polling
-- (orders table already has created_at, let's ensure updated_at exists or relies on status changes)
-- Adding updated_at to orders if not exists is good practice, but for now we might rely on state.

-- Let's stick to the Role update for now.
