<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $playlist_id = $_GET['id'];

    $sql_delete_songs = "DELETE FROM playlist_songs WHERE playlist_id = ?";
    $stmt_delete_songs = $conn->prepare($sql_delete_songs);
    $stmt_delete_songs->bind_param("i", $playlist_id);

    if ($stmt_delete_songs->execute()) {
        $sql_delete_playlist = "DELETE FROM playlists WHERE id = ?";
        $stmt_delete_playlist = $conn->prepare($sql_delete_playlist);
        $stmt_delete_playlist->bind_param("i", $playlist_id);

        if ($stmt_delete_playlist->execute()) {
            echo "播放列表刪除成功！";
        } else {
            echo "Error deleting playlist: " . $stmt_delete_playlist->error;
        }
    } else {
        echo "Error deleting playlist songs: " . $stmt_delete_songs->error;
    }

    $stmt_delete_songs->close();
    $stmt_delete_playlist->close();
}

$conn->close();
?>


