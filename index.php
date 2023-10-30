<!DOCTYPE html>
<html>
<head>
    <title>用户信息</title>
</head>
<body>
    <form method="get" action="">
        <label for="id">输入ID：</label>
        <input type="text" id="id" name="id" required>
        <input type="submit" value="提交">
    </form>

    <?php
    require_once 'db.php';
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $userInfoUrl = "https://bodian.eu.org/userapi?id=" . $id;
        $userInfoJson = file_get_contents($userInfoUrl);
        $userInfo = json_decode($userInfoJson, true);

        if ($userInfo) {
            $nickname = $userInfo['data']['userInfo']['nickname'];
            $headImg = $userInfo['data']['userInfo']['headImg'];

            echo "<p>你好，{$nickname}</p>";
            echo "<img src=\"{$headImg}\" width=\"80\" height=\"80\" alt=\"头像\">";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("数据库连接失败: " . $conn->connect_error);
            }

            $checkQuery = "SELECT * FROM bodian WHERE bdid = '$id'";
            $checkResult = $conn->query($checkQuery);

            if ($checkResult->num_rows == 0) {
                // ID does not exist in the database, insert new record
                $insertQuery = "INSERT INTO bodian (bdid, sctime, add_time) VALUES ('$id', NOW(), NOW())";
                if ($conn->query($insertQuery) === TRUE) {
                    echo "<p>添加时间：" . date("Y-m-d H:i:s") . "</p>";
                } else {
                    echo "<p>无法插入记录到数据库</p>";
                }
            } else {
                // ID already exists in the database
                $row = $checkResult->fetch_assoc();
                $addTime = $row['add_time'];
                $scTime = $row['sctime'];
                echo "<p>添加时间：{$addTime}</p>";
                echo "<p>上次签到：{$scTime}</p>";
            }

            // Check if sctime is today
            $sctimeQuery = "SELECT DATE(sctime) AS sctime_date FROM bodian WHERE bdid = '$id'";
            $sctimeResult = $conn->query($sctimeQuery);

            if ($sctimeResult->num_rows > 0) {
                $row = $sctimeResult->fetch_assoc();
                $sctimeDate = $row['sctime_date'];

                if ($sctimeDate == date("Y-m-d")) {
                    // sctime is today, no need to continue
                    // sctime is not today, continue with the second GET request
                    $qdUrl = "https://bodian.eu.org/qdapi?id=" . $id;
                    $qdJson = file_get_contents($qdUrl);
                    $qdInfo = json_decode($qdJson, true);

                    if ($qdInfo && $qdInfo['code'] === 200) {
                        echo "<p>签到成功</p>";
                        echo "<p>程序会在0点自动签到，不要监控本接口！</p>";
                        echo "<p>程序会在0点自动签到，不要监控本接口！</p>";
                        echo "<p>程序会在0点自动签到，不要监控本接口！</p>";
                        echo "<p>进群加VX：Br00wn(备注波点)</p>";
                        echo "<p>--------By求仙镇</p>";
                    } else {
                        echo "<p>签到失败</p>";
                    }
                }
            }

            $conn->close();
        } else {
            echo "<p>如果你看到这条消息，说明我cloudflare的接口被撸爆了，请联系我反馈：Br00wn</p>";
        }
    }
    ?>
</body>
</html>
