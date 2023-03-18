-- Postgresql installation file for COMPONENT_NAME

-- Postgresql installation file for COMPONENT_NAME
CREATE TABLE IF NOT EXISTS "#__sites_sites" (
    "idSite" serial NOT NULL,
    "title" varchar(255) DEFAULT '' NOT NULL,
    "alias" varchar(255) DEFAULT '' NOT NULL,
    "description" text DEFAULT '' NOT NULL,
    -- MVC_LANDMARK_SiteFIELDS
    "state" smallint DEFAULT 0 NOT NULL,
    "ordering" bigint DEFAULT 0 NOT NULL,
    "metakey" text,
    "params" text DEFAULT '' NOT NULL,
    "checked_out" integer,
    "checked_out_time" timestamp without time zone,
    "publish_up" timestamp without time zone,
    "publish_down" timestamp without time zone,
    "created" timestamp without time zone NOT NULL,
    "created_by" bigint DEFAULT 0 NOT NULL,
    "created_by_alias" varchar(255) DEFAULT '' NOT NULL,
    "modified" timestamp without time zone NOT NULL,
    "modified_by" bigint DEFAULT 0 NOT NULL,
    PRIMARY KEY ("idSite"),
);
CREATE INDEX "#__sites_sites_idx_title" ON "#__sites_sites" ("title");

-- Postgresql installation file for COMPONENT_NAME
CREATE TABLE IF NOT EXISTS "#__sites_domains" (
    "idDomain" serial NOT NULL,
    "title" varchar(255) DEFAULT '' NOT NULL,
    "alias" varchar(255) DEFAULT '' NOT NULL,
    "description" text DEFAULT '' NOT NULL,
    -- MVC_LANDMARK_DomainFIELDS
    "state" smallint DEFAULT 0 NOT NULL,
    "ordering" bigint DEFAULT 0 NOT NULL,
    "metakey" text,
    "params" text DEFAULT '' NOT NULL,
    "checked_out" integer,
    "checked_out_time" timestamp without time zone,
    "publish_up" timestamp without time zone,
    "publish_down" timestamp without time zone,
    "created" timestamp without time zone NOT NULL,
    "created_by" bigint DEFAULT 0 NOT NULL,
    "created_by_alias" varchar(255) DEFAULT '' NOT NULL,
    "modified" timestamp without time zone NOT NULL,
    "modified_by" bigint DEFAULT 0 NOT NULL,
    PRIMARY KEY ("idDomain"),
);
CREATE INDEX "#__sites_domains_idx_title" ON "#__sites_domains" ("title");

-- Postgresql installation file for COMPONENT_NAME
CREATE TABLE IF NOT EXISTS "#__sites_languages" (
    "idLanguage" serial NOT NULL,
    "title" varchar(255) DEFAULT '' NOT NULL,
    "alias" varchar(255) DEFAULT '' NOT NULL,
    "description" text DEFAULT '' NOT NULL,
    -- MVC_LANDMARK_LanguageFIELDS
    "state" smallint DEFAULT 0 NOT NULL,
    "ordering" bigint DEFAULT 0 NOT NULL,
    "metakey" text,
    "params" text DEFAULT '' NOT NULL,
    "checked_out" integer,
    "checked_out_time" timestamp without time zone,
    "publish_up" timestamp without time zone,
    "publish_down" timestamp without time zone,
    "created" timestamp without time zone NOT NULL,
    "created_by" bigint DEFAULT 0 NOT NULL,
    "created_by_alias" varchar(255) DEFAULT '' NOT NULL,
    "modified" timestamp without time zone NOT NULL,
    "modified_by" bigint DEFAULT 0 NOT NULL,
    PRIMARY KEY ("idLanguage"),
);
CREATE INDEX "#__sites_languages_idx_title" ON "#__sites_languages" ("title");
