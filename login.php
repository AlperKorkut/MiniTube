//  Alper Korkut
//  20230702083

<?php

$servername = "localhost";
$username = "root";
$password = "mysql";
$database = "Alper_Korkut";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username_input = $_POST['username'];
$password_input = $_POST['password'];

$sql = "SELECT * FROM Users WHERE username = '$username_input' AND password = '$password_input';";

$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    $user_id = $user['user_id'];

    header("Location: feed.php?user_id=$user_id");

    exit();

} else {

    echo "Invalid username or password.";

}

?>