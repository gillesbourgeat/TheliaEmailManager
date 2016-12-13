
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- email_manager_trace
-- ---------------------------------------------------------------------

CREATE TABLE `email_manager_trace`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `parent_id` INTEGER,
    `hash` CHAR(32) NOT NULL,
    `cli` TINYINT(1) DEFAULT 0,
    `environment` CHAR(32) NOT NULL,
    `disable_history` TINYINT(1) DEFAULT 0,
    `disable_sending` TINYINT(1) DEFAULT 0,
    `force_same_customer_disable` TINYINT(1) DEFAULT 0,
    `number_of_catch` INTEGER DEFAULT 0,
    `email_bcc` TEXT,
    `email_redirect` TEXT,
    `detail` TEXT NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `email_manager_trace_U_1` (`hash`),
    INDEX `FI_email_manager_trace__email_manager_trace` (`parent_id`),
    CONSTRAINT `fk_email_manager_trace__email_manager_trace`
        FOREIGN KEY (`parent_id`)
        REFERENCES `email_manager_trace` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- email_manager_history
-- ---------------------------------------------------------------------

CREATE TABLE `email_manager_history`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `trace_id` INTEGER NOT NULL,
    `status` INTEGER DEFAULT 0,
    `subject` TEXT,
    `info` TEXT,
    `body` BLOB,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `email_manager_history_I_1` (`status`),
    INDEX `email_manager_history_trace_id` (`trace_id`),
    INDEX `email_manager_history_created_at` (`created_at`),
    CONSTRAINT `fk_email_manager_history__email_manager_trace`
        FOREIGN KEY (`trace_id`)
        REFERENCES `email_manager_trace` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- email_manager_email
-- ---------------------------------------------------------------------

CREATE TABLE `email_manager_email`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `disable_send` TINYINT(1) DEFAULT 0,
    `disable_send_date` DATETIME,
    `disable_hash` CHAR(64) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `email_manager_email_U_1` (`email`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- email_manager_history_email
-- ---------------------------------------------------------------------

CREATE TABLE `email_manager_history_email`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `history_id` INTEGER NOT NULL,
    `email_id` INTEGER NOT NULL,
    `type` TINYINT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `email_manager_history_email_U_1` (`history_id`, `email_id`, `type`),
    INDEX `email_manager_history_email_I_1` (`history_id`),
    INDEX `email_manager_history_email_I_2` (`type`),
    INDEX `email_manager_history_email_I_3` (`email_id`),
    CONSTRAINT `fk_email_manager_history_email__email_manager_history`
        FOREIGN KEY (`history_id`)
        REFERENCES `email_manager_history` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `fk_email_manager_history_email__email_manager_email`
        FOREIGN KEY (`email_id`)
        REFERENCES `email_manager_email` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- email_manager_trace_i18n
-- ---------------------------------------------------------------------

CREATE TABLE `email_manager_trace_i18n`
(
    `id` INTEGER NOT NULL,
    `locale` VARCHAR(5) DEFAULT 'en_US' NOT NULL,
    `title` TEXT,
    `description` VARCHAR(255),
    PRIMARY KEY (`id`,`locale`),
    CONSTRAINT `email_manager_trace_i18n_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `email_manager_trace` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
