<?php
/**
 * php/admin-logs.php - View download logs
 */

$logFile = '../logs/download_errors.log';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Logs - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .log-container {
            max-height: 600px;
            overflow-y: auto;
            margin: 20px 0;
            border: 1px solid var(--text-secondary);
            padding: 15px;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .log-entry {
            padding: 8px;
            margin: 3px 0;
            border-left: 2px solid var(--text-secondary);
            background: rgba(0, 255, 0, 0.03);
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: var(--text-white);
            word-wrap: break-word;
        }
        .log-entry:hover {
            background: rgba(0, 255, 0, 0.08);
        }
        .log-success {
            border-left-color: #00ff00;
        }
        .log-error {
            border-left-color: #ff0000;
            color: #ffaaaa;
        }
        .log-warning {
            border-left-color: #ffaa00;
            color: #ffddaa;
        }
        .refresh-btn {
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid var(--text-color);
            color: var(--text-color);
            text-decoration: none;
            text-transform: uppercase;
            margin: 10px 5px;
            transition: all 0.3s ease;
        }
        .refresh-btn:hover {
            background-color: var(--text-color);
            color: var(--bg-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <pre class="ascii-art">
 ███▄ ▄███▓ ██▓ ██▓ ██▀███   ▄▄▄      ▄▄▄       ██ ▄█▀
▓██▒▀█▀ ██▒▓██▒▓██▒▓██ ▒ ██▒▒████▄   ▒████▄     ██▄█▒ 
▓██    ▓██░▒██▒▒██▒▓██ ░▄█ ▒▒██  ▀█▄ ▒██  ▀█▄  ▓███▄░ 
▒██    ▒██ ░██░░██░▒██▀▀█▄  ░██▄▄▄▄██░██▄▄▄▄██ ▓██ █▄ 
▒██▒   ░██▒░██░░██░░██▓ ▒██▒ ▓█   ▓██▒▓█   ▓██▒▒██▒ █▄
░ ▒░   ░  ░░▓  ░▓  ░ ▒▓ ░▒▓░ ▒▒   ▓▒█░▒▒   ▓▒█░▒ ▒▒ ▓▒
░  ░      ░ ▒ ░ ▒ ░  ░▒ ░ ▒░  ▒   ▒▒ ░ ▒   ▒▒ ░░ ░▒ ▒░
░      ░    ▒ ░ ▒ ░  ░░   ░   ░   ▒    ░   ▒   ░ ░░ ░ 
       ░    ░   ░     ░           ░  ░     ░  ░░  ░   
        </pre>

        <div class="admin-container">
            <h1 style="color: var(--text-color); text-align: center;">📋 DOWNLOAD LOGS</h1>
            <p class="prompt">root@miiraak:~$ tail -f logs/download_errors.log</p>

            <div class="log-container">
                <?php
                if (file_exists($logFile)) {
                    $logs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $logs = array_reverse($logs);
                    
                    if (count($logs) > 0) {
                        foreach (array_slice($logs, 0, 200) as $line) {
                            $class = 'log-entry';
                            
                            if (stripos($line, 'successfully') !== false || stripos($line, 'tracked') !== false) {
                                $class .= ' log-success';
                            } elseif (stripos($line, 'error') !== false || stripos($line, 'failed') !== false) {
                                $class .= ' log-error';
                            } elseif (stripos($line, 'warning') !== false || stripos($line, 'attempt') !== false) {
                                $class .= ' log-warning';
                            }
                            
                            echo '<div class="' . $class . '">' .  htmlspecialchars($line) . '</div>';
                        }
                    } else {
                        echo '<p style="color: var(--text-secondary);">No logs available yet.</p>';
                    }
                } else {
                    echo '<p style="color: var(--text-secondary);">Log file not found.  It will be created on first download.</p>';
                }
                ?>
            </div>

            <!-- Actions -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="admin-logs.php" class="refresh-btn">🔄 Refresh</a>
                <a href="admin-stats.php" class="refresh-btn">📊 View Stats</a>
                <a href="/index.html" class="refresh-btn">🏠 Home</a>
            </div>

            <p class="prompt">root@miiraak:~$ # Last viewed: <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>

    <footer>
        <p>© 2025 Miiraak | <a href="https://github.com/Miiraak">GitHub</a></p>
    </footer>
</body>
</html>