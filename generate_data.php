//  Alper Korkut
//  20230702083

<?php

function readLines($filename) {
    return file($filename, FILE_IGNORE_NEW_LINES);
}

$first_names = readLines("data/first_names.txt");
$last_names = readLines("data/last_names.txt");
$countries = readLines("data/countries.txt");
$video_titles = readLines("data/video_titles.txt");
$video_descriptions = readLines("data/video_descriptions.txt");
$categories = readLines("data/categories.txt");
$video_links = readLines("data/video_links.txt");


if (file_exists("seed.sql")) {
    unlink("seed.sql");
}

$output = fopen("seed.sql", "w");

if (!$output) {
    die("Could not create seed.sql");
}


for ($i = 1; $i <= 100; $i++) {

    $first = $first_names[array_rand($first_names)];
    $last = $last_names[array_rand($last_names)];

    $full_name = "$first $last";

    $username = strtolower($first . $i);

    $password = "123456";

    $email = $username . "@gmail.com";

    $country = $countries[array_rand($countries)];

    $joined_on = date("Y-m-d", strtotime("-" . rand(0, 365) . " days"));

    $bio = "Hello, I am $full_name";

    $image = "https://picsum.photos/200?random=$i";

    $sql = "
    INSERT INTO Users
    (username, password, user_image, full_name, email, country, joined_on, bio)

    VALUES

    (
        '$username',
        '$password',
        '$image',
        '$full_name',
        '$email',
        '$country',
        '$joined_on',
        '$bio'
    );

    ";

    fwrite($output, $sql);
}


for ($i = 1; $i <= 50; $i++) {

    $channel_name = "Channel$i";

    $description = "Welcome to $channel_name";

    $category = $categories[array_rand($categories)];

    $created_on = date("Y-m-d");

    $image = "https://picsum.photos/300?random=channel$i";

    $sql = "

    INSERT INTO Channels
    (owner_id, channel_image, name, description, created_on, category)

    VALUES

    (
        '$i',
        '$image',
        '$channel_name',
        '$description',
        '$created_on',
        '$category'
    );

    ";

    fwrite($output, $sql);
}


for ($i = 1; $i <= 200; $i++) {

    $channel_id = rand(1, 50);

    $title = $video_titles[array_rand($video_titles)];

    $description = $video_descriptions[array_rand($video_descriptions)];

    $url = $video_links[array_rand($video_links)];

    $duration = rand(60, 1200);

    $uploaded_at = date("Y-m-d H:i:s", strtotime("-" . rand(0, 365) . " days"));

    $view_count = rand(100, 100000);

    $like_count = rand(
        intval($view_count * 0.02),
        intval($view_count * 0.15)
    );

    $sql = "

    INSERT INTO Videos
    (channel_id, title, description, url, duration_seconds, uploaded_at, view_count, like_count)

    VALUES

    (
        '$channel_id',
        '$title',
        '$description',
        '$url',
        '$duration',
        '$uploaded_at',
        '$view_count',
        '$like_count'
    );

    ";

    fwrite($output, $sql);
}


$subscriptions = [];

for ($i = 1; $i <= 120; $i++) {

    $subscriber_id = rand(1, 100);
    $channel_id = rand(1, 50);

    $key = $subscriber_id . "-" . $channel_id;

    if (in_array($key, $subscriptions)) {
        $i--;
        continue;
    }

    $subscriptions[] = $key;

    $subscribed_at = date(
        "Y-m-d H:i:s",
        strtotime("-" . rand(0, 365) . " days")
    );

    $sql = "INSERT INTO Subscriptions(subscriber_id, channel_id, subscribed_at) VALUES('$subscriber_id', '$channel_id', '$subscribed_at');";

    fwrite($output, $sql);
}



// First 130 normal comments

for ($i = 1; $i <= 130; $i++) {

    $video_id = rand(1, 200);

    $user_id = rand(1, 100);

    $body = "This is comment $i";

    $posted_at = date("Y-m-d H:i:s", strtotime("-" . rand(0, 365) . " days"));

    $sql = "

    INSERT INTO Comments
    (video_id, user_id, parent_comment_id, body, posted_at)

    VALUES

    (
        '$video_id',
        '$user_id',
        NULL,
        '$body',
        '$posted_at'
    );

    ";

    fwrite($output, $sql);
}


// Then 20 reply comments

for ($i = 131; $i <= 150; $i++) {

    $video_id = rand(1, 200);

    $user_id = rand(1, 100);

    $parent_comment_id = rand(1, 130);

    $body = "This is a reply comment $i";

    $posted_at = date("Y-m-d H:i:s", strtotime("-" . rand(0, 365) . " days"));

    $sql = "INSERT INTO Comments(video_id, user_id, parent_comment_id, body, posted_at) VALUES('$video_id', '$user_id', '$parent_comment_id', '$body', '$posted_at');";

    fwrite($output, $sql);
}


fclose($output);
?>