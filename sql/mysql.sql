# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_broken`
#

CREATE TABLE `tdmdownloads_broken` (
    reportid INT(5)      NOT NULL AUTO_INCREMENT,
    lid      INT(11)     NOT NULL DEFAULT '0',
    sender   INT(11)     NOT NULL DEFAULT '0',
    ip       VARCHAR(20) NOT NULL DEFAULT '',
    PRIMARY KEY (reportid),
    KEY lid (lid),
    KEY sender (sender),
    KEY ip (ip)
)
    ENGINE = MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_cat`
#

CREATE TABLE `tdmdownloads_cat` (
    cat_cid              INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    cat_pid              INT(5) UNSIGNED NOT NULL DEFAULT '0',
    cat_title            VARCHAR(255)    NOT NULL DEFAULT '',
    cat_imgurl           VARCHAR(255)    NOT NULL DEFAULT '',
    cat_description_main TEXT            NOT NULL,
    cat_weight           INT(11)         NOT NULL DEFAULT '0',
    PRIMARY KEY (cat_cid),
    KEY cat_pid (cat_pid)
)
    ENGINE = MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_downloads`
#

CREATE TABLE `tdmdownloads_downloads` (
    lid         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    cid         INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    title       VARCHAR(255)     NOT NULL DEFAULT '',
    url         VARCHAR(255)     NOT NULL DEFAULT '',
    homepage    VARCHAR(255)     NOT NULL DEFAULT '',
    version     VARCHAR(20)      NOT NULL DEFAULT '',
    size        VARCHAR(15)      NOT NULL DEFAULT '',
    platform    VARCHAR(255)     NOT NULL DEFAULT '',
    description TEXT             NOT NULL,
    logourl     VARCHAR(255)     NOT NULL DEFAULT '',
    submitter   INT(11)          NOT NULL DEFAULT '0',
    status      TINYINT(2)       NOT NULL DEFAULT '0',
    date        INT(10)          NOT NULL DEFAULT '0',
    hits        INT(11) UNSIGNED NOT NULL DEFAULT '0',
    rating      DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
    votes       INT(11) UNSIGNED NOT NULL DEFAULT '0',
    comments    INT(11) UNSIGNED NOT NULL DEFAULT '0',
    top         TINYINT(2)       NOT NULL DEFAULT '0',
    paypal      VARCHAR(255)     NOT NULL DEFAULT '',
    PRIMARY KEY (lid),
    KEY cid (cid),
    KEY status (status),
    KEY title (title(40))
)
    ENGINE = MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_mod`
#

CREATE TABLE `tdmdownloads_mod` (
    requestid       INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    lid             INT(11) UNSIGNED NOT NULL DEFAULT '0',
    cid             INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    title           VARCHAR(255)     NOT NULL DEFAULT '',
    url             VARCHAR(255)     NOT NULL DEFAULT '',
    homepage        VARCHAR(255)     NOT NULL DEFAULT '',
    version         VARCHAR(20)      NOT NULL DEFAULT '',
    size            VARCHAR(15)      NOT NULL DEFAULT '',
    platform        VARCHAR(50)      NOT NULL DEFAULT '',
    logourl         VARCHAR(255)     NOT NULL DEFAULT '',
    description     TEXT             NOT NULL,
    modifysubmitter INT(11)          NOT NULL DEFAULT '0',
    PRIMARY KEY (requestid)
)
    ENGINE = MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_votedata`
#

CREATE TABLE `tdmdownloads_votedata` (
    ratingid        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    lid             INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    ratinguser      INT(11)             NOT NULL DEFAULT '0',
    rating          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    ratinghostname  VARCHAR(60)         NOT NULL DEFAULT '',
    ratingtimestamp INT(10)             NOT NULL DEFAULT '0',
    PRIMARY KEY (ratingid),
    KEY ratinguser (ratinguser),
    KEY ratinghostname (ratinghostname),
    KEY lid (lid)
)
    ENGINE = MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_field`
#

CREATE TABLE `tdmdownloads_field` (
    fid        INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    title      VARCHAR(255)     NOT NULL DEFAULT '',
    img        VARCHAR(255)     NOT NULL DEFAULT '',
    weight     INT(11)          NOT NULL DEFAULT '0',
    status     INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    search     INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    status_def INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    PRIMARY KEY (fid)
)
    ENGINE = MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_fielddata`
#

CREATE TABLE `tdmdownloads_fielddata` (
    iddata INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    fid    INT(11) UNSIGNED NOT NULL DEFAULT '0',
    lid    INT(11) UNSIGNED NOT NULL DEFAULT '0',
    data   VARCHAR(255)     NOT NULL DEFAULT '',
    PRIMARY KEY (iddata)
)
    ENGINE = MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_modfielddata`
#

CREATE TABLE `tdmdownloads_modfielddata` (
    modiddata INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    fid       INT(11) UNSIGNED NOT NULL DEFAULT '0',
    lid       INT(11) UNSIGNED NOT NULL DEFAULT '0',
    moddata   VARCHAR(255)     NOT NULL DEFAULT '',
    PRIMARY KEY (modiddata)
)
    ENGINE = MyISAM;
# --------------------------------------------------------

#
# Table structure for table `tdmdownloads_downlimit`
#

CREATE TABLE `tdmdownloads_downlimit` (
    downlimit_id       INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    downlimit_lid      INT(11) UNSIGNED NOT NULL DEFAULT '0',
    downlimit_uid      INT(11)          NOT NULL DEFAULT '0',
    downlimit_hostname VARCHAR(60)      NOT NULL DEFAULT '',
    downlimit_date     INT(10)          NOT NULL DEFAULT '0',
    PRIMARY KEY (downlimit_id)
)
    ENGINE = MyISAM;
