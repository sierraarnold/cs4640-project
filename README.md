# cs4640-project

DB SQL reference

CREATE TABLE user (
	user_id INT AUTO_INCREMENT,
    email VARCHAR(50),
    pwd VARCHAR(50),
    PRIMARY KEY (user_id))

CREATE TABLE conversion (
	conversion_id INT AUTO_INCREMENT,
    unit1 VARCHAR(50),
    unit2 VARCHAR(50),
    ratio DOUBLE, 
    PRIMARY KEY (conversion_id))

CREATE TABLE `cs4640-project`.`user_conversions` ( `unit_id` INT NOT NULL , `conversion_id` INT NOT NULL ) ENGINE = InnoDB;

ALTER TABLE `user_conversions` ADD FOREIGN KEY (`conversion_id`) REFERENCES `conversions`(`conversion_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `user_conversions` ADD FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `user_conversions` ADD UNIQUE( `conversion_id`, `user_id`);

