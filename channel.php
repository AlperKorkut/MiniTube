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

$channel_id = $_GET['channel_id'];

$user_id = $_GET['user_id'];

$channel_sql = "SELECT Channels.*, Users.full_name, Users.country FROM Channels JOIN Users ON Channels.owner_id = Users.user_id WHERE channel_id = '$channel_id';";

$channel_result = $conn->query($channel_sql);

$channel = $channel_result->fetch_assoc();

$subscriber_sql = "SELECT COUNT(*) AS subscriber_count FROM Subscriptions WHERE channel_id = '$channel_id';";

$subscriber_result = $conn->query($subscriber_sql);

$subscriber_data = $subscriber_result->fetch_assoc();

$subscriber_count = $subscriber_data['subscriber_count'];

$videos_sql = "SELECT * FROM Videos WHERE channel_id = '$channel_id' ORDER BY uploaded_at DESC;";

$videos_result = $conn->query($videos_sql);

$check_subscription_sql = "SELECT * FROM Subscriptions WHERE subscriber_id = '$user_id' AND channel_id = '$channel_id';";

$check_result = $conn->query($check_subscription_sql);

$is_subscribed = $check_result->num_rows > 0;


if (isset($_POST['subscribe'])) {

    $subscribe_sql = "INSERT INTO Subscriptions(subscriber_id, channel_id, subscribed_at) VALUES('$user_id', '$channel_id', NOW());";

    $conn->query($subscribe_sql);

    header("Location: channel.php?channel_id=$channel_id&user_id=$user_id");

    exit();
}

if (isset($_POST['unsubscribe'])) {

    $unsubscribe_sql = "DELETE FROM Subscriptions WHERE subscriber_id = '$user_id' AND channel_id = '$channel_id';";

    $conn->query($unsubscribe_sql);

    header("Location: channel.php?channel_id=$channel_id&user_id=$user_id");

    exit();
}

?>


<!DOCTYPE html>

<html>

<head>
    <title>Channel Page</title>
</head>

<body>

<h1>
    <?php echo $channel['name']; ?>
</h1>

<img
    src="<?php echo $channel['channel_image']; ?>"
    width="200"
>

<p>
    <?php

    if (empty($channel['description'])) {

        echo "(no description)";

    } else {

        echo $channel['description'];

    }

    ?>
</p>

<p>
    Owner:
    <?php echo $channel['full_name']; ?>
</p>

<p>
    Country:
    <?php echo $channel['country']; ?>
</p>

<p>
    Subscribers:
    <?php echo $subscriber_count; ?>
</p>

<?php if ($channel['owner_id'] != $user_id) { ?>

    <?php if ($is_subscribed) { ?>

<form method="post">

    <button type="submit" name="unsubscribe">
        Unsubscribe
    </button>

</form>

<?php } else { ?>

<form method="post">

    <button type="submit" name="subscribe">
        Subscribe
    </button>

</form>

<?php } ?>

<?php } ?>

<hr>

<h2>Channel Videos</h2>

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
        Duration:
        <?php echo gmdate("i:s", $video['duration_seconds']); ?>
    </p>

    <p>
        Uploaded:
        <?php echo $video['uploaded_at']; ?>
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

</body>

</html>