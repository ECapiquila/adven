document.addEventListener('alpine:init', () => {
    Alpine.store('ui', {
        darkMode: localStorage.getItem('theme') === 'dark',
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', this.darkMode ? 'dark' : 'light');
        }
    });

    document.documentElement.setAttribute('data-theme', Alpine.store('ui').darkMode ? 'dark' : 'light');
});

window.toggleTheme = function () {
    if (Alpine.store('ui')) {
        Alpine.store('ui').toggleTheme();
    }
};
