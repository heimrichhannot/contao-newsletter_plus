<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/* Additional Newsletter Subcribtion Fields e.g. First Name, Last Name, Title ... */

$GLOBALS['TL_DCA']['tl_subscribe_plus'] = array(
	'fields' => array(
		// Sex
		'salutation' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['sex'],
			'flag'                    => 1,
			'inputType'               => 'radio',
			'options'									=> array('male','female'),
			'reference'								=> &$GLOBALS['TL_LANG']['tl_subscribe_plus']['sex'],
			'eval'                    => array('beEditable'=>true)
		),
		// Title
		'title' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['title'],
			'flag'                    => 1,
			'inputType'               => 'radio',
			'options'									=> array('dr','prof.dr'),
			'reference'								=> &$GLOBALS['TL_LANG']['tl_subscribe_plus']['title'],
			'eval'                    => array('beEditable'=>true)
		),
		// First Name
		'firstname' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['firstname'],
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('beEditable'=>true)
		),
		// Last Name
		'lastname' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['lastname'],
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('beEditable'=>true)
		),
		// Last Name
		'company' => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['company'],
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('beEditable'=>true)
		),
		'email'	  => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['email'],
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('beEditable'=>false, 'rgxp' => 'email', 'required' => true, 'mandatory' => true, 'placeholder' => $GLOBALS['TL_LANG']['tl_subscribe_plus']['emailPlaceHolder'])
		),
		'street'	  => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['street'],
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('beEditable'=>true)
		),
		'ziploc'	  => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['ziploc'],
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('beEditable'=>true)
		),
		'phone'	  => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['phone'],
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('beEditable'=>true)
		),
		'comment'	  => array(
			'label'                   => &$GLOBALS['TL_LANG']['tl_subscribe_plus']['comment'],
			'flag'                    => 1,
			'inputType'               => 'textarea',
			'eval'                    => array('beEditable'=>true)
		),
	),
);