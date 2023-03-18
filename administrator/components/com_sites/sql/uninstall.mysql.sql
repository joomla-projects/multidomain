-- This file includes the cleanup queries when uninstalling the component
-- Cleaning up Sites

-- We delete data on uninstall
DROP TABLE IF EXISTS `#__sites_sites`;
-- Cleaning up Domains

-- We delete data on uninstall
DROP TABLE IF EXISTS `#__sites_domains`;
-- Cleaning up Languages

-- We delete data on uninstall
DROP TABLE IF EXISTS `#__sites_languages`;
