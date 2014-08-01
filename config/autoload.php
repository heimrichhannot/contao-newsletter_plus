<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Newsletter_plus
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'HeimrichHannot',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'HeimrichHannot\NewsletterPlus\NlpInputHelper'             => 'system/modules/newsletter_plus/NlpInputHelper.php',
	'HeimrichHannot\NewsletterPlus\Subscriber'                 => 'system/modules/newsletter_plus/Subscriber.php',
	'HeimrichHannot\NewsletterPlus\NewsletterFormSubscribe'    => 'system/modules/newsletter_plus/NewsletterFormSubscribe.php',
	'HeimrichHannot\NewsletterPlus\ChannelWizard'              => 'system/modules/newsletter_plus/ChannelWizard.php',
	'HeimrichHannot\NewsletterPlus\NewsletterPlus'             => 'system/modules/newsletter_plus/NewsletterPlus.php',
	'HeimrichHannot\NewsletterPlus\CleverRearchSoapHelper'     => 'system/modules/newsletter_plus/CleverRearchSoapHelper.php',
	'HeimrichHannot\NewsletterPlus\ModuleSubscribePlus'        => 'system/modules/newsletter_plus/ModuleSubscribePlus.php',
	'HeimrichHannot\NewsletterPlus\ChannelFieldWizard'         => 'system/modules/newsletter_plus/ChannelFieldWizard.php',
	'HeimrichHannot\NewsletterPlus\ModuleRegistrationPlus'     => 'system/modules/newsletter_plus/ModuleRegistrationPlus.php',
	'HeimrichHannot\NewsletterPlus\ModuleUnsubscribePlus'      => 'system/modules/newsletter_plus/ModuleUnsubscribePlus.php',
	'HeimrichHannot\NewsletterPlus\NewsletterFormSubscribeMsg' => 'system/modules/newsletter_plus/NewsletterFormSubscribeMsg.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'nl_default'               => 'system/modules/newsletter_plus/templates',
	'nl_subscribe_plus_toggle' => 'system/modules/newsletter_plus/templates',
	'nl_unsubscribe_plus'      => 'system/modules/newsletter_plus/templates',
));
