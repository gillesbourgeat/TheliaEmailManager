
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- mail_manager_trace
-- ---------------------------------------------------------------------

CREATE TABLE `mail_manager_trace`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `hash` CHAR(32) NOT NULL,
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
    UNIQUE INDEX `mail_manager_trace_U_1` (`hash`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- mail_manager_trace_comment
-- ---------------------------------------------------------------------

CREATE TABLE `mail_manager_trace_comment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `trace_id` INTEGER NOT NULL,
    `admin_id` INTEGER,
    `comment` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `mail_manager_trace_comment_I_1` (`trace_id`),
    INDEX `FI_mail_manager_trace_comment__admin` (`admin_id`),
    CONSTRAINT `fk_mail_manager_trace_comment__mail_manager_trace`
        FOREIGN KEY (`trace_id`)
        REFERENCES `mail_manager_trace` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `fk_mail_manager_trace_comment__admin`
        FOREIGN KEY (`admin_id`)
        REFERENCES `admin` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- mail_manager_history
-- ---------------------------------------------------------------------

CREATE TABLE `mail_manager_history`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `trace_id` INTEGER NOT NULL,
    `subject` TEXT,
    `body` BLOB,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `idx_arh_customer_profile_is_default` (`trace_id`),
    CONSTRAINT `fk_mail_manager_history__mail_manager_trace`
        FOREIGN KEY (`trace_id`)
        REFERENCES `mail_manager_trace` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- mail_manager_mail
-- ---------------------------------------------------------------------

CREATE TABLE `mail_manager_mail`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `mail` VARCHAR(255) NOT NULL,
    `disable_send` TINYINT(1) DEFAULT 0,
    `disable_send_date` DATETIME,
    `disable_hash` CHAR(64) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `mail_manager_mail_U_1` (`mail`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- mail_manager_history_mail
-- ---------------------------------------------------------------------

CREATE TABLE `mail_manager_history_mail`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `history_id` INTEGER NOT NULL,
    `mail_id` INTEGER NOT NULL,
    `type` TINYINT NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `mail_manager_history_mail_U_1` (`history_id`, `mail_id`, `type`),
    INDEX `mail_manager_history_mail_I_1` (`history_id`),
    INDEX `mail_manager_history_mail_I_2` (`type`),
    INDEX `mail_manager_history_mail_I_3` (`mail_id`),
    CONSTRAINT `fk_mail_manager_history_mail__mail_manager_history`
        FOREIGN KEY (`history_id`)
        REFERENCES `mail_manager_history` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `fk_mail_manager_history_mail__mail_manager_mail`
        FOREIGN KEY (`mail_id`)
        REFERENCES `mail_manager_trace` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
