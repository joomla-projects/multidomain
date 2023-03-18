-- MySQL installation file for Multisite

-- Creating table for Sites
CREATE TABLE IF NOT EXISTS `#__sites_sites` (
    `idSite` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `description` text NOT NULL,
    -- MVC_LANDMARK_SiteFIELDS
    `state` tinyint NOT NULL DEFAULT 0,
    `ordering` int NOT NULL DEFAULT 0,
    `metakey` text,
    `params` text NOT NULL,
    `checked_out` int unsigned,
    `checked_out_time` datetime,
    `publish_up` datetime,
    `publish_down` datetime,
    `created` datetime NOT NULL,
    `created_by` int unsigned NOT NULL DEFAULT 0,
    `created_by_alias` varchar(255) NOT NULL DEFAULT '',
    `modified` datetime NOT NULL,
    `modified_by` int unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`idSite`),
    KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Creating table for Domains
CREATE TABLE IF NOT EXISTS `#__sites_domains` (
    `idDomain` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `description` text NOT NULL,
    -- MVC_LANDMARK_DomainFIELDS
    `state` tinyint NOT NULL DEFAULT 0,
    `ordering` int NOT NULL DEFAULT 0,
    `metakey` text,
    `params` text NOT NULL,
    `checked_out` int unsigned,
    `checked_out_time` datetime,
    `publish_up` datetime,
    `publish_down` datetime,
    `created` datetime NOT NULL,
    `created_by` int unsigned NOT NULL DEFAULT 0,
    `created_by_alias` varchar(255) NOT NULL DEFAULT '',
    `modified` datetime NOT NULL,
    `modified_by` int unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`idDomain`),
    KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Creating table for Languages
CREATE TABLE IF NOT EXISTS `#__sites_languages` (
    `idLanguage` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `description` text NOT NULL,
    -- MVC_LANDMARK_LanguageFIELDS
    `state` tinyint NOT NULL DEFAULT 0,
    `ordering` int NOT NULL DEFAULT 0,
    `metakey` text,
    `params` text NOT NULL,
    `checked_out` int unsigned,
    `checked_out_time` datetime,
    `publish_up` datetime,
    `publish_down` datetime,
    `created` datetime NOT NULL,
    `created_by` int unsigned NOT NULL DEFAULT 0,
    `created_by_alias` varchar(255) NOT NULL DEFAULT '',
    `modified` datetime NOT NULL,
    `modified_by` int unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY (`idLanguage`),
    KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
