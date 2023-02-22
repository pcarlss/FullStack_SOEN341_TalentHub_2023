    <?php

// Start the session to store any error messages
session_start();

// Check if the form has been submitted
if (isset($_POST['name'])) {

    // Get the form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    // Validate the form data
    if (empty($name) || empty($username) || empty($password1) || empty($password2)) {
        $_SESSION['error'] = "All fields are required.";
    } elseif ($password1 !== $password2) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        // Check if the username already exists
        $conn = mysqli_connect('localhost', 'root', '', 'users');
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['error'] = "Username already taken.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password1, PASSWORD_DEFAULT);

            // Insert the data into the database
            $query = "INSERT INTO users (name, username, password) VALUES ('$name', '$username', '$hashed_password')";
            if (mysqli_query($conn, $query)) {
                $_SESSION['success'] = "User registered successfully.";
                header("Location: success_sign_up.php");
                exit;
            } else {
                $_SESSION['error'] = "Error: " . mysqli_error($conn);
            }
        }

        // Close the database connection
        mysqli_close($conn);
    }

    // Redirect back to the registration page
    header("Location: error.php");
    exit;
}
?>
