-- Script SQL para crear la tabla de registros de actividad

-- Verificar si la tabla ya existe y eliminarla si es necesario
-- DROP TABLE IF EXISTS activity_logs;

-- Crear la tabla activity_logs
CREATE TABLE IF NOT EXISTS activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    model_type VARCHAR(255) NULL,
    model_id BIGINT UNSIGNED NULL,
    properties JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX activity_logs_action_index (action),
    INDEX activity_logs_created_at_index (created_at),
    INDEX activity_logs_model_type_model_id_index (model_type, model_id)
);

-- Agregar restricción de clave externa si la tabla users existe
-- ALTER TABLE activity_logs
-- ADD CONSTRAINT activity_logs_user_id_foreign
-- FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL;
