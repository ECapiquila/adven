# Sabbath Connect (PHP MVC)

Implementação inicial da plataforma Sabbath Connect com uma stack PHP 8.2, MySQL 8 e Apache.

## Requisitos
- PHP 8.2+ com extensões `pdo_mysql` e `openssl`
- MySQL 8+
- Apache 2.4 com `mod_rewrite`

## Instalação rápida
1. Clone o repositório e copie o conteúdo para o servidor.
2. Crie a base de dados `sabbath_connect` e execute `database/install.sql`.
3. Aplique os extras opcionais com `database/migrations_addons.sql`.
4. Ajuste credenciais em `config/database.php` ou via variáveis de ambiente (`DB_HOST`, `DB_DATABASE`, etc.).
5. Configure o DocumentRoot para a pasta `public/` e active o `mod_rewrite`.
6. Certifique-se de servir o site via HTTPS (o `.htaccess` já força redirect).

## Estrutura
```
app/                # Controllers, models e serviços
config/             # Configurações da aplicação e base de dados
core/               # Router, View, Model, helpers
helpers/            # Utilitários globais (CSRF)
public/             # Front controller, assets, service worker
resources/views/    # Layouts e páginas Blade-like
routes/web.php      # Definição de rotas
storage/            # Pasta reservada para caches/sessões
```

## Rotas principais
- `/` landing page
- `/login`, `/register`
- `/feed`
- `/louvores`, `/louvores/novo`, `/gerenciar/louvores`
- `/oracoes`, `/oracoes/novo`, `/oracoes/{id}`, `/gerenciar/oracoes`
- `/notificacoes`
- API: `/api/notifications/unread_count`, `/api/notifications/latest`
- PWA: `/manifest.webmanifest`, `/sw.js`

## PWA & Notificações
- Manifesto gerado dinamicamente por `PwaController@manifest`
- Service Worker (`public/sw.js`) pronto para Web Push (ping)
- Chaves VAPID definidas em `config/app.php`
- Navbar com badge para notificações e polling (30s por default)

## Segurança
- Sessões com `SameSite=Strict` e regeneração a cada login/logout
- Middleware simples para CSRF (`helpers/csrf.php`)
- Password hashing com `password_hash` (ver seeds em `migrations_addons.sql`)

## Próximos passos
- Implementar lógica completa de autenticação e CRUDs
- Integrar RBAC com base nas tabelas `roles` e `permissions`
- Ligar serviços de notificações, fila de e-mail e webhooks
- Completar os módulos de Carteira, Investidas, Cursos e Doações

## Desenvolvimento
```bash
php -S 127.0.0.1:8000 -t public
```

A app é modular e as páginas actuais funcionam como “mock-ups” navegáveis para orientar o desenvolvimento futuro.
