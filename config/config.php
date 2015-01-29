<?php


array_insert($GLOBALS['BE_FFL'], 4, array
(
	'channelWizard'   		=> 'HeimrichHannot\NewsletterPlus\ChannelWizard',
	'channelFieldWizard'	=> 'HeimrichHannot\NewsletterPlus\ChannelFieldWizard'
));

//$GLOBALS['FE_MOD']['user']['registration'] = 'ModuleRegistrationPlus';

/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['newsletter']['subscribe_plus'] = 'HeimrichHannot\NewsletterPlus\ModuleSubscribePlus';
$GLOBALS['FE_MOD']['newsletter']['unsubscribe_plus'] = 'HeimrichHannot\NewsletterPlus\ModuleUnsubscribePlus';


$GLOBALS['TL_HOOKS']['processFormData'][] = array('HeimrichHannot\NewsletterPlus\NewsletterFormSubscribe', 'processSubmittedData');

if(is_array($GLOBALS['TL_HOOKS']['createNewUser']))
{
	foreach($GLOBALS['TL_HOOKS']['createNewUser'] as $key => $hook)
	{
		if($hook[0] == 'Newsletter')
		{
			$GLOBALS['TL_HOOKS']['createNewUser'][$key] = array('HeimrichHannot\NewsletterPlus\NewsletterPlus', 'createNewUser');
			break;
		}
	}
}