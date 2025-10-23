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

function initChatPolling() {
    const container = document.getElementById('chat-messages');
    if (!container) {
        return;
    }
    let lastId = 0;
    container.querySelectorAll('[data-message]').forEach(el => {
        const id = parseInt(el.getAttribute('data-message'), 10);
        if (id > lastId) {
            lastId = id;
        }
    });
    const threadId = container.getAttribute('data-thread');
    if (!threadId) {
        return;
    }

    setInterval(() => {
        fetch(`/chat/poll?thread_id=${threadId}&after_id=${lastId}`)
            .then(resp => resp.ok ? resp.json() : {messages: []})
            .then(data => {
                (data.messages || []).forEach(message => {
                    lastId = Math.max(lastId, parseInt(message.id, 10));
                    const wrapper = document.createElement('div');
                    wrapper.className = 'mb-3';
                    wrapper.setAttribute('data-message', message.id);
                    wrapper.innerHTML = `<div class="fw-semibold">Utilizador #${message.sender_id} <small class="text-muted">${message.created_at}</small></div>` +
                        `<p class="mb-0">${message.body.replace(/</g, '&lt;')}</p>`;
                    container.appendChild(wrapper);
                    container.scrollTop = container.scrollHeight;
                });
            })
            .catch(() => {});
    }, 5000);
}

document.addEventListener('DOMContentLoaded', initChatPolling);
