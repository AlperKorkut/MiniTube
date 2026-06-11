//  Alper Korkut
//  20230702083

<?php
// This is a reference PHP code for creating and filling the database.
// Add new lines and queries based on your given project.

$servername = "localhost";
$username = "root";
$password = "mysql";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
$conn->query("DROP DATABASE IF EXISTS Alper_Korkut");

$sql = "CREATE DATABASE IF NOT EXISTS Alper_Korkut";

// For checking given sql query is executed correctly
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . mysqli_connect_error());
}

//Select database
mysqli_select_db($conn, 'Alper_Korkut');

// Create tables
$sql = "CREATE TABLE IF NOT EXISTS Users(user_id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(150) UNIQUE NOT NULL, password VARCHAR(150) NOT NULL, user_image TEXT, full_name VARCHAR(150) NOT NULL, email VARCHAR(150) UNIQUE NOT NULL, country VARCHAR(150), joined_on DATE, bio TEXT);
CREATE TABLE IF NOT EXISTS Channels(channel_id INT AUTO_INCREMENT PRIMARY KEY, owner_id INT UNIQUE, channel_image TEXT, name VARCHAR(150) NOT NULL, description TEXT, created_on DATE, category VARCHAR(150), FOREIGN KEY (owner_id) REFERENCES Users(user_id));
CREATE TABLE IF NOT EXISTS Videos(video_id INT AUTO_INCREMENT PRIMARY KEY, channel_id INT, title VARCHAR(150) NOT NULL, description TEXT, url TEXT, duration_seconds INT, uploaded_at DATETIME, view_count INT DEFAULT 0, like_count INT DEFAULT 0, FOREIGN KEY (channel_id) REFERENCES Channels(channel_id));
CREATE TABLE IF NOT EXISTS Subscriptions(subscription_id INT AUTO_INCREMENT PRIMARY KEY, subscriber_id INT, channel_id INT, subscribed_at DATETIME, UNIQUE KEY(subscriber_id, channel_id), FOREIGN KEY (subscriber_id) REFERENCES Users(user_id), FOREIGN KEY (channel_id) REFERENCES Channels(channel_id));
CREATE TABLE IF NOT EXISTS Comments(comment_id INT AUTO_INCREMENT PRIMARY KEY, video_id INT, user_id INT, parent_comment_id INT NULL, body TEXT, posted_at DATETIME, FOREIGN KEY (video_id) REFERENCES Videos(video_id), FOREIGN KEY (user_id) REFERENCES Users(user_id), FOREIGN KEY (parent_comment_id) REFERENCES Comments(comment_id));";

// For checking given sql query is executed correctly
if($conn->multi_query($sql) === FALSE) {
    die("Error creating table: " . mysqli_connect_error());
}

do {

} while ($conn->next_result());


// Fill tables
// ...

require_once("generate_data.php");

$sql_file = file_get_contents("seed.sql");

if ($conn->multi_query($sql_file)) {

    do {

    } while ($conn->next_result());


} else {

    die("Error importing seed data: " . $conn->error);

}

header("Location: login.html");
exit();

?>