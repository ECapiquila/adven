-- Alterações adicionais para módulos expandidos
ALTER TABLE users
    ADD COLUMN is_secretario TINYINT(1) DEFAULT 0 AFTER is_anciao,
    ADD COLUMN inapp_prayer_status_changed TINYINT(1) DEFAULT 1,
    ADD COLUMN inapp_prayer_new_response TINYINT(1) DEFAULT 1,
    ADD COLUMN inapp_song_approved TINYINT(1) DEFAULT 1,
    ADD COLUMN inapp_song_rejected TINYINT(1) DEFAULT 1,
    ADD COLUMN email_song_approved TINYINT(1) DEFAULT 0,
    ADD COLUMN email_song_rejected TINYINT(1) DEFAULT 0,
    ADD COLUMN inapp_enabled TINYINT(1) DEFAULT 1,
    ADD COLUMN email_enabled TINYINT(1) DEFAULT 1,
    ADD COLUMN push_enabled TINYINT(1) DEFAULT 0,
    ADD COLUMN dark_mode_preference ENUM('system','light','dark') DEFAULT 'system';

INSERT INTO users (name, email, password, is_admin)
VALUES ('Administrador Demo', 'admin@demo.com', '$2y$12$.JEd/AcWeXvsPZCijT.0J.wZGqU1.Nlgaw9GEG4VrSgcCKNEM2uIy', 1)
ON DUPLICATE KEY UPDATE is_admin = VALUES(is_admin);

INSERT INTO role_user (user_id, role_id)
SELECT id, 101 FROM users WHERE email = 'admin@demo.com'
ON DUPLICATE KEY UPDATE role_id = VALUES(role_id);
