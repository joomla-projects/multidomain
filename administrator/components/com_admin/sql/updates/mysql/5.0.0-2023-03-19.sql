ALTER TABLE `#__users` ADD `multisitesGroupId` INT NOT NULL DEFAULT '0' AFTER `sendEmail`;
ALTER TABLE `#__users` DROP INDEX `idx_name`;
ALTER TABLE `#__users` ADD UNIQUE `idx_mailgroup` (`email`, `multisitesGroupId`);
ALTER TABLE `#__users` ADD UNIQUE `idx_namegroup` (`username`, `multisitesGroupId`);


