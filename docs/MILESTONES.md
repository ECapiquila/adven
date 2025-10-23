# Marcos do Projeto Adventista Social (PHP MVC)

A seguir está a sequência incremental recomendada para evoluir a plataforma construída em PHP 8.2 MVC customizado. Cada marco pode ser entregue de forma independente garantindo validação contínua.

## Marco 1 — Fundação MVC & Instalador
- Consolidar o núcleo MVC (roteador, container, views, serviços) e consolidar o tema Bootstrap 5.
- Implementar o instalador `/install` para configurar `.env`, testar DB/SMTP, correr migrations/seeders e criar `storage/uploads`.
- Activar autenticação (login e registo completo), sessão segura, CSRF e sanitização via HTMLPurifier.

## Marco 2 — Perfil, Hierarquia & RBAC
- Persistir registo "Sou Adventista?" com regiões/distritos/igrejas, cargos e políticas de escopo.
- Painel `/gerenciar` para admins, presidentes, pastores e anciãos delegarem funções.
- Auditoria detalhada de atribuições, políticas e logs de actividades.

## Marco 3 — Feed Social & Interações
- Finalizar `/feed` com slider de comunicados, stories, devocional dinâmico e posts variados.
- Activar chat 1:1/grupo com polling e indicadores de entrega/visualização.
- Mensagens sabáticas na `/login` e mensagens de boas-vindas configuráveis.

## Marco 4 — Conteúdos Eclesiásticos & Formação
- Ministérios, eventos com RSVP, comunicados agendados.
- Investidas (inscrições e gestão), cursos com aulas/quizzes/certificado PDF.
- Louvores com player HTTP Range, playlists e integração em stories.

## Marco 5 — Finanças & Monetização
- Carteira, doações, saques, KYC e integrações de pagamento.
- Anúncios self-service com segmentação, métricas e planos `ad_free`.
- Assinaturas/planos com boosts e selo verificado.

## Marco 6 — Módulos Especializados
- **Pedidos de Oração**: roteamento multi-nível, reacções, respostas, encaminhamentos e gamificação.
- **Saúde & Bem-Estar**: fila de aprovação, destaques editoriais, perfis verificados.
- **Lar & Família**: threads privadas, encaminhamento hierárquico, notas internas e notificações.

## Marco 7 — Gamificação & Analytics
- Sistema de pontos, níveis e leaderboards (geral e por ministério).
- Dashboards com KPIs (orações activas, threads família, posts saúde, finanças).
- Schedules: expiração de stories/comunicados, limpeza, relatórios automáticos.

## Marco 8 — Qualidade & Documentação Final
- Testes de features (login, feed, comunicados, anúncios, investidas, cursos, louvores, oração, saúde, família, RBAC, auditoria).
- Guia Hostinger pt-AO, CRON, flags de funcionalidades e políticas de privacidade.
- Revisão de segurança (CSRF, rate-limit, sanitização) e UX responsiva.
