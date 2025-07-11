<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_records";

// Connect to MySQL server (no database yet)
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
if (!$conn->query($sql)) {
    die("Error creating database: " . $conn->error);
}
$conn->select_db($dbname);

// Create table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    age INT(3) NOT NULL,
    status TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($sql)) {
    die("Error creating table: " . $conn->error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $age = intval($_POST['age']);
    
    $sql = "INSERT INTO users (name, age) VALUES ('$name', $age)";
    
    if ($conn->query($sql)) {
        echo "<script>alert('Record added successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Handle status toggle via AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggle_status'])) {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);
    
    $sql = "UPDATE users SET status = $status WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status";
    }
    exit;
}

// Handle user edit via AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $age = intval($_POST['age']);
    
    $sql = "UPDATE users SET name = '$name', age = $age WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo "User updated successfully";
    } else {
        echo "Error updating user";
    }
    exit;
}

// Handle user delete via AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $id = intval($_POST['id']);
    
    $sql = "DELETE FROM users WHERE id = $id";
    
    if ($conn->query($sql)) {
        echo "User deleted successfully";
    } else {
        echo "Error deleting user";
    }
    exit;
}

// Fetch all records
$sql = "SELECT * FROM users ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Records</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #0f0f0f;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(108, 92, 231, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 184, 148, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(255, 107, 107, 0.05) 0%, transparent 50%);
            background-attachment: fixed;
            color: #ffffff;
            line-height: 1.6;
            padding: 30px;
            min-height: 100vh;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #1a1a1a;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            border: 1px solid #2d2d2d;
            overflow: hidden;
            position: relative;
            z-index: 2;
        }
        
        .header {
            background: linear-gradient(135deg, #6c5ce7 0%, #2d3436 100%);
            padding: 40px;
            text-align: center;
            border-bottom: 1px solid #3d3d3d;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '👥';
            position: absolute;
            top: 10px;
            left: 30px;
            font-size: 24px;
            opacity: 0.3;
        }
        
        .header::after {
            content: '📊';
            position: absolute;
            top: 10px;
            right: 30px;
            font-size: 24px;
            opacity: 0.3;
        }
        
        .header h1 {
            font-size: 2.8rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }
        
        .header p {
            color: #b0b0b0;
            font-size: 1.1rem;
            font-weight: 300;
        }
        
        .content {
            padding: 50px;
        }
        
        .form-section {
            background: #242424;
            padding: 35px;
            border-radius: 12px;
            margin-bottom: 40px;
            border: 1px solid #3d3d3d;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        
        .form-section::before {
            content: '✏️';
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 20px;
            opacity: 0.4;
        }
        
        .form-group {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .form-group input {
            flex: 1;
            padding: 14px 18px;
            background: #1a1a1a;
            border: 1px solid #3d3d3d;
            border-radius: 8px;
            color: #ffffff;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #6c5ce7;
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.15);
            background: #1f1f1f;
        }
        
        .form-group input::placeholder {
            color: #777;
        }
        
        .form-group button {
            padding: 14px 28px;
            background: linear-gradient(135deg, #6c5ce7 0%, #5a4fcf 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3);
            position: relative;
        }
        
        .form-group button::before {
            content: '➕';
            margin-right: 8px;
        }
        
        .form-group button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(108, 92, 231, 0.4);
        }
        
        .form-group button:active {
            transform: translateY(0);
        }
        
        .table-section {
            background: #242424;
            border-radius: 12px;
            border: 1px solid #3d3d3d;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        
        .table-section::before {
            content: '📋';
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 20px;
            opacity: 0.4;
            z-index: 1;
        }
        
        .table-header {
            background: linear-gradient(135deg, #6c5ce7 0%, #2d3436 100%);
            padding: 25px;
            border-bottom: 1px solid #3d3d3d;
            position: relative;
            z-index: 2;
        }
        
        .table-header h3 {
            color: #ffffff;
            font-size: 1.3rem;
            font-weight: 500;
            margin: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #1f1f1f;
            color: #b0b0b0;
            padding: 18px;
            text-align: left;
            font-weight: 500;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 1px solid #3d3d3d;
        }
        
        td {
            padding: 18px;
            border-bottom: 1px solid #2d2d2d;
            color: #ffffff;
            font-size: 15px;
        }
        
        tr {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        tr:hover {
            background: #2a2a2a;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .status-badge {
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .status-badge.status-0 {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
        }
        
        .status-badge.status-1 {
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 184, 148, 0.3);
        }
        
        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 54px;
            height: 28px;
            margin: 0 10px;
        }
        
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ff6b6b;
            transition: 0.4s;
            border-radius: 28px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        input:checked + .slider {
            background-color: #00b894;
        }
        
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        
        /* Edit and Delete Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #6c5ce7 0%, #5a4fcf 100%);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(108, 92, 231, 0.3);
            position: relative;
        }
        
        .btn-edit::before {
            content: '✏️';
            margin-right: 4px;
        }
        
        .btn-edit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(108, 92, 231, 0.4);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
            position: relative;
        }
        
        .btn-delete::before {
            content: '🗑️';
            margin-right: 4px;
        }
        
        .btn-delete:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
        }
        
        /* Edit Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
        }
        
        .modal-content {
            background-color: #242424;
            margin: 8% auto;
            padding: 40px;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            border: 1px solid #3d3d3d;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .modal-header h3 {
            color: #ffffff;
            font-size: 1.4rem;
            font-weight: 500;
            margin: 0;
            position: relative;
        }
        
        .modal-header h3::before {
            content: '👤';
            margin-right: 10px;
        }
        
        .close {
            color: #b0b0b0;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
            line-height: 1;
        }
        
        .close:hover {
            color: #ffffff;
        }
        
        .modal-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .modal-form input {
            padding: 14px 18px;
            background: #1a1a1a;
            border: 1px solid #3d3d3d;
            border-radius: 8px;
            color: #ffffff;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .modal-form input:focus {
            outline: none;
            border-color: #6c5ce7;
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.15);
            background: #1f1f1f;
        }
        
        .modal-form button {
            padding: 14px 28px;
            background: linear-gradient(135deg, #6c5ce7 0%, #5a4fcf 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3);
            position: relative;
        }
        
        .modal-form button::before {
            content: '💾';
            margin-right: 8px;
        }
        
        .modal-form button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(108, 92, 231, 0.4);
        }
        
        .no-records {
            text-align: center;
            padding: 60px 20px;
            color: #666;
            font-size: 16px;
        }
        
        .no-records::before {
            content: "📋";
            font-size: 48px;
            display: block;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 16px;
        }
        
        .loading::after {
            content: "";
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #3d3d3d;
            border-top: 2px solid #6c5ce7;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 2.2rem;
            }
            
            .content {
                padding: 30px;
            }
            
            .form-section {
                padding: 25px;
            }
            
            .form-group {
                flex-direction: column;
                gap: 15px;
            }
            
            .form-group input,
            .form-group button {
                width: 100%;
            }
            
            .table-section {
                border-radius: 8px;
            }
            
            .table-header {
                padding: 20px;
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 14px 10px;
            }
            
            .action-buttons {
                flex-wrap: wrap;
                gap: 8px;
            }
            
            .btn {
                font-size: 11px;
                padding: 6px 10px;
            }
            
            .toggle-switch {
                width: 48px;
                height: 26px;
            }
            
            .slider:before {
                height: 20px;
                width: 20px;
            }
            
            input:checked + .slider:before {
                transform: translateX(22px);
            }
        }
        
        @media (max-width: 480px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .content {
                padding: 20px;
            }
            
            .form-section {
                padding: 20px;
            }
            
            .modal-content {
                padding: 30px 20px;
                margin: 15% auto;
            }
            
            th, td {
                padding: 12px 8px;
                font-size: 13px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 8px;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
            
            .toggle-switch {
                align-self: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Records</h1>
            <p>Manage your user database</p>
        </div>
        
        <div class="content">
            <div class="form-section">
                <form method="POST" id="userForm">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Enter full name" required>
                        <input type="number" name="age" placeholder="Enter age" required min="1" max="120">
                        <button type="submit">Add User</button>
                    </div>
                </form>
            </div>
            
            <div class="table-section">
                <div class="table-header">
                    <h3>Users List</h3>
                </div>
                <div id="recordsTable">
                    <?php if ($result->num_rows > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo $row['age']; ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $row['status']; ?>">
                                                <?php echo $row['status'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-edit" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['name']); ?>', <?php echo $row['age']; ?>)">
                                                    Edit
                                                </button>
                                                <label class="toggle-switch">
                                                    <input type="checkbox" <?php echo $row['status'] ? 'checked' : ''; ?> 
                                                           onchange="toggleStatus(<?php echo $row['id']; ?>, <?php echo $row['status']; ?>)">
                                                    <span class="slider"></span>
                                                </label>
                                                <button class="btn btn-delete" onclick="deleteUser(<?php echo $row['id']; ?>)">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="no-records">
                            No records found. Add some users above!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit User</h3>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            <form class="modal-form" id="editForm">
                <input type="hidden" id="editUserId" name="id">
                <input type="text" id="editUserName" name="name" placeholder="Enter full name" required>
                <input type="number" id="editUserAge" name="age" placeholder="Enter age" required min="1" max="120">
                <button type="submit">Update User</button>
            </form>
        </div>
    </div>

    <script>
        // Add loading state management
        function showLoading(element) {
            element.style.opacity = '0.6';
            element.style.pointerEvents = 'none';
        }
        
        function hideLoading(element) {
            element.style.opacity = '1';
            element.style.pointerEvents = 'auto';
        }
        
        // Enhanced toggle status function
        function toggleStatus(id, currentStatus) {
            const newStatus = currentStatus ? 0 : 1;
            
            fetch('index.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `toggle_status=1&id=${id}&status=${newStatus}`
            })
            .then(response => response.text())
            .then(data => {
                showNotification('Status updated successfully!', 'success');
                fetchRecords();
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error updating status. Please try again.', 'error');
            });
        }
        
        // Edit Modal Functions
        function openEditModal(id, name, age) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editUserName').value = name;
            document.getElementById('editUserAge').value = age;
            document.getElementById('editModal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Delete User Function
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `delete_user=1&id=${id}`
                })
                .then(response => response.text())
                .then(data => {
                    showNotification('User deleted successfully!', 'success');
                    fetchRecords();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error deleting user. Please try again.', 'error');
                });
            }
        }
        
        // Enhanced fetch records function
        function fetchRecords() {
            const tableContainer = document.getElementById('recordsTable');
            
            // Add loading indicator
            tableContainer.innerHTML = '<div class="loading">Loading records...</div>';
            
            fetch('index.php')
            .then(response => response.text())
            .then(html => {
                // Extract the table section from the response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.getElementById('recordsTable');
                
                if (newTable) {
                    // Add fade-in effect
                    tableContainer.style.opacity = '0';
                    tableContainer.innerHTML = newTable.innerHTML;
                    
                    // Animate fade-in
                    setTimeout(() => {
                        tableContainer.style.transition = 'opacity 0.3s ease';
                        tableContainer.style.opacity = '1';
                    }, 100);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tableContainer.innerHTML = '<div class="no-records">Error loading records. Please refresh the page.</div>';
            });
        }
        
        // Notification system
        function showNotification(message, type = 'success') {
            // Remove existing notifications
            const existing = document.querySelector('.notification');
            if (existing) {
                existing.remove();
            }
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 1000;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
                border: 1px solid rgba(255, 255, 255, 0.1);
            `;
            
            if (type === 'success') {
                notification.style.background = 'linear-gradient(135deg, #00b894 0%, #00a085 100%)';
            } else {
                notification.style.background = 'linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%)';
            }
            
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
        
        // Enhanced form submission
        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Add loading state
            showLoading(submitButton);
            submitButton.textContent = 'Adding User...';
            
            const formData = new FormData(this);
            
            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Show success notification
                showNotification('User added successfully!', 'success');
                
                // Reload the records after submission
                fetchRecords();
                
                // Reset the form
                this.reset();
                
                // Reset button state
                hideLoading(submitButton);
                submitButton.textContent = originalText;
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error notification
                showNotification('Error adding user. Please try again.', 'error');
                
                // Reset button state
                hideLoading(submitButton);
                submitButton.textContent = originalText;
            });
        });
        
        // Edit form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Add loading state
            showLoading(submitButton);
            submitButton.textContent = 'Updating...';
            
            const formData = new FormData(this);
            formData.append('edit_user', '1');
            
            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Show success notification
                showNotification('User updated successfully!', 'success');
                
                // Close modal and reload records
                closeEditModal();
                fetchRecords();
                
                // Reset button state
                hideLoading(submitButton);
                submitButton.textContent = originalText;
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error notification
                showNotification('Error updating user. Please try again.', 'error');
                
                // Reset button state
                hideLoading(submitButton);
                submitButton.textContent = originalText;
            });
        });
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
        
        // Add smooth scrolling and form validation
        document.addEventListener('DOMContentLoaded', function() {
            // Add input animations
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>