# Miiraak.ch

A terminal-styled personal website showcasing software projects, documentation, and file downloads.

## Features

- **Terminal-Inspired Design**: Hacker/terminal aesthetic with green-on-black color scheme
- **Download System**: Secure file download tracking with rate limiting
- **Statistics Dashboard**: Real-time download analytics and product metrics
- **Documentation Pages**: Detailed product information and usage guides
- **Responsive Design**: Mobile-friendly layout
- **Easter Eggs**: Console commands for interactive features

## Project Structure

```
Miiraak.ch/
├── index.html          # Homepage with navigation
├── docs.html           # Documentation listing page
├── downloads.html      # Downloads listing page
├── css/
│   └── style.css       # Main stylesheet
├── js/
│   ├── main.js         # Main JavaScript with console features
│   └── stats.js        # Statistics loading and display
├── php/
│   ├── download.php    # Secure download handler with tracking
│   ├── admin-stats.php # Admin statistics dashboard
│   └── admin-logs.php  # Admin log viewer
├── html/
│   ├── 402.html        # Page 401
│   ├── 403.html        # Page 403
│   └── 404.html        # Page 404
├── docs/
│   └── AccountTester.html # Product detail pages
├── data/
│   └── stats.json      # Download statistics (writable)
├── files/              # Direct file access directory
└── logs/               # Log files directory (auto-created)
```

## Setup

### Prerequisites

- Web server with PHP 7.4+ support
- Write permissions for `data/stats.json` and `logs/` directory

### Installation

1. Clone or upload files to your web server
2. Set permissions:
   ```bash
   chmod 666 data/stats.json
   chmod 777 logs/
   ```
3. Configure `.htaccess` files for security
4. Test permissions with `php/test-permissions.php`
5. **Delete** `php/test-permissions.php` after testing

### Configuration

Edit `php/download.php` to configure:
- `$allowedFiles`: Whitelist of downloadable files
- `$allowedExtensions`: Permitted file extensions
- `$maxDownloadsPerMinute`: Rate limiting threshold

## Security Features

- **Whitelist-based downloads**: Only explicitly allowed files can be downloaded
- **Rate limiting**: Prevents download abuse (configurable)
- **Path traversal protection**: Validates file paths
- **Input sanitization**: All user inputs are validated
- **Admin area protection**: .htaccess authentication for admin pages
- **MIME type validation**: Prevents serving malicious files
- **File locking**: Prevents race conditions in stats updates

## Statistics

The system tracks:
- Download counts per product
- GitHub stars
- Product ratings
- Last update dates

Statistics are stored in `data/stats.json` and displayed on:
- Individual product pages
- Documentation listing page
- Admin dashboard (`php/admin-stats.php`)

## Customization

### Adding New Products

1. Add file to `files/` directory
2. Add entry to `$allowedFiles` in `php/download.php`
3. Add entry to `data/stats.json`:
   ```json
   "ProductName": {
     "downloads": 0,
     "rating": 0.0,
     "github_stars": 0,
     "last_updated": "YYYY-MM-DD"
   }
   ```
4. Create documentation page in `docs/` based on template
5. Add card to `docs.html` and entry to `downloads.html`

### Color Scheme

CSS variables in `css/style.css`:
```css
--bg-color: #0a0a0a;
--text-color: #00ff00;
--text-secondary: #00aa00;
```

## Console Commands

Open browser console and type:
- `help` - Show available commands
- `about` - About information
- `files` - Direct file access info
- `clear` or `Ctrl+K` - Clear console

## License

© 2025 Miiraak | All Rights Reserved

## 🔗 Links

- GitHub: [github.com/Miiraak](https://github.com/Miiraak)
- Website: [miiraak.ch](https://miiraak.ch)
