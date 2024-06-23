<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-image: url('圖片/leoneed_background.png'); /* 确保路径正确 */
            background-size: cover; /* 调整背景图片大小 */
            background-position: center;
            background-attachment: fixed; /* 固定背景图片 */
        }
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* 半透明的白色背景 */
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: rgba(242, 242, 242, 0.8); /* 半透明的背景 */
            color: #333;
        }
        table td {
            background-color: rgba(255, 255, 255, 0.8); /* 半透明的背景 */
        }
        form {
            margin: 0;
        }
        audio {
            width: 100%;
            margin-top: 5px;
        }
        ul {
            list-style-type: none;
            padding: 0;
            background-color: rgba(255, 255, 255, 0.8); /* 半透明的背景 */
            padding: 20px; /* 添加一些内边距 */
            border-radius: 5px; /* 添加圆角效果 */
            margin-top: 20px;
        }
        ul li {
            margin-bottom: 10px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .playlist-section {
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8); 
            padding: 20px;
            border-radius: 5px; 
        }
        .create-playlist-form {
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.8); 
            padding: 20px;
            border-radius: 5px; 
        }
    </style>
</head>
<body>
    <h1 style="color:chartreuse">歌曲列表</h1>
    <table>
        <tr>
            <th>標題</th>
            <th>演出者</th>
            <th>專輯</th>
            <th>操作</th>
        </tr>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "music_library";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("連接失敗: " . $conn->connect_error);
        }

        $sql_songs = "SELECT id, title, artist, album, file_path FROM songs WHERE artist='Leo/need'";
        $result_songs = $conn->query($sql_songs);

        if ($result_songs->num_rows > 0) {
            while($row = $result_songs->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["artist"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["album"]) . "</td>";
                echo "<td>";
                echo "<form action='add_to_playlist.php' method='post'>";
                echo "<input type='hidden' name='song_id' value='" . $row["id"] . "'>";
                echo "<select name='playlist_id'>";

                // 查询播放列表
                $sql_playlists = "SELECT id, name FROM playlists";
                $result_playlists = $conn->query($sql_playlists);

                if ($result_playlists->num_rows > 0) {
                    while ($playlist = $result_playlists->fetch_assoc()) {
                        echo "<option value='" . $playlist["id"] . "'>" . htmlspecialchars($playlist["name"]) . "</option>";
                    }
                } else {
                    echo "<option value=''>沒有播放列表</option>";
                }

                echo "</select>";
                echo "<input type='submit' value='加入'>";
                echo "<audio controls>
                        <source src='" . htmlspecialchars($row['file_path']) . "' type='audio/mpeg'>
                        您的瀏覽器不支援 audio 元素。
                      </audio>";
                echo "</td>";
                echo "</tr>";
                echo "</form>";
                echo "</td>";
                echo "<td>";
            }
        } else {
            echo "<tr><td colspan='4'>沒有歌曲</td></tr>";
        }

        $conn->close();
        ?>
    </table>

    <div class="playlist-section">
        <h1>播放列表</h1>
        <ul>
            <?php
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("連接失敗: " . $conn->connect_error);
            }

            $sql_playlists = "SELECT id, name FROM playlists";
            $result_playlists = $conn->query($sql_playlists);

            if ($result_playlists->num_rows > 0) {
                while($row = $result_playlists->fetch_assoc()) {
                    echo "<li>";
                    echo "<a href='playlist.php?id=" . $row["id"] . "'>" . htmlspecialchars($row["name"]) . "</a>";
                    echo " | ";
                    echo "<a href='delete_playlist.php?id=" . $row["id"] . "'>刪除</a>";
                    echo "</li>";
                }
            } else {
                echo "<li>沒有播放列表</li>";
            }

            $conn->close();
            ?>
        </ul>
    </div>

    <div class="create-playlist-form">
        <h2>建立新播放列表</h2>
        <form action="create_playlist.php" method="post">
            <label for="playlist_name">播放列表名稱:</label>
            <input type="text" id="playlist_name" name="playlist_name">
            <input type="submit" value="建立">
        </form>
    </div>

</body>
</html>
