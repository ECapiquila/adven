-- Sabbath Connect base schema
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    is_admin TINYINT(1) DEFAULT 0,
    is_musician TINYINT(1) DEFAULT 0,
    is_pastor TINYINT(1) DEFAULT 0,
    is_anciao TINYINT(1) DEFAULT 0,
    verified_badge TINYINT(1) DEFAULT 0,
    verified_at TIMESTAMP NULL,
    total_points INT DEFAULT 0,
    level INT DEFAULT 1,
    region_id BIGINT NULL,
    district_id BIGINT NULL,
    church_id BIGINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE
);

CREATE TABLE role_user (
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id, role_id)
);

CREATE TABLE permission_role (
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id)
);

CREATE TABLE songs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    composer VARCHAR(150) NULL,
    lyrics TEXT NULL,
    audio_path VARCHAR(255) NULL,
    status ENUM('pendente','aprovado','recusado') DEFAULT 'pendente',
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE prayers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    title VARCHAR(150) NOT NULL,
    body TEXT NOT NULL,
    status ENUM('novo','em_atendimento','respondido','arquivado') DEFAULT 'novo',
    assigned_to BIGINT UNSIGNED NULL,
    region_id BIGINT NULL,
    district_id BIGINT NULL,
    church_id BIGINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE prayer_responses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prayer_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE prayer_reactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prayer_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    type ENUM('orou','gratidao') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY prayer_reactions_unique (prayer_id, user_id, type)
);

CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(150) NOT NULL,
    title VARCHAR(180) NOT NULL,
    body TEXT NOT NULL,
    meta_json JSON NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE notification_prefs_defaults (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(150) NOT NULL,
    in_app TINYINT(1) DEFAULT 1,
    email TINYINT(1) DEFAULT 0,
    push TINYINT(1) DEFAULT 0
);

CREATE TABLE notification_prefs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(150) NOT NULL,
    in_app TINYINT(1) DEFAULT 1,
    email TINYINT(1) DEFAULT 0,
    push TINYINT(1) DEFAULT 0,
    UNIQUE KEY notification_prefs_unique (user_id, type)
);

CREATE TABLE notification_mutes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    scope_type ENUM('region','district','church') NOT NULL,
    scope_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE notification_queue (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payload JSON NOT NULL,
    available_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    attempts INT DEFAULT 0
);

CREATE TABLE webpush_subscriptions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    endpoint VARCHAR(255) NOT NULL,
    p256dh VARCHAR(255) NOT NULL,
    auth VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY webpush_unique (user_id, endpoint)
);

CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(150) NOT NULL UNIQUE,
    value TEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(150) NOT NULL,
    object_type VARCHAR(120) NULL,
    object_id BIGINT NULL,
    meta JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE comunicados (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    body TEXT NOT NULL,
    media_path VARCHAR(255) NULL,
    scope_type ENUM('global','region','district','church') DEFAULT 'global',
    scope_id BIGINT NULL,
    starts_at DATETIME NULL,
    ends_at DATETIME NULL,
    status ENUM('rascunho','publicado','expirado') DEFAULT 'rascunho',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE verification_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    document_path VARCHAR(255) NOT NULL,
    baptism_card_path VARCHAR(255) NULL,
    status ENUM('pendente','aprovado','recusado') DEFAULT 'pendente',
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE wallets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    balance DECIMAL(12,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE wallet_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    wallet_id BIGINT UNSIGNED NOT NULL,
    type ENUM('credit','debit') NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    description VARCHAR(255) NOT NULL,
    meta JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE wallet_deposits (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    paid_at DATE NULL,
    receipt_path VARCHAR(255) NULL,
    status ENUM('pendente','aprovado','recusado') DEFAULT 'pendente',
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kyc_documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    document_type VARCHAR(100) NOT NULL,
    document_path VARCHAR(255) NOT NULL,
    status ENUM('pendente','aprovado','recusado') DEFAULT 'pendente',
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bank_accounts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    bank_name VARCHAR(150) NOT NULL,
    account_name VARCHAR(150) NOT NULL,
    iban VARCHAR(150) NOT NULL,
    is_default TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE payouts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    bank_account_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    status ENUM('pendente','aprovado','pago','recusado') DEFAULT 'pendente',
    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,
    paid_by BIGINT UNSIGNED NULL,
    paid_at TIMESTAMP NULL,
    reference VARCHAR(150) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE payout_transfers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payout_id BIGINT UNSIGNED NOT NULL,
    proof_path VARCHAR(255) NULL,
    reference VARCHAR(150) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE activity_points_config (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(120) NOT NULL UNIQUE,
    description VARCHAR(255) NOT NULL,
    points INT NOT NULL DEFAULT 0
);

CREATE TABLE user_points_history (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    code VARCHAR(120) NOT NULL,
    points INT NOT NULL,
    meta JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE email_queue (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    to_address VARCHAR(180) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    template VARCHAR(150) NOT NULL,
    payload JSON NULL,
    status ENUM('pendente','enviando','enviado','erro') DEFAULT 'pendente',
    attempts INT DEFAULT 0,
    last_error TEXT NULL,
    available_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE webhook_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    event VARCHAR(150) NOT NULL,
    payload JSON NOT NULL,
    attempts INT DEFAULT 0,
    last_response_code INT NULL,
    last_response_body TEXT NULL,
    status ENUM('pendente','processando','entregue','falhou') DEFAULT 'pendente',
    available_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE webhook_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    webhook_job_id BIGINT UNSIGNED NOT NULL,
    response_code INT NULL,
    response_headers TEXT NULL,
    response_body TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO roles (id, name, slug) VALUES
    (101, 'Designer', 'designer'),
    (102, 'Avaliador de Acessibilidade', 'a11y_reviewer')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO permissions (id, name, slug) VALUES
    (201, 'Gerir Paletas', 'design_manage_palettes'),
    (202, 'Publicar Tema', 'publish_theme_palette'),
    (203, 'Ver Auditoria de Tema', 'view_theme_audit')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO permission_role (role_id, permission_id) VALUES
    (101, 201),
    (101, 202),
    (101, 203),
    (102, 203)
ON DUPLICATE KEY UPDATE permission_id = VALUES(permission_id);
