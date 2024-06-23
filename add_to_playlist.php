<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連接失敗 " . $conn->connect_error);
}

// 处理表单提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['song_id']) && isset($_POST['playlist_id'])) {
        $song_id = $_POST['song_id'];
        $playlist_id = $_POST['playlist_id'];

        $sql_check_playlist = "SELECT * FROM playlists WHERE id = '$playlist_id'";
        $result_check_playlist = $conn->query($sql_check_playlist);

        if ($result_check_playlist->num_rows == 0) {
            echo "錯誤：指定的播放列表不存在";
            exit; 
        }

        $sql_song_info = "SELECT title, artist, album, file_path FROM songs WHERE id = '$song_id'";
        $result_song_info = $conn->query($sql_song_info);

        if ($result_song_info->num_rows > 0) {
            $song_info = $result_song_info->fetch_assoc();
            $title = $song_info['title'];
            $artist = $song_info['artist'];
            $album = $song_info['album'];
            $file_path = $song_info['file_path'];

            $sql = "INSERT INTO playlist_songs (playlist_id, song_id, title, artist, album, file_path) 
                    VALUES ('$playlist_id', '$song_id', '$title', '$artist', '$album','$file_path')";

            if ($conn->query($sql) === TRUE) {
                echo '<script>alert("歌曲成功添加到播放列表");</script>';
                echo '<script>window.history.go(-1);</script>'; 
            } else {
                echo '<script>alert("错误: ' . $sql . '\n' . $conn->error . '");</script>';
                echo '<script>window.history.go(-1);</script>'; 
            }

        } else {
            echo "找不到歌曲";
        }
    } else {
        echo "請選擇一個播放列表";
    }
}

$conn->close();
exit();
?>
