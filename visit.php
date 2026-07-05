<?php
// 存储文件
$countFile = __DIR__ . '/visit_count.txt';
$logFile = __DIR__ . '/ip_log.txt';

// 获取访客真实IP
function getRealIp() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
$ip = getRealIp();
$time = date('Y-m-d H:i:s');

// 初始化计数文件
if (!file_exists($countFile)) {
    file_put_contents($countFile, '0');
}
$count = (int)file_get_contents($countFile);
$count++;
file_put_contents($countFile, (string)$count);

// 写入IP日志（每条：时间 | IP）
$logLine = "{$time} | {$ip}" . PHP_EOL;
file_put_contents($logFile, $logLine, FILE_APPEND);

// 返回前端总访问数字
header("Content-Type: application/json;charset=utf-8");
echo json_encode([
    "count" => $count
]);
exit;
?>