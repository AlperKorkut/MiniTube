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

$query = $_POST['query'];

echo "<h2>Executed Query</h2>";

echo "<pre>$query</pre>";

$result = $conn->query($query);

if ($result === TRUE) {

    echo "Query executed successfully.";

} elseif ($result instanceof mysqli_result) {

    echo "<table border='1'>";

    echo "<tr>";

    while($field = $result->fetch_field()) {

        echo "<th>" . $field->name . "</th>";

    }

    echo "</tr>";

    $count = 0;

    while(($row = $result->fetch_assoc()) && $count < 10) {

        echo "<tr>";

        foreach($row as $value) {

            echo "<td>$value</td>";

        }

        echo "</tr>";

        $count++;
    }

    echo "</table>";

} else {

    echo "Error: " . $conn->error;

}

?>