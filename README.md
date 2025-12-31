# golden  - Restaurant Order & Reservation System

A premium, full-stack PHP web application for restaurant management, featuring online ordering, table reservations, and a comprehensive dashboard for both staff and customers.

## 🏗️ Project Structure
Based on the Ethco Coders architecture:
```
/
├── app/                    # Core Logic
│   ├── api/                # Endpoints (reservations.php)
│   ├── controllers/        # Auth, Dashboard, Order Controllers
│   ├── models/             # Database Models (User, Menu, Order)
│   ├── config.php          # Database Configuration
│   └── functions.php       # Helpers
├── dashboard/              # Admin/User Dashboard
├── assets/                 # Public Assets (CSS, JS)
├── database/               # SQL Schema & Seeds
├── uploads/                # Image Storage
└── Public Pages (index.php, menu.php, login.php...)
```

## 🚀 Setup Instructions

1.  **Database Setup**:
    *   Create a MySQL database named `restaurant_db` (or allow the app to create it).
    *   Import `database/schema.sql` to create tables.
    *   (Optional) Run `php database/seed.php` to populate dummy data.

2.  **Configuration**:
    *   Edit `app/config.php` if your database credentials differ from:
        *   User: `root`
        *   Password: `""` (Empty)

3.  **Run Locally**:
    *   Use XAMPP/Laragon and point to this directory.
    *   Or run: `php -S localhost:8000`

## 🔑 Default Credentials
*   **Admin Email**: `admin@restaurant.com`
*   **Password**: `admin123`

## ✨ Features
*   **Authentication**: Secure Login/Register/Logout.
*   **Menu**: Categorized menu with images.
*   **Orders**: Basket management (UI placeholder) and Order Status tracking.
*   **Reservations**: Online table booking system.
*   **Dashboard**:
    *   **Admin**: View revenue, manage orders (Update Status), view stats.
    *   **User**: View past orders, reservation status.
*   **Responsive Design**: Premium Dark/Gold theme using Bootstrap 5.

## 🇪🇹 Cultural Notes
Designed with architectural similarities to modern Ethiopian web apps, featuring high-contrast premium aesthetics suitable for international or local fine dining.
