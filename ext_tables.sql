#
# Table structure for table 'tx_mediacenter_domain_model_media'
#
CREATE TABLE tx_mediacenter_domain_model_media (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  title varchar(255) DEFAULT '' NOT NULL,
  assets int(11) DEFAULT '0' NOT NULL,
  media_category int(11) DEFAULT '0' NOT NULL,
  media_type int(11) DEFAULT '0' NOT NULL,
  teaser varchar(255) DEFAULT '' NOT NULL,
  date int(11) DEFAULT '0' NOT NULL,
  text text,
  downloadable tinyint(4) DEFAULT '0' NOT NULL,
  related_media int(11) DEFAULT '0' NOT NULL,
  slug varchar(255) DEFAULT '' NOT NULL,

  l10n_parent int(11) DEFAULT '0' NOT NULL,
  l10n_diffsource mediumblob,

  PRIMARY KEY (uid),
  KEY parent (pid)
);

#
# Table structure for table 'tx_mediacenter_domain_model_mediacategory'
#
CREATE TABLE tx_mediacenter_domain_model_mediacategory (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,

  title varchar(255) DEFAULT '' NOT NULL,

  l10n_parent int(11) DEFAULT '0' NOT NULL,
  l10n_diffsource mediumblob,

  PRIMARY KEY (uid),
  KEY parent (pid)
);

#
# Table structure for table 'tx_mediacenter_media_media_mm'
#
CREATE TABLE tx_mediacenter_media_media_mm (
    uid_local int(11) DEFAULT '0' NOT NULL,
    uid_foreign int(11) DEFAULT '0' NOT NULL,
    sorting int(11) DEFAULT '0' NOT NULL,

    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);
