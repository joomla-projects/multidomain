ALTER TABLE "#__users" ADD "multisitesGroupId" INT NOT NULL DEFAULT '0' AFTER "sendEmail";
DROP INDEX IF EXISTS "#__users_idx_name";
ALTER TABLE "#__users" ADD CONSTRAINT "#__users_idx_email_idx_multisitesGroupId" UNIQUE ("email", "multisitesGroupId");
ALTER TABLE "#__users" ADD CONSTRAINT "#__users_idx_username_idx_multisitesGroupId" UNIQUE ("username", "multisitesGroupId");
