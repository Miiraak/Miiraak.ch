// stats.js - Load dynamic product statistics

async function loadProductStats(productId) {
    try {
        const response = await fetch('../data/stats.json');
        const data = await response.json();
        const stats = data[productId];

        if (stats) {
            // Update total downloads in meta
            const totalDownloads = document.getElementById('total-downloads');
            if (totalDownloads) {
                totalDownloads.textContent = stats.downloads.toLocaleString();
            }

            // Update downloads stat box
            const statDownloads = document.getElementById('stat-downloads');
            if (statDownloads) {
                if (stats.downloads >= 1000) {
                    statDownloads.textContent = (stats.downloads / 1000).toFixed(1) + 'K';
                } else {
                    statDownloads.textContent = stats.downloads;
                }
            }

            // Update rating
            const statRating = document.getElementById('stat-rating');
            if (statRating) {
                statRating.textContent = '★ ' + stats.rating;
            }

            // Update GitHub stars
            const statStars = document.getElementById('stat-stars');
            if (statStars) {
                statStars.textContent = stats.github_stars;
            }

            // Optional: Update last updated date
            const lastUpdated = document.getElementById('last-updated');
            if (lastUpdated && stats.last_updated) {
                lastUpdated.textContent = stats.last_updated;
            }

            console.log(`✓ Stats loaded for ${productId}`);
        } else {
            console.error(`✗ Product "${productId}" not found in stats.json`);
            showStatsFallback();
        }
    } catch (error) {
        console.error('✗ Error loading stats:', error);
        showStatsFallback();
    }
}

// Show fallback if stats fail to load
function showStatsFallback() {
    const loadingElements = document.querySelectorAll('.loading');
    loadingElements.forEach(el => {
        el.textContent = 'N/A';
        el.style.color = 'var(--text-secondary)';
    });
}

// Copy code to clipboard
function copyCode(button) {
    const codeBlock = button.nextElementSibling;
    const text = codeBlock.textContent;

    navigator.clipboard.writeText(text).then(() => {
        button.textContent = 'COPIED!';
        button.style.backgroundColor = 'rgba(0, 255, 0, 0.3)';
        button.style.color = 'var(--text-color)';

        setTimeout(() => {
            button.textContent = 'COPY';
            button.style.backgroundColor = 'rgba(0, 255, 0, 0.1)';
            button.style.color = 'var(--text-secondary)';
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy:', err);
        button.textContent = 'ERROR';
    });
}