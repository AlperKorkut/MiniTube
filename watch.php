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

$video_id = $_GET['video_id'];

$user_id = $_GET['user_id'];

$update_views_sql = "UPDATE Videos SET view_count = view_count + 1 WHERE video_id = '$video_id';";

$conn->query($update_views_sql);

$video_sql = "SELECT Videos.*, Channels.name AS channel_name, Channels.channel_image, Users.country, CASE WHEN view_count >= 1000 THEN 'Popular' WHEN view_count >= 100 THEN 'Trending' ELSE 'New' END AS popularity_badge FROM Videos JOIN Channels ON Videos.channel_id = Channels.channel_id JOIN Users ON Channels.owner_id = Users.user_id WHERE video_id = '$video_id';";

$video_result = $conn->query($video_sql);

$video = $video_result->fetch_assoc();

if (isset($_POST['add_comment'])) {

    $comment_body = $_POST['comment_body'];

    $insert_comment_sql = "INSERT INTO Comments(video_id, user_id, parent_comment_id, body, posted_at) VALUES('$video_id', '$user_id', NULL, '$comment_body', NOW());";

    $conn->query($insert_comment_sql);

    header("Location: watch.php?video_id=$video_id&user_id=$user_id");

    exit();
}

?>



<!DOCTYPE html>

<html>

<head>
    <title>Watch Video</title>
</head>

<body>

<h1>
    <?php echo $video['title']; ?>
</h1>

<p>
    <?php echo $video['description']; ?>
</p>

<p>
    Views:
    <?php echo $video['view_count']; ?>
</p>

<p>
    Likes:
    <?php echo $video['like_count']; ?>
</p>

<p>
    Country:
    <?php echo $video['country']; ?>
</p>

<p>
    Duration:
    <?php echo gmdate("i:s", $video['duration_seconds']); ?>
</p>


<p>
    Uploaded:
    <?php echo $video['uploaded_at']; ?>
</p>

<p>
    Badge:
    <?php echo $video['popularity_badge']; ?>
</p>

<p>

    Channel:

    <a href="channel.php?channel_id=<?php echo $video['channel_id']; ?>&user_id=<?php echo $user_id; ?>">

        <?php echo $video['channel_name']; ?>

    </a>

</p>

<a href="channel.php?channel_id=<?php echo $video['channel_id']; ?>&user_id=<?php echo $user_id; ?>">

    <img
        src="<?php echo $video['channel_image']; ?>"
        width="150"
    >

</a>

<a href="<?php echo $video['url']; ?>" target="_blank">
    Open YouTube Video
</a>

<hr>

<?php
$comments_sql = "SELECT Comments.*, Users.username FROM Comments JOIN Users ON Comments.user_id = Users.user_id WHERE video_id = '$video_id' AND parent_comment_id IS NULL ORDER BY posted_at DESC; ";

$comments_result = $conn->query($comments_sql);

?>

<h2>Comments</h2>


<form method="post">

    <textarea
        name="comment_body"
        rows="4"
        cols="50"
        required
    ></textarea>

    <br><br>

    <button type="submit" name="add_comment">
        Add Comment
    </button>

</form>

<hr>

<?php

while($comment = $comments_result->fetch_assoc()) {

?>

<div>

    <h4>
        <?php echo $comment['username']; ?>
    </h4>

    <p>
        <?php echo $comment['body']; ?>
    </p>

</div>
<hr>

<?php

$comment_id = $comment['comment_id'];

$reply_sql = "SELECT Comments.*, Users.username FROM Comments JOIN Users ON Comments.user_id = Users.user_id WHERE parent_comment_id = '$comment_id';";

$reply_result = $conn->query($reply_sql);

while($reply = $reply_result->fetch_assoc()) {

?>



<div style="margin-left:40px;">

    <h5>
        Reply from:
        <?php echo $reply['username']; ?>
    </h5>

    <p>
        <?php echo $reply['body']; ?>
    </p>

</div>


<?php

}

}

?>

</body>

</html>