# Rede Adventista — Plataforma Social em PHP MVC

Este repositório disponibiliza uma implementação inicial da rede social adventista solicitada, construída em **PHP 8.2+ puro** com arquitectura **MVC customizada** e **MySQL 8**. A solução atende aos requisitos principais: tema inspirado no briefing (#2E5EAA, #5D8233, #D4AF37, #F5F5F5), módulos especializados (Pedidos de Oração, Saúde & Bem-Estar, Lar & Família), navegação estilo Facebook, armazenamento local e documentação em pt-AO.

## Estrutura

```
app/
  Core/        → motor MVC (Router, Application, Request/Response, Container, View, Validator)
  Controllers/ → controladores web (Home, Auth, Feed, Prayer, Health, Family, Error)
  Models/      → modelos PDO (User, PrayerRequest, HealthPost, FamilyThread)
  Services/    → camadas de domínio (FeedService, PrayerService, HealthService, etc.)
  Policies/    → políticas base para RBAC/escopo
config/        → configurações de app, base de dados, tema e flags
public/        → ponto de entrada (`index.php`), assets e `.htaccess`
resources/     → views em PHP (layouts, landing, feed, orações, saúde, família)
routes/web.php → definição das rotas amigáveis
```

## Requisitos

- PHP 8.2+
- MySQL 8
- Extensão `pdo_mysql`
- Servidor HTTP com suporte a `.htaccess` (Apache ou compatível)

## Instalação rápida

1. Clonar o repositório e instalar dependências do SO (não há dependências Composer).
2. Configurar um host virtual apontando para `public/`.
3. Ajustar variáveis de ambiente de base de dados (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
4. Executar os SQL em `database/migrations/001_create_core_tables.sql` numa base de dados vazia.
5. (Opcional) Executar o seeder `database/seeders/GeoSeeder.php` para carregar municípios.
6. Aceder a `http://localhost/` para ver a landing page. Módulos `/feed`, `/oracoes`, `/saude`, `/familia` estão activos.

## Funcionalidades cobertas

- **Layout e tema:** Navbar estilo Facebook com menu global agrupado, modo escuro via Alpine store, paleta coerente com a landing.
- **Autenticação básica:** formulário de login/registro (fluxo de login funcional com verificação de credenciais).
- **Feed inicial:** cartões de comunicados, stories, devocional, eventos.
- **Pedidos de Oração:** listagem, criação e detalhe, com categorias, privacidade e validações.
- **Saúde & Bem-Estar:** feed próprio e formulário de partilha para profissionais verificados.
- **Lar & Família:** hub de conversas, abertura de threads e visualização com mensagens simuladas.
- **Serviços dedicados:** camada de serviços para feed, devocionais, orações, saúde, família, música, chat, carteira, anúncios e mais.
- **Configurações:** paleta em `config/theme.php`, flags em `config/features.php`, timezone `Africa/Luanda`, locale `pt_AO`.
- **Armazenamento local:** `storage/uploads` preparado para `LocalStorageService` com quotas futuras.
- **Migrations/Seeds:** SQL base para utilizadores, pedidos de oração, posts de saúde, threads familiares e CSV de municípios de Angola.

## Próximos passos sugeridos

- Completar persistência de registo de utilizadores, hierarquia eclesiástica e RBAC detalhado.
- Implementar filas (database), notificações, chat com polling e gamificação.
- Expandir os testes automatizados (PHPUnit/Pest) para cobrir fluxos críticos.
- Evoluir o instalador web `/install` e ferramentas de administração `/gerenciar`.

> **Nota:** Todo o código está comentado e pronto para evolução incremental conforme os marcos originais.
