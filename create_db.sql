-- session
CREATE TABLE `workoutdbv1`.`workouts` (`id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `date` DATE NOT NULL , `duration_minutes` INT NOT NULL , `calories_burned` INT NOT NULL , `notes` VARCHAR(256) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `workouts` ADD CONSTRAINT `userfkworkouts` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

-- workouts
CREATE TABLE `workoutdbv1`.`workouts` (`id` INT NOT NULL AUTO_INCREMENT , `session_id` INT NOT NULL , `exercise_id` INT NOT NULL , `user_id` INT NOT NULL , `sets` INT NOT NULL , `reps` INT NOT NULL , `weight` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `workouts` ADD CONSTRAINT `userfk` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT; ALTER TABLE `workouts` ADD CONSTRAINT `sessionfk` FOREIGN KEY (`session_id`) REFERENCES `session`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT; ALTER TABLE `workouts` ADD CONSTRAINT `exfk` FOREIGN KEY (`exercise_id`) REFERENCES `exercises`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
