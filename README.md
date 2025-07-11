# User Records Management System

A modern, responsive PHP web application for managing user records with a sleek dark theme interface. 

## 🌟 Features

- **User Management**: Add, edit, and delete user records
- **Status Toggle**: Real-time status updates (Active/Inactive)
- **Responsive Design**: Modern dark theme that works on all devices
- **AJAX Integration**: Seamless updates without page refresh
- **Database Auto-Setup**: Automatically creates database and tables
- **Form Validation**: Client-side and server-side validation
- **Interactive UI**: Smooth animations and hover effects
- **Modern Design**: Beautiful gradient backgrounds and professional styling

## 🛠️ Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Server**: Apache (XAMPP)
- **Styling**: Custom CSS with Google Fonts (Poppins)

## 📋 Prerequisites

Before running this project, make sure you have:

- [XAMPP](https://www.apachefriends.org/index.html) installed
- PHP 7.4 or higher
- MySQL 5.7 or higher

## 🔧 Installation & Setup

### Step 1: Download and Install XAMPP
1. Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Install XAMPP on your system
3. Start Apache and MySQL services from XAMPP Control Panel

### Step 2: Clone the Repository
```bash
git clone https://github.com/yourusername/user-records-management.git
cd user-records-management
```

### Step 3: Move Files to XAMPP Directory
1. Copy all project files to your XAMPP htdocs directory:
   ```
   C:\xampp\htdocs\web_dataBase\
   ```

### Step 4: Database Configuration
The application will automatically create the database and table on first run. The default configuration is:
- **Server**: localhost
- **Username**: root
- **Password**: (empty)
- **Database**: user_records

If you need to modify these settings, edit the database configuration in `index.php`:
```php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_records";
```

### Step 5: Run the Application
1. Start XAMPP services (Apache and MySQL)
2. Open your web browser
3. Navigate to: `http://localhost/web_dataBase/`

## 🗂️ Project Structure

```
web_dataBase/
│
├── index.php          # Main application file
└──README.md          # Project documentation
```

## 🎯 How It Works

### Database Setup (Automatic)
1. **Connection**: The app connects to MySQL server
2. **Database Creation**: Creates `user_records` database if it doesn't exist
3. **Table Creation**: Creates `users` table with the following structure:
   ```sql
   CREATE TABLE users (
       id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(50) NOT NULL,
       age INT(3) NOT NULL,
       status TINYINT(1) DEFAULT 0,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

### Core Functionality

#### 1. Add User
- **Form**: Simple form with name and age fields
- **Validation**: Client-side and server-side validation
- **Process**: Data sanitization and SQL injection prevention
- **Response**: Success notification and table refresh

#### 2. Edit User
- **Modal**: Pop-up modal for editing user details
- **Pre-fill**: Form pre-populated with existing data
- **AJAX**: Updates without page refresh
- **Validation**: Same validation rules as add user

#### 3. Delete User
- **Confirmation**: JavaScript confirmation dialog
- **AJAX**: Soft delete with immediate UI update
- **Notification**: Success/error notifications

#### 4. Toggle Status
- **Switch**: Modern toggle switch for status
- **Real-time**: Instant status updates
- **Visual**: Color-coded status badges (Active/Inactive)

#### 5. Responsive Design
- **Mobile-first**: Optimized for mobile devices
- **Breakpoints**: 768px and 480px breakpoints
- **Adaptive**: Flexible layout and components

## 🔧 Code Structure Explained

### Backend (PHP)
```php
// Database connection and setup
$conn = new mysqli($servername, $username, $password);

// CRUD operations
- Create: INSERT INTO users (name, age) VALUES (...)
- Read: SELECT * FROM users ORDER BY id ASC
- Update: UPDATE users SET ... WHERE id = ...
- Delete: DELETE FROM users WHERE id = ...
```

### Frontend (HTML/CSS/JavaScript)
```html
<!-- Responsive layout with modern design -->
<div class="container">
    <div class="header">...</div>
    <div class="content">
        <div class="form-section">...</div>
        <div class="table-section">...</div>
    </div>
</div>
```

### JavaScript Functions
```javascript
// Core functions
- toggleStatus(): Handle status updates
- openEditModal(): Open edit modal
- deleteUser(): Delete user with confirmation
- fetchRecords(): Refresh table data
- showNotification(): Display success/error messages
```


### Database Schema
To add more fields, modify the table creation SQL and update the form/display logic:
```sql
ALTER TABLE users ADD COLUMN email VARCHAR(100);
```

