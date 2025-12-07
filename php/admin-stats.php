<?php
/**
 * php/admin-stats.php - View download statistics
 */

$statsFile = '../data/stats.json'; // ✅ CHANGÉ

if (!file_exists($statsFile)) {
    die('Stats file not found');
}

$stats = json_decode(file_get_contents($statsFile), true);
$totalDownloads = 0;
$totalStars = 0;
$productCount = count($stats);

foreach ($stats as $data) {
    $totalDownloads += $data['downloads'] ?? 0;
    $totalStars += $data['github_stars'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Statistics - Admin</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- ✅ CHANGÉ -->
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        . summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .summary-card {
            padding: 20px;
            border: 2px solid var(--text-color);
            background-color: rgba(0, 255, 0, 0.05);
            text-align: center;
        }
        .summary-number {
            color: var(--text-color);
            font-size: 36px;
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }
        .summary-label {
            color: var(--text-secondary);
            font-size: 14px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid var(--text-secondary);
            padding: 12px;
            text-align: left;
            color: var(--text-white);
        }
        th {
            background-color: rgba(0, 255, 0, 0.1);
            color: var(--text-color);
            font-weight: bold;
            text-transform: uppercase;
        }
        tr:hover {
            background-color: rgba(0, 255, 0, 0.05);
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
            <h1 style="color: var(--text-color); text-align: center;">📊 DOWNLOAD STATISTICS</h1>
            <p class="prompt">root@miiraak:~$ cat data/stats.json | jq</p>

            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card">
                    <span class="summary-number"><?php echo number_format($totalDownloads); ?></span>
                    <span class="summary-label">Total Downloads</span>
                </div>
                <div class="summary-card">
                    <span class="summary-number"><?php echo $productCount; ?></span>
                    <span class="summary-label">Products</span>
                </div>
                <div class="summary-card">
                    <span class="summary-number"><?php echo $totalStars; ?></span>
                    <span class="summary-label">Total Stars</span>
                </div>
                <div class="summary-card">
                    <span class="summary-number"><?php echo number_format($totalDownloads / max($productCount, 1), 0); ?></span>
                    <span class="summary-label">Avg per Product</span>
                </div>
            </div>

            <!-- Products Table -->
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Downloads</th>
                        <th>Rating</th>
                        <th>GitHub Stars</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats as $id => $data): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($id); ? ></strong></td>
                        <td style="color: var(--text-color); font-weight: bold;"><?php echo number_format($data['downloads'] ?? 0); ?></td>
                        <td>⭐ <?php echo $data['rating'] ?? 'N/A'; ?></td>
                        <td>⭐ <?php echo $data['github_stars'] ?? 0; ?></td>
                        <td><?php echo $data['last_updated'] ?? 'N/A'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Actions -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="admin-stats.php" class="refresh-btn">🔄 Refresh</a>
                <a href="admin-logs.php" class="refresh-btn">📋 View Logs</a>
                <a href="../index.html" class="refresh-btn">🏠 Home</a> <!-- ✅ CHANGÉ -->
            </div>

            <p class="prompt">root@miiraak:~$ # Last updated: <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>

    <footer>
        <p>© 2025 Miiraak | <a href="https://github.com/Miiraak">GitHub</a></p>
    </footer>
</body>
</html>