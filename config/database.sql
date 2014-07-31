-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `nl_subject` varchar(255) NOT NULL default '',
  `nl_subject_unsubscribe` varchar(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_newsletter_channel`
-- 

CREATE TABLE `tl_newsletter_channel` (
  `subscribeplus_inputs` blob NULL,
  `subscribeplus_required_inputs` blob NULL,
	`cleverreach_active` char(1) NOT NULL default '',
  `cleverreach_wsdl_url` varchar(64) NOT NULL default '',
  `cleverreach_api_key` varchar(64) NOT NULL default '',
  `cleverreach_listen_id` varchar(32) NOT NULL default '',
  `channel_page` int(10) unsigned NOT NULL default '0',
  `checkbox_label` varchar(255) NOT NULL default '',
  `nl_sender_name` varchar(255) NOT NULL default '',
  `nl_sender_mail` varchar(255) NOT NULL default '',
  `nl_subject` varchar(255) NOT NULL default '',
  `nl_text` text NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_newsletter_recipients`
-- 

CREATE TABLE `tl_newsletter_recipients` (
  `salutation` varchar(32) NULL default '',
  `title` varchar(32) NULL default '',
	`firstname` varchar(32) NULL default '',
	`lastname` varchar(32) NULL default '',
	`company` varchar(128) NULL default '',
	`street` varchar(64) NULL default '',
	`ziploc` varchar(64) NULL default '',
	`phone` varchar(32) NULL default '',
	`comment` text NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_form_field`
-- 

CREATE TABLE `tl_form_field` (
  `nlChannels` blob NULL,
  `nlFieldMapping` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;