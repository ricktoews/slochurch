--
-- Table structure for table `site_mailing_sections`
--

CREATE TABLE site_mailing_sections (
  ms_id int(11) NOT NULL auto_increment,
  ms_mailid int(11) NOT NULL default '0',
  ms_name varchar(50) NOT NULL default '',
  ms_heading varchar(255) NOT NULL default '',
  ms_content text NOT NULL,
  ms_image varchar(255) NOT NULL default '',
  ms_image2 varchar(255) NOT NULL default '',
  ms_image3 varchar(255) NOT NULL default '',
  ms_align int(11) NOT NULL default '0',
  ms_seq int(11) NOT NULL default '0',
  PRIMARY KEY  (ms_id)
) TYPE=MyISAM;

--
-- Dumping data for table `site_mailing_sections`
--

INSERT INTO site_mailing_sections VALUES (1,5,'Battle Ground Lake','','Yesterday, we went to Battle Ground Lake and walked around it.  The lake\'s small, but the walk around is quite nice.  There are lots of fir trees and various ivy and ferns growing along the path.','battle_ground_lake.jpg','Mount Hood.jpg','',0,0);
INSERT INTO site_mailing_sections VALUES (2,5,'Ridgefield','','The other day, we got to see a double rainbow.  The main arc was complete and set against a backdrop of pink clouds.  Quite spectacular.','ridgefield_double_rainbow.jpg','','',1,0);

--
-- Table structure for table `site_mailings`
--

-- CREATE TABLE site_mailings (
--   mail_id int(11) NOT NULL auto_increment,
--   mail_date date NOT NULL default '0000-00-00',
--   mail_subject varchar(255) NOT NULL default '',
--   mail_contact text NOT NULL,
--   PRIMARY KEY  (mail_id)
-- ) TYPE=MyISAM;

--
-- Dumping data for table `site_mailings`
--

-- INSERT INTO site_mailings VALUES (5,'2005-07-24','Rick\'s Recreational Ruminations','');

