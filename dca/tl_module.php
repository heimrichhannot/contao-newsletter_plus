<?php

/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['subscribe_plus']   = '{title_legend},name,headline,type;{config_legend},nl_channels,nl_hideChannels;{redirect_legend},jumpTo;{template_legend:hide},nl_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['unsubscribe_plus'] = '{title_legend},name,headline,type;{config_legend},nl_channels,nl_hideChannels;{redirect_legend},jumpTo;{email_legend:hide},nl_subject_unsubscribe,nl_unsubscribe;{template_legend:hide},nl_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['nl_archive'] = '{title_legend},name,headline,type;{config_legend},nl_channels;{redirect_legend},jumpTo;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_subject'] = array(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['nl_subject'],
	'exclude'       => true,
	'inputType'     => 'text',
	'eval'          => array('mandatory' => true, 'tl_class' => 'long', 'decodeEntities' => true, 'alwaysSave' => true),
	'load_callback' => array
	(
		array('tl_module_subscribeplus', 'getSubjectDefault')
	),
	'sql'			=> "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['nl_subject_unsubscribe'] = array(
	'label'         => &$GLOBALS['TL_LANG']['tl_module']['nl_subject'],
	'exclude'       => true,
	'inputType'     => 'text',
	'eval'          => array('mandatory' => true, 'tl_class' => 'long', 'decodeEntities' => true, 'alwaysSave' => true),
	'load_callback' => array
	(
		array('tl_module_subscribeplus', 'getUnsubscribeSubjectDefault')
	),
	'sql'			=> "varchar(255) NOT NULL default ''"
);


$GLOBALS['TL_DCA']['tl_module']['fields']['nl_template']['default'] = 'nl_subscribe_plus';


class tl_module_subscribeplus extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();
	}

	public function getSubjectDefault()
	{
		if (!trim($varValue)) {
			$varValue = $GLOBALS['TL_LANG']['tl_module']['nl_subject_default'][1];
		}

		return $varValue;
	}

	public function getUnsubscribeSubjectDefault()
	{
		if (!trim($varValue)) {
			$varValue = $GLOBALS['TL_LANG']['tl_module']['nl_subject_unsubscribe_default'][1];
		}

		return $varValue;
	}
}
