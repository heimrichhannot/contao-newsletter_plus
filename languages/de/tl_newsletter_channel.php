<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribe_plus_legend'] = 'Abonnieren Plus Konfiguration';

$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribeplus_inputs'] = array('Eingabefelder', 'Wählen Sie zusätzlich benötigte Eingabefelder für das Newsletter Formular aus.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribeplus_required_inputs'] = array('Erforderliche Eingabefelder', 'Wählen Sie aus den zusätzlich benötigten Eingabefelder die benötigten Felder.');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_legend'] = 'CleverReach Konfiguration';
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_active'] = array('CleaverReach Support aktivieren', 'Soll CleverReach für diesen Newsletter, als Dienst aktiviert werden?');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_wsdl_url'] = array('WSDL-URL', 'Im CleverReach Backend zu finden unter "Empfänger->Gruppen->GRUPPENNAME->Einstellungen->API Key".');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_api_key'] = array('API Key', 'Im CleverReach Backend zu finden unter "Empfänger->Gruppen->GRUPPENNAME->Einstellungen->API Key".');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_listen_id'] = array('Listen ID', 'Im CleverReach Backend zu finden unter "Empfänger->Gruppen->GRUPPENNAME->Einstellungen->API Key".');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['checkbox_label'] = array('Checkbox Label', 'Der Text für die Checkbox, die dem Nutzer im Frontend in Anmeldeformularen angezeigt wird.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['checkbox_label_default'] = "Ja, ich möchte zukünftig %s regelmäßig per E-Mail erhalten.";
$GLOBALS['TL_LANG']['tl_newsletter_channel']['channel_page'] = array('Kanal Seite', 'Für externe Module wie den Webshop ist es notwendig, dass die Newsletter-Aktivierung auf eine Seite verweißt in der das Subscribe Plus Modul hinterlegt ist.');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_subject'] = array('E-Mail Betreff', 'Hinterlegen Sie den Betreff der Opt-In E-Mail');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_text'] = array('E-Mail Text', 'Hinterlegen Sie den Text der Opt-In E-Mail');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_sender_name'] = array('Absender Name', 'Der Name der für diesen Verteiler stets als Absender angezeigt werden soll (Registrierung, Abmeldung etc), wenn leer dann wird die E-Mail-Adresse des Systemadministrators aus den Contao Einstellungen verwendet.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_sender_mail'] = array('Absender E-Mail-Adresse', 'Die E-Mail die für diesen Verteiler stets als Absender angezeigt werden soll (Registrierung, Abmeldung etc), wenn leer dann wird die E-Mail-Adresse des Systemadministrators aus den Contao Einstellungen verwendet.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_subject_default'] = array('Anmeldung', 'Anmeldung ##channels##');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_text_default'] = array('Text', "Sehr ##salutation##\n\nvielen Dank für Ihre Bestellung der ##channels##. Zur finalen Bestätigung klicken Sie bitte auf den unten stehenden Link. So stellen wir sicher, dass der Newsletter von Ihnen persönlich bestellt wurde.\n\n##link##\n\nIhre Bayerische Eisenbahngesellschaft\n\nBayerische Eisenbahngesellschaft mbH\nBoschetsrieder Straße 69 | 81379 München\nTelefon: 089 748825-0 | Telefax: 089 748825-51\nE-Mail: info@bahnland-bayern.de\n\nAufsichtsratsvorsitzender: Martin Zeil | Geschäftsführer: Fritz Czeschka\nSitz der Gesellschaft: München | Amtsgericht München, HRB 111279\n\n\nwww.bahnland-bayern.de | www.bayern-fahrplan.de | www.bahnland-bayern.de/beg");