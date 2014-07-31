<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');


$GLOBALS['TL_DCA']['tl_form_field']['fields']['nlChannels'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['nlChannels'],
	'exclude'                 => true,
	'inputType'               => 'channelWizard',
	'eval'                    => array('mandatory' => true),
	'options_callback'				=> array('ChannelWizard', 'getChannels'),
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['nlFieldMapping'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['nlFieldMapping'],
	'exclude'                 => true,
	'inputType'               => 'channelFieldWizard',
	'eval'                    => array('mandatory' => true),
	'options_callback'				=> array('ChannelFieldWizard', 'getChannelFields'),
);

// add palette for field type nl_subscribe
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['palettes']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['palettes'], count($GLOBALS['TL_DCA']['tl_form_field']['palettes']),
	array('nlSubscribe' => '{type_legend},type,name,label;{options_legend},nlChannels,nlFieldMapping;{fconfig_legend},mandatory;{expert_legend:hide},accesskey,class;{submit_legend},addSubmit')
	);
}


// add field type nl_subscribe to available form field 'type'
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'], (array_search('select', $GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'])+1),
		'nlSubscribe'
	);
}


// add palette for field type nl_subscribe_messages
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['palettes']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['palettes'], count($GLOBALS['TL_DCA']['tl_form_field']['palettes']),
	array('nlSubscribeMsg' => '{type_legend},type,name')
	);
}

// add field type nl_subscribe_message to available form field 'type'
if (is_array($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options']))
{
	array_insert($GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'], (array_search('checkbox', $GLOBALS['TL_DCA']['tl_form_field']['fields']['type']['options'])+1),
	'nlSubscribeMsg');
}

// add back end form fields
$GLOBALS['BE_FFL']['nlSubscribe'] = 'NewsletterFormSubscribe';
$GLOBALS['BE_FFL']['nlSubscribeMsg'] = 'NewsletterFormSubscribeMsg';


// add front end form fields
$GLOBALS['TL_FFL']['nlSubscribe'] = 'NewsletterFormSubscribe';
$GLOBALS['TL_FFL']['nlSubscribeMsg'] = 'NewsletterFormSubscribeMsg';