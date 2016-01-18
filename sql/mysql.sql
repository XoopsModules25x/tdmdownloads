# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_broken`
#

CREATE TABLE tdmdownloads_broken (
  reportid int(5) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  sender int(11) NOT NULL default '0',
  ip varchar(20) NOT NULL default '',
  PRIMARY KEY  (reportid),
  KEY lid (lid),
  KEY sender (sender),
  KEY ip (ip)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_cat`
#

CREATE TABLE tdmdownloads_cat (
  cat_cid int(5) unsigned NOT NULL auto_increment,
  cat_pid int(5) unsigned NOT NULL default '0',
  cat_title varchar(255) NOT NULL default '',
  cat_imgurl varchar(255) NOT NULL default '',
  cat_description_main text NOT NULL,
  cat_weight int(11) NOT NULL default '0',
  PRIMARY KEY  (cat_cid),
  KEY cat_pid (cat_pid)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_downloads`
#

CREATE TABLE tdmdownloads_downloads (
  lid int(11) unsigned NOT NULL auto_increment,
  cid int(5) unsigned NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  homepage varchar(255) NOT NULL default '',
  version varchar(10) NOT NULL default '',
  size varchar(15) NOT NULL default '',
  platform varchar(255) NOT NULL default '',
  description text NOT NULL,
  logourl varchar(255) NOT NULL default '',
  submitter int(11) NOT NULL default '0',
  status tinyint(2) NOT NULL default '0',
  date int(10) NOT NULL default '0',
  hits int(11) unsigned NOT NULL default '0',
  rating double(6,4) NOT NULL default '0.0000',
  votes int(11) unsigned NOT NULL default '0',
  comments int(11) unsigned NOT NULL default '0',
  top tinyint(2) NOT NULL default '0',
  paypal varchar(255) NOT NULL default '',
  PRIMARY KEY  (lid),
  KEY cid (cid),
  KEY status (status),
  KEY title (title(40))
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_mod`
#

CREATE TABLE tdmdownloads_mod (
  requestid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  cid int(5) unsigned NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  homepage varchar(255) NOT NULL default '',
  version varchar(10) NOT NULL default '',
  size varchar(15) NOT NULL default '',
  platform varchar(50) NOT NULL default '',
  logourl varchar(255) NOT NULL default '',
  description text NOT NULL,
  modifysubmitter int(11) NOT NULL default '0',
  PRIMARY KEY  (requestid)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_votedata`
#

CREATE TABLE tdmdownloads_votedata (
  ratingid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname),
  KEY lid (lid)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_field`
#

CREATE TABLE tdmdownloads_field (
  fid int(11) unsigned NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  img varchar(255) NOT NULL default '',
  weight int(11) NOT NULL default '0',
  status int(5) unsigned NOT NULL default '0',
  search int(5) unsigned NOT NULL default '0',
  status_def int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (fid)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_fielddata`
#

CREATE TABLE tdmdownloads_fielddata (
  iddata int(11) unsigned NOT NULL auto_increment,
  fid int(11) unsigned NOT NULL default '0',
  lid int(11) unsigned NOT NULL default '0',
  data varchar(255) NOT NULL default '',
  PRIMARY KEY  (iddata)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_modfielddata`
#

CREATE TABLE tdmdownloads_modfielddata (
  modiddata int(11) unsigned NOT NULL auto_increment,
  fid int(11) unsigned NOT NULL default '0',
  lid int(11) unsigned NOT NULL default '0',
  moddata varchar(255) NOT NULL default '',
  PRIMARY KEY  (modiddata)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_downlimit`
#

CREATE TABLE tdmdownloads_downlimit (
  downlimit_id int(11) unsigned NOT NULL auto_increment,
  downlimit_lid int(11) unsigned NOT NULL default '0',
  downlimit_uid int(11) NOT NULL default '0',
  downlimit_hostname varchar(60) NOT NULL default '',
  downlimit_date int(10) NOT NULL default '0',
  PRIMARY KEY  (downlimit_id)
) ENGINE=MyISAM;