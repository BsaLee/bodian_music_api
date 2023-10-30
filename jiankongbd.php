<?php

// 数据库连接信息
require_once 'db.php';

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 获取当前日期和时间
$currentDateTime = date("Y-m-d H:i:s");

// 查询不是今天的记录
$sql = "SELECT bdid, sctime FROM bodian WHERE DATE(sctime) <> CURDATE()";
$result = $conn->query($sql);

// 执行 GET 请求
while ($row = $result->fetch_assoc()) {
    $id = $row["bdid"];
    $sctime = $row["sctime"];

    // 打印符合条件的id和sctime
    echo "符合条件的记录：id = $id, sctime = $sctime\n";

    $url = "https://bodian.eu.org/qdapi?id=$id";

    // 发起 GET 请求
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // 解析返回的 JSON
    $responseData = json_decode($response, true);

    // 打印返回的 JSON
    echo "GET请求返回的JSON: $response\n";

    // 提取 code
    $code = $responseData["code"];

    // 更新数据库中的记录
    $updateSql = "UPDATE bodian SET sctime = '$currentDateTime', code = '$code' WHERE bdid = '$id'";
    $conn->query($updateSql);
}

// 关闭数据库连接
$conn->close();

?>
