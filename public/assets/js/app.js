window.addEventListener('DOMContentLoaded', () => {
    const bell = document.querySelector('[href="/notificacoes"]');
    const apiBase = '/api/notifications';
    const pollEvery = (Number(document.body.dataset.pollInterval) || 30) * 1000;
    const preference = localStorage.getItem('dark-mode') ?? 'system';
    document.documentElement.classList.toggle('theme-dark', preference === 'dark');

    async function fetchUnread() {
        try {
            const response = await fetch(`${apiBase}/unread_count`);
            if (!response.ok) return;
            const data = await response.json();
            if (data.unread > 0) {
                bell?.classList.add('has-unread');
                bell?.setAttribute('data-unread', data.unread);
            } else {
                bell?.classList.remove('has-unread');
                bell?.removeAttribute('data-unread');
            }
        } catch (error) {
            console.warn('Falha ao obter notificações:', error);
        }
    }

    if (bell) {
        fetchUnread();
        setInterval(fetchUnread, pollEvery);
    }

    document.addEventListener('keydown', (event) => {
        if (event.shiftKey && event.key.toLowerCase() === 'd') {
            const current = localStorage.getItem('dark-mode') ?? 'system';
            const next = current === 'system' ? 'dark' : current === 'dark' ? 'light' : 'system';
            localStorage.setItem('dark-mode', next);
            document.documentElement.classList.toggle('theme-dark', next === 'dark');
        }
    });
});
