<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');


array_insert($GLOBALS['BE_FFL'], 4, array
(
	'channelWizard'   		=> 'ChannelWizard',
	'channelFieldWizard'	=> 'ChannelFieldWizard'
));

//$GLOBALS['FE_MOD']['user']['registration'] = 'ModuleRegistrationPlus';

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['newsletter']['subscribe'] = 'ModuleSubscribePlus';
$GLOBALS['FE_MOD']['newsletter']['unsubscribe'] = 'ModuleUnsubscribePlus';


$GLOBALS['TL_HOOKS']['processFormData'][] = array('NewsletterFormSubscribe', 'processSubmittedData');

if(is_array($GLOBALS['TL_HOOKS']['createNewUser']))
{
	foreach($GLOBALS['TL_HOOKS']['createNewUser'] as $key => $hook)
	{
		if($hook[0] == 'Newsletter')
		{
			$GLOBALS['TL_HOOKS']['createNewUser'][$key] = array('NewsletterPlus', 'createNewUser');
			break;
		}
	}
}