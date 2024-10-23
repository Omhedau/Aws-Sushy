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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='message error'><h2>Email already exists. Please use a different email.</h2></div>";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<div class='message success'><h2>Signup Successful! You can now <a href='login.php'>login</a>.</h2></div>";
        } else {
            echo "<div class='message error'><h2>Error: Could not complete signup. Please try again.</h2></div>";
        }
    }

    // Close statement
    $stmt->close();
} else {
    echo "<div class='message error'><h2>Invalid request method.</h2></div>";
}

// Close the database connection
$conn->close();
?>
