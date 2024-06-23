<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $playlist_name = $_POST['playlist_name'];

    $sql = "INSERT INTO playlists (name) VALUES ('$playlist_name')";

    if ($conn->query($sql) === TRUE) {
        echo "播放列表 '$playlist_name' 建立成功！";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

