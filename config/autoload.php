<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
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
	// Modules
	'HeimrichHannot\NewsletterPlus\ModuleSubscribePlus'        => 'system/modules/newsletter_plus/modules/ModuleSubscribePlus.php',
	'HeimrichHannot\NewsletterPlus\ModuleRegistrationPlus'     => 'system/modules/newsletter_plus/modules/ModuleRegistrationPlus.php',
	'HeimrichHannot\NewsletterPlus\ModuleUnsubscribePlus'      => 'system/modules/newsletter_plus/modules/ModuleUnsubscribePlus.php',
	'HeimrichHannot\NewsletterPlus\ModuleNewsletterArchive'    => 'system/modules/newsletter_plus/modules/ModuleNewsletterArchive.php',

	// Widgets
	'HeimrichHannot\NewsletterPlus\NewsletterFormSubscribe'    => 'system/modules/newsletter_plus/widgets/NewsletterFormSubscribe.php',
	'HeimrichHannot\NewsletterPlus\ChannelWizard'              => 'system/modules/newsletter_plus/widgets/ChannelWizard.php',
	'HeimrichHannot\NewsletterPlus\ChannelFieldWizard'         => 'system/modules/newsletter_plus/widgets/ChannelFieldWizard.php',
	'HeimrichHannot\NewsletterPlus\NewsletterFormSubscribeMsg' => 'system/modules/newsletter_plus/widgets/NewsletterFormSubscribeMsg.php',
	// Classes
	'HeimrichHannot\NewsletterPlus\NlpInputHelper'             => 'system/modules/newsletter_plus/classes/NlpInputHelper.php',
	'HeimrichHannot\NewsletterPlus\Subscriber'                 => 'system/modules/newsletter_plus/classes/Subscriber.php',
	'HeimrichHannot\NewsletterPlus\NewsletterPlus'             => 'system/modules/newsletter_plus/classes/NewsletterPlus.php',
	'HeimrichHannot\NewsletterPlus\CleverRearchSoapHelper'     => 'system/modules/newsletter_plus/classes/CleverRearchSoapHelper.php',
	'HeimrichHannot\NewsletterPlus\NewsArchive'                => 'system/modules/newsletter_plus/classes/NewsArchive.php'
));
/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'nl_default'               => 'system/modules/newsletter_plus/templates',
	'nl_subscribe_plus_toggle' => 'system/modules/newsletter_plus/templates',
	'mod_newsletter_archive'   => 'system/modules/newsletter_plus/templates',
	'nl_unsubscribe_plus'      => 'system/modules/newsletter_plus/templates',
));

