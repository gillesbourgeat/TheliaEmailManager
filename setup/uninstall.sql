# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- email_manager_trace
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `email_manager_trace`;

-- ---------------------------------------------------------------------
-- email_manager_trace_i18n
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `email_manager_trace_i18n`;

-- ---------------------------------------------------------------------
-- email_manager_history
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `email_manager_history`;

-- ---------------------------------------------------------------------
-- email_manager_email
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `email_manager_email`;

-- ---------------------------------------------------------------------
-- email_manager_history_email
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `email_manager_history_email`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;