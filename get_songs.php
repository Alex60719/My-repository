<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

$sql = "SELECT id, title, artist, album, file_path FROM songs";
$result = $conn->query($sql);

$songs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $songs[] = $row;
    }
}

$conn->close();

echo json_encode($songs);
?>
