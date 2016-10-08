# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- mail_manager_trace
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mail_manager_trace`;

-- ---------------------------------------------------------------------
-- mail_manager_trace_i18n
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mail_manager_trace_i18n`;

-- ---------------------------------------------------------------------
-- mail_manager_history
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mail_manager_history`;

-- ---------------------------------------------------------------------
-- mail_manager_mail
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mail_manager_mail`;

-- ---------------------------------------------------------------------
-- mail_manager_history_mail
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mail_manager_history_mail`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;