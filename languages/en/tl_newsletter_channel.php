<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribe_plus_legend'] = 'Subscribe Plus configuration';

$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribeplus_inputs'] = array('Input fields', 'Select additional required fields for the newsletter form.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribeplus_required_inputs'] = array('Required fields', 'Select the additional required fields.');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_legend'] = 'CleverReach configuration';
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_active'] = array('Enable CleaverReach Support', 'Should the Cleaverreach service be enabled for this newsletter?');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_wsdl_url'] = array('WSDL-URL', 'To Find in the Cleaverreach Backend under "Empfänger->Gruppen->GRUPPENNAME->Einstellungen->API Key".');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_api_key'] = array('API Key', 'To Find in the Cleaverreach Backend under "Empfänger->Gruppen->GRUPPENNAME->Einstellungen->API Key".');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_listen_id'] = array('Listen ID', 'To Find in the Cleaverreach Backend under  "Empfänger->Gruppen->GRUPPENNAME->Einstellungen->API Key".');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_subject'] = array('E-Mail Subject', 'Define the subject of the opt-in e-mail.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_text'] = array('E-Mail Text', 'Define the text of the opt-in e-mail.');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_sender_name'] = array('Sender Name', 'The name that will always be displayed as the sender (registration, cancellation, etc), if empty then the e-mail address of the system administrator from the Contao settings is used.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_sender_mail'] = array('Sender E-Mail-address', 'The e-mail that will always be displayed as the sender (registration, cancellation, etc), if empty then the e-mail address of the system administrator from the Contao settings is used.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_subject_default'] = array('Subscription', 'Subscription ##channels##');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_text_default'] = array('Text', "Dear ##salutation##\n\nthank you for your subscription to the ##channels##. For final confirmation, please click on the link below. So we make sure that the newsletter was ordered by you personally.\n\n##link##\n\nYour Bayerische Eisenbahngesellschaft\n\nBayerische Eisenbahngesellschaft mbH\nBoschetsrieder Straße 69 | 81379 München\nFon: 089 748825-0 | Fax: 089 748825-51\nE-Mail: info@bahnland-bayern.de\n\nChairman of the Board: Martin Zeil | Manager: Fritz Czeschka\nSitz der Gesellschaft: München | District Court of Munich, HRB 111279\n\n\nwww.bahnland-bayern.de | www.bayern-fahrplan.de | www.bahnland-bayern.de/beg");