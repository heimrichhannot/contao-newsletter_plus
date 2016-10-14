<?php


/* Additional Newsletter Subcribtion Fields e.g. First Name, Last Name, Title ... */

$GLOBALS['TL_DCA']['tl_subscribe_plus'] = array(
	'fields' => array(
		// Sex
		'salutation' => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['sex'],
			'flag'      => 1,
			'inputType' => 'radio',
			'options'   => array('male', 'female'),
			'reference' => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['sex'],
			'eval'      => array('beEditable' => true),
			'sql'       => "varchar(32) NOT NULL default ''"
		),
		// Title
		'title'     => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['title'],
			'flag'      => 1,
			'inputType' => 'radio',
			'options'   => array('dr', 'prof.dr'),
			'reference' => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['title'],
			'eval'      => array('beEditable' => true),
			'sql'       => "varchar(255) NOT NULL default ''"
		),
		// First Name
		'firstname' => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['firstname'],
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('beEditable' => true),
			'sql'       => "varchar(255) NOT NULL default ''"
		),
		// Last Name
		'lastname'  => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['lastname'],
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('beEditable' => true),
			'sql'       => "varchar(255) NOT NULL default ''"
		),
		// Last Name
		'company'   => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['company'],
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('beEditable' => true),
			'sql'       => "varchar(255) NOT NULL default ''"
		),
		'email'     => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['email'],
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('beEditable' => false, 'rgxp' => 'email', 'required' => true, 'mandatory' => true, 'placeholder' => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['placeholders']['email']),
			'sql'       => "varchar(255) NOT NULL default ''"
		),
		'street'    => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['street'],
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('beEditable' => true),
			'sql'       => "varchar(255) NOT NULL default ''"
		),
		'ziploc'    => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['ziploc'],
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('beEditable' => true),
			'sql'       => "varchar(32) NOT NULL default ''"
		),
		'phone'     => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['phone'],
			'flag'      => 1,
			'inputType' => 'text',
			'eval'      => array('beEditable' => true),
			'sql'       => "varchar(64) NOT NULL default ''"
		),
		'comment'   => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['comment'],
			'flag'      => 1,
			'inputType' => 'textarea',
			'eval'      => array('beEditable' => true),
			'sql'		=> 'text NULL'
		),
	),
);