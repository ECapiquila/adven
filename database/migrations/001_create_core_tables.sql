CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) UNIQUE,
    phone VARCHAR(40) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(40) DEFAULT 'membro',
    church_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS prayer_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_id INT UNSIGNED NOT NULL,
    scope_type ENUM('church','district','region') DEFAULT 'church',
    scope_id INT NOT NULL,
    title VARCHAR(160) NOT NULL,
    category ENUM('saude','familia','missao','agradecimento','outro') DEFAULT 'outro',
    body TEXT NOT NULL,
    privacy ENUM('publico','privado') DEFAULT 'publico',
    status ENUM('aberto','em_andamento','encaminhado','encerrado') DEFAULT 'aberto',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS health_posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_id INT UNSIGNED NOT NULL,
    title VARCHAR(190) NOT NULL,
    body_html TEXT,
    category ENUM('nutricao','exercicio','prevencao','mental','familia','outros') DEFAULT 'nutricao',
    media_path VARCHAR(255) NULL,
    visibility ENUM('publico','igreja','privado') DEFAULT 'publico',
    status ENUM('pending','approved','rejected') DEFAULT 'approved',
    is_verified_author TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS family_threads (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_id INT UNSIGNED NOT NULL,
    church_id INT NOT NULL,
    subject VARCHAR(180) NOT NULL,
    body TEXT NOT NULL,
    privacy ENUM('pastor_equipe','igreja_limitada') DEFAULT 'pastor_equipe',
    status ENUM('aberto','acompanhamento','encerrado') DEFAULT 'aberto',
    tags_json JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
