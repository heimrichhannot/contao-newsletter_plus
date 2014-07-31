<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');


$GLOBALS['TL_DCA']['tl_newsletter_channel']['palettes']['default'] = str_replace('jumpTo;','jumpTo;{cleverreach_legend},cleverreach_active,cleverreach_wsdl_url,cleverreach_api_key,cleverreach_listen_id; {subscribe_plus_legend},subscribeplus_inputs,subscribeplus_required_inputs,channel_page,checkbox_label,nl_sender_name,nl_sender_mail,nl_subject,nl_text;',
																														$GLOBALS['TL_DCA']['tl_newsletter_channel']['palettes']['default']);


/**
 * Add fields to tl_newsletter
 */
$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['cleverreach_active'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_active'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['cleverreach_wsdl_url'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_wsdl_url'],
	'inputType'               => 'text',
	'default'									=> 'http://api.cleverreach.com/soap/interface_v4.1.php?wsdl',
	'eval'                    => array('tl_class'=>'long'),
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['cleverreach_api_key'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_api_key'],
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'long')
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['cleverreach_listen_id'] = array
(
'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_listen_id'],
'inputType'               => 'text',
'eval'                    => array('tl_class'=>'long', 'rgxp' => 'digit')
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['subscribeplus_inputs'] = array(
	'label'										=>	&$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribeplus_inputs'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'options_callback'        => array('tl_newsletter_channel_plus', 'getAvailableSubscribtionInputs'),
	'eval'                    => array('multiple'=>true, 'tl_class'=>'wizard')
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['subscribeplus_required_inputs'] = array(
	'label'										=>	&$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribeplus_required_inputs'],
	'exclude'                 => true,
	'inputType'               => 'checkboxWizard',
	'options_callback'        => array('tl_newsletter_channel_plus', 'getAvailableSubscribtionInputs'),
	'eval'                    => array('multiple'=>true, 'tl_class'=>'wizard')
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['nl_sender_name'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_sender_name'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('tl_class' => 'w50'),
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['nl_sender_mail'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_sender_mail'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('tl_class' => 'w50', 'rgxp'=>'email'),
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['nl_subject'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribeplus_subject'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'tl_class' => 'clr long', 'decodeEntities'=>true, 'alwaysSave'=>true),
	'load_callback' => array
	(
		array('tl_newsletter_channel_plus', 'getSubjectDefault')
	)
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['nl_subject'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_subject'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'tl_class' => 'long', 'decodeEntities'=>true, 'alwaysSave'=>true),
	'load_callback' 					=> array(array('tl_newsletter_channel_plus', 'getDefaultSubject'))
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['nl_text'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_text'],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('mandatory'=>true, 'tl_class' => 'long', 'style'=>'height:120px;', 'decodeEntities'=>true, 'alwaysSave'=>true),
	'load_callback' 					=> array(array('tl_newsletter_channel_plus', 'getDefaultText'))
);

$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['checkbox_label'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['checkbox_label'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'tl_class' => 'long', 'decodeEntities'=>true, 'alwaysSave'=>true),
	'load_callback' 					=> array(array('tl_newsletter_channel_plus', 'getDefaultCheckboxLabel'))
);


$GLOBALS['TL_DCA']['tl_newsletter_channel']['fields']['channel_page'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_newsletter_channel']['channel_page'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'eval'                    => array('fieldType'=>'radio', 'mandatory'=>true, 'tl_class'=>'clr')
);

class tl_newsletter_channel_plus extends Backend
{

	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		$this->loadLanguageFile('tl_subscribe_plus');
		$this->loadDataContainer('tl_subscribe_plus');
	}

	public function getAvailableSubscribtionInputs()
	{
		$options = array();

		foreach ($GLOBALS['TL_DCA']['tl_subscribe_plus']['fields'] as $k=>$v)
		{
			if ($v['eval']['beEditable'])
			{
				$options[$k] = $GLOBALS['TL_DCA']['tl_subscribe_plus']['fields'][$k]['label'][0];
			}
		}
		return $options;
	}
	
	public function getDefaultSubject($varValue){
		if (!trim($varValue))
		{
			$varValue = $GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_subject_default'][1];
		}
		return $varValue;
	}
	
	public function getDefaultText($varValue){
		if (!trim($varValue))
		{
			$varValue = $GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_text_default'][1];
		}
		return $varValue;
	}
	
	public function getDefaultCheckboxLabel($varValue, DC_Table $dc)
	{
		if (!trim($varValue))
		{
			$varValue = sprintf($GLOBALS['TL_LANG']['tl_newsletter_channel']['checkbox_label_default'], $dc->activeRecord->title);
		}
		return $varValue;
	}
}