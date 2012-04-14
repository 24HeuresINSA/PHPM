ALTER TABLE `phpm`.`Orga` ADD privileges SMALLINT NOT NULL;
UPDATE `phpm`.`Orga` SET `privileges` = '2' WHERE `Orga`.`isAdmin` = 1;
UPDATE `phpm`.`Orga` SET `privileges` = '1' WHERE `Orga`.`isAdmin` = 0;
ALTER TABLE `phpm`.`Orga` DROP is_admin;
