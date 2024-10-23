<?php
// Database configuration
$host = 'mysushidatabase.cf00s6s4mw3w.eu-north-1.rds.amazonaws.com'; // Your database host
$dbname = 'database-1'; // Your database name
$username = 'admin'; // Your database username
$password = 'MySushiDataBase'; // Your database password (update if needed)

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CSS for styling
echo '<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .message { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
</style>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    // Check if the email exists
    if ($result->num_rows > 0) {
        // Fetch the hashed password
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            echo "<div class='message success'><h2>Login Successful!</h2></div>";
            // You can also redirect to another page or set session variables here
        } else {
            echo "<div class='message error'><h2>Invalid email or password.</h2></div>";
        }
    } else {
        echo "<div class='message error'><h2>Invalid email or password.</h2></div>";
    }

    // Close statement
    $stmt->close();
} else {
    echo "<div class='message error'><h2>Invalid request method.</h2></div>";
}

// Close the database connection
$conn->close();
?>
