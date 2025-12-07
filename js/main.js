// Terminal effects and interactions for Miiraak.ch

document.addEventListener('DOMContentLoaded', function() {
    // Console welcome message
    console.log('%c ███▄ ▄███▓ ██▓ ██▓ ██▀███   ▄▄▄      ▄▄▄       ██ ▄█▀', 'color: #00ff00; font-family: monospace;');
    console.log('%c Miiraak.ch - Terminal Mode Active', 'color: #00ff00; font-family: monospace;');
    console.log('%c Type "help" for available commands', 'color: #00aa00; font-family: monospace;');
    
    // Easter egg: Console commands
    window.help = function() {
        console. log('%c Available commands:', 'color: #00ff00; font-weight: bold;');
        console. log('%c - help: Show this message', 'color: #00aa00;');
        console.log('%c - about: About Miiraak', 'color: #00aa00;');
        console.log('%c - clear: Clear console', 'color: #00aa00;');
        console.log('%c - files: List available files', 'color: #00aa00;');
    };
    
    window.about = function() {
        console.log('%c Bunch of scraps here, lot from my stupid poping brain ideas... ', 'color: #ffffff; font-style: italic;');
    };
    
    window.files = function() {
        console. log('%c Direct file access: https://miiraak.ch/files/<filename>', 'color: #00ff00;');
    };
    
    // Optional: Typing effect for description (uncomment to enable)
    /*
    const descriptionElement = document.querySelector('.description');
    if (descriptionElement) {
        const text = descriptionElement.textContent;
        descriptionElement.textContent = '';
        let i = 0;
        
        function typeWriter() {
            if (i < text.length) {
                descriptionElement.textContent += text. charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        }
        
        typeWriter();
    }
    */
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to clear (easter egg)
        if ((e.ctrlKey || e. metaKey) && e.key === 'k') {
            console.clear();
            console.log('%c Console cleared.  Type "help" for commands.', 'color: #00ff00;');
            e.preventDefault();
        }
    });
    
    // Matrix rain effect (optional, commented out - uncomment to enable)
    /*
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    document.body.appendChild(canvas);
    
    canvas.style.position = 'fixed';
    canvas.style.top = '0';
    canvas.style.left = '0';
    canvas.style.width = '100%';
    canvas.style.height = '100%';
    canvas.style.zIndex = '-1';
    canvas.style.opacity = '0.1';
    
    canvas.width = window.innerWidth;
    canvas.height = window. innerHeight;
    
    const matrix = "MIIRAAK01";
    const fontSize = 16;
    const columns = canvas.width / fontSize;
    const drops = Array(Math.floor(columns)).fill(1);
    
    function drawMatrix() {
        ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
        ctx. fillRect(0, 0, canvas.width, canvas.height);
        
        ctx.fillStyle = '#00ff00';
        ctx.font = fontSize + 'px monospace';
        
        for (let i = 0; i < drops.length; i++) {
            const text = matrix[Math.floor(Math.random() * matrix.length)];
            ctx.fillText(text, i * fontSize, drops[i] * fontSize);
            
            if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
                drops[i] = 0;
            }
            drops[i]++;
        }
    }
    
    setInterval(drawMatrix, 35);
    
    window.addEventListener('resize', function() {
        canvas.width = window. innerWidth;
        canvas.height = window.innerHeight;
    });
    */
});