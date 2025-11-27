<?php
include 'config.php';

// 获取原始请求路径
$request_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 构建目标URL
$target_url = "http://{$TARGET_DOMAIN}{$request_path}";

// 对URL进行安全编码
$encoded_url = htmlspecialchars($target_url, ENT_QUOTES, 'UTF-8');
$delay_ms = $REDIRECT_DELAY * 1000;

// 输出跳转页面
echo '<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">  <!-- 禁止搜索引擎索引 -->
    
    <!-- 双重跳转机制 -->
    <meta http-equiv="refresh" content="'.$REDIRECT_DELAY.'; url='.$encoded_url.'"> <!-- 为禁用JS的用户提供HTML跳转 -->
    
    <title>正在打开新地址</title>
    <style>
        /* 响应式设计 - 适用于所有设备 */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
        }
        
        .header {
            background: #4a6fa5;
            color: white;
            padding: 25px 20px;
        }
        
        .header h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .content {
            padding: 40px 25px;
        }
        
        .message {
            font-size: 1.2rem;
            line-height: 1.6;
            color: #333;
            margin-bottom: 30px;
        }
        
        .countdown-number {
            display: inline-block;
            background: #f0f5ff;
            color: #4a6fa5;
            font-weight: bold;
            font-size: 1.8rem;
            padding: 10px 25px;
            border-radius: 12px;
            margin: 15px 0;
            min-width: 80px;
        }
        
        .btn {
            display: inline-block;
            background: #4a6fa5;
            color: white;
            text-decoration: none;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 20px 0 15px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background: #3a5a8a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .footer {
            margin-top: 15px;
            font-size: 0.9rem;
            color: #777;
        }
        
        /* 手机设备优化 */
        @media (max-width: 600px) {
            .header {
                padding: 20px 15px;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .message {
                font-size: 1.1rem;
                margin-bottom: 25px;
            }
            
            .countdown-number {
                font-size: 1.6rem;
                padding: 8px 20px;
                min-width: 70px;
            }
            
            .btn {
                width: 100%;
                padding: 16px;
                font-size: 1rem;
                margin: 15px 0 10px;
            }
        }
        
        /* 动画效果 */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>正在打开新地址</h1>
        </div>
        
        <div class="content">
            <div class="countdown-number" id="countdown">'.$REDIRECT_DELAY.'</div>
            
            <p class="message">页面将在 <span id="countdown-text">'.$REDIRECT_DELAY.'</span> 秒后自动跳转</p>
            
            <a href="'.$encoded_url.'" class="btn pulse">立即跳转</a>
            
            <p class="footer">如果跳转未自动进行，请点击上方按钮</p>
        </div>
    </div>

    <!-- 禁用JS时仅保留meta刷新跳转，不显示任何提示 -->
    <noscript></noscript>

    <script>
        // 首选跳转方式
        setTimeout(function() {
            window.location.href = "'.$encoded_url.'";
        }, '.$delay_ms.');
        
        // 添加倒计时显示
        var seconds = '.$REDIRECT_DELAY.';
        var countdownEl = document.getElementById("countdown");
        var countdownText = document.getElementById("countdown-text");
        
        if (countdownEl && countdownText) {
            var countdownInterval = setInterval(function() {
                seconds--;
                countdownEl.textContent = seconds;
                countdownText.textContent = seconds;
                if(seconds <= 0) clearInterval(countdownInterval);
            }, 1000);
        }
    </script>
</body>
</html>';
?>