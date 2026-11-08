// Theme Utility: Manages Light (Default) and Dark ("Black Mode")
export const getInitialTheme = () => {
    if (typeof window !== 'undefined') {
        const stored = localStorage.getItem('ekrishi_theme');
        if (stored === 'dark' || stored === 'light') {
            return stored;
        }
        // Match cookie if set
        const match = document.cookie.match(new RegExp('(^| )ekrishi_theme=([^;]+)'));
        if (match && (match[2] === 'dark' || match[2] === 'light')) {
            return match[2];
        }
    }
    // "বাই ডিফল হোয়াইট মুড থাকবে" -> Strictly 'light' by default
    return 'light';
};

export const applyTheme = (theme) => {
    if (typeof document !== 'undefined') {
        document.documentElement.setAttribute('data-theme', theme);
        if (theme === 'dark') {
            document.body.classList.add('dark-theme');
        } else {
            document.body.classList.remove('dark-theme');
        }
    }
};

export const saveTheme = (theme) => {
    if (typeof window !== 'undefined') {
        localStorage.setItem('ekrishi_theme', theme);
        document.cookie = `ekrishi_theme=${theme};path=/;max-age=31536000`;
    }
    applyTheme(theme);
};

