// Terminal effects and interactions for Miiraak.ch

document.addEventListener('DOMContentLoaded', function() {
    // Console welcome message
    console.log('%c ███▄ ▄███▓ ██▓ ██▓ ██▀███   ▄▄▄      ▄▄▄       ██ ▄█▀', 'color: #00ff00; font-family: monospace;');
    console.log('%c Miiraak.ch - Terminal Mode Active', 'color: #00ff00; font-family: monospace;');
    console.log('%c Type "help" for available commands', 'color: #00aa00; font-family: monospace;');
    
    // Easter egg: Console commands
    window.help = function() {
        console.log('%c Available commands:', 'color: #00ff00; font-weight: bold;');
        console.log('%c - help: Show this message', 'color: #00aa00;');
        console.log('%c - about: About Miiraak', 'color: #00aa00;');
        console.log('%c - clear: Clear console', 'color: #00aa00;');
        console.log('%c - files: List available files', 'color: #00aa00;');
    };
    
    window.about = function() {
        console.log('%c Bunch of scraps here, lot from my stupid poping brain ideas...', 'color: #ffffff; font-style: italic;');
    };
    
    window.files = function() {
        console.log('%c Direct file access: https://miiraak.ch/files/<filename>', 'color: #00ff00;');
    };
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to clear (easter egg)
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            console.clear();
            console.log('%c Console cleared. Type "help" for commands.', 'color: #00ff00;');
            e.preventDefault();
        }
    });
});