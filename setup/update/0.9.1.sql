ALTER TABLE  `email_manager_history` ADD  `status` INT NOT NULL AFTER  `trace_id` ;
ALTER TABLE  `email_manager_history` ADD  `info` TEXT NOT NULL AFTER  `subject` ;