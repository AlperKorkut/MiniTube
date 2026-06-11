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

$user_id = $_GET['user_id'];

$user_sql = "SELECT * FROM Users WHERE user_id = '$user_id';";

$user_result = $conn->query($user_sql);

$user = $user_result->fetch_assoc();

?>

<!DOCTYPE html>

<html>

<head>
    <title>
        Hello, <?php echo $user['full_name']; ?>!
    </title>
</head>

<body>

    <h1>
        Hello, <?php echo $user['full_name']; ?>!
    </h1>

    <div style="display:flex; gap:40px;">

    <div style="width:70%;">




<?php
$videos_sql = "SELECT Videos.*, Channels.name AS channel_name, Channels.channel_image, Users.country FROM Videos JOIN Channels ON Videos.channel_id = Channels.channel_id JOIN Users ON Channels.owner_id = Users.user_id JOIN Subscriptions ON Channels.channel_id = Subscriptions.channel_id WHERE Subscriptions.subscriber_id = '$user_id' ORDER BY uploaded_at DESC LIMIT 10;";

$videos_result = $conn->query($videos_sql);

?>

<h2>Subscribed Channel Videos</h2>

<?php

while($video = $videos_result->fetch_assoc()) {

?>

<div>

    <h3>

    <a href="watch.php?video_id=<?php echo $video['video_id']; ?>&user_id=<?php echo $user_id; ?>">

        <?php echo $video['title']; ?>

    </a>

    </h3>

    <p>
        <?php echo $video['description']; ?>
    </p>

    <a href="channel.php?channel_id=<?php echo $video['channel_id']; ?>&user_id=<?php echo $user_id; ?>">

        <img
            src="<?php echo $video['channel_image']; ?>"
            width="120"
        >

    </a>
    
    <p>

            Channel:

            <a href="channel.php?channel_id=<?php echo $video['channel_id']; ?>&user_id=<?php echo $user_id; ?>">

                <?php echo $video['channel_name']; ?>

            </a>

        </p>

            <p>
                Country:
                <?php echo $video['country']; ?>
            </p>

    <?php

    $upload_date = new DateTime($video['uploaded_at']);

    $today = new DateTime();

    $days_ago = $today->diff($upload_date)->days;

    ?>

    <p>
        Uploaded:
        <?php echo $days_ago; ?> days ago
    </p>

    <p>
        Views:
        <?php echo $video['view_count']; ?>
    </p>

</div>

<hr>


<?php

}
?>


</div>

<div style="width:30%;">
        <?php
        
        $channels_sql =  "SELECT Channels.*, COUNT(Subscriptions.subscription_id) AS subscriber_count FROM Channels LEFT JOIN Subscriptions ON Channels.channel_id = Subscriptions.channel_id GROUP BY Channels.channel_id ORDER BY subscriber_count DESC LIMIT 5;";

        $channels_result = $conn->query($channels_sql);

        ?>

        <h2>Popular Channels</h2>

        <?php

        while($channel = $channels_result->fetch_assoc()) {

        ?>

        <div>

            <h3>
                <a href="channel.php?channel_id=<?php echo $channel['channel_id']; ?>&user_id=<?php echo $user_id; ?>">

                    <?php echo $channel['name']; ?>

                </a>
            </h3>

            <p>
                <?php echo $channel['description']; ?>
            </p>
        
            <p>
                Category:
                <?php echo $channel['category']; ?>
            </p>

            <p>
                Subscribers:
                <?php echo $channel['subscriber_count']; ?>
            </p>

        </div>

        <hr>


        <?php

        }

        ?>
        <h2>User Information</h2>

<p>
    <strong>Username:</strong>
    <?php echo $user['username']; ?>
</p>

<p>
    <strong>Full Name:</strong>
    <?php echo $user['full_name']; ?>
</p>

<p>
    <strong>Country:</strong>
    <?php echo $user['country']; ?>
</p>


<p>
    <strong>Join Date:</strong>
    <?php echo $user['joined_on']; ?>
</p>


<p>
    <strong>Bio:</strong>
    <?php echo $user['bio']; ?>
</p>

<hr>

    </div>

</div>

</body>

</html>