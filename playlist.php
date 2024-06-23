<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>播放列表</title>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "music_library";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("連接失敗: " . $conn->connect_error);
    }

    if (isset($_GET['id'])) {
        $playlist_id = $_GET['id'];

        $sql_playlist_name = "SELECT name FROM playlists WHERE id = '$playlist_id'";
        $result_playlist_name = $conn->query($sql_playlist_name);

        if ($result_playlist_name->num_rows > 0) {
            $playlist_name_row = $result_playlist_name->fetch_assoc();
            $playlist_name = htmlspecialchars($playlist_name_row['name']);
        } else {
            echo "錯誤：找不到播放列表";
            exit;
        }
    } else {
        echo "未選擇播放列表";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_song_id'])) {
        $delete_song_id = $_POST['delete_song_id'];
        $sql_delete_song = "DELETE FROM playlist_songs WHERE playlist_id = '$playlist_id' AND song_id = '$delete_song_id'";

        if ($conn->query($sql_delete_song) === TRUE) {
            echo "歌曲成功從播放列表中刪除";
        } else {
            echo "錯誤: " . $conn->error;
        }
    }

    $sql_playlist_songs = "SELECT ps.song_id, s.title, s.artist, s.album, s.file_path FROM playlist_songs ps JOIN songs s ON ps.song_id = s.id WHERE ps.playlist_id = '$playlist_id'";
    $result_playlist_songs = $conn->query($sql_playlist_songs);

    ?>
    
    <h1><?php echo $playlist_name; ?></h1>
    <table border="1">
        <tr>
            <th>標題</th>
            <th>演出者</th>
            <th>專輯</th>
            <th>操作</th>
        </tr>
        <?php

        if ($result_playlist_songs->num_rows > 0) {
            while ($row = $result_playlist_songs->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["artist"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["album"]) . "</td>";
                echo "<td>";
                echo "<form method='post' action='playlist.php?id=$playlist_id' style='display:inline;'>";
                echo "<input type='hidden' name='delete_song_id' value='" . $row["song_id"] . "'>";
                echo "<audio controls>
                        <source src='" . htmlspecialchars($row['file_path']) . "' type='audio/mpeg'>
                        您的瀏覽器不支援 audio 元素。
                      </audio>";
                echo "<input type='submit' value='刪除'>";
                echo "</td>";
                echo "</tr>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>播放列表中没有歌曲</td></tr>";
        }

        $conn->close();
        ?>
    </table>

    <audio id="audioPlayer" controls style="display:none;">
        <source id="audioSource" src="" type="audio/mpeg">
        您的瀏覽器不支援 audio 元素。
    </audio>

    <script>
        function playSong(songPath) {
            var audioPlayer = document.getElementById('audioPlayer');
            var audioSource = document.getElementById('audioSource');
            audioSource.src = songPath;
            audioPlayer.style.display = 'block';
            audioPlayer.load();
            audioPlayer.play();
        }
    </script>

    <a href="javascript:void(0);" onclick="window.history.go(-1);">
        返回主頁
    </a>

</body>
</html>


