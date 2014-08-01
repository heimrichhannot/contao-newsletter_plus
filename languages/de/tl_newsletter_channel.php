<?php

$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribe_plus_legend'] = 'Abonnieren Plus Konfiguration';
$GLOBALS['TL_LANG']['tl_newsletter_channel']['unsubscribe_plus_legend'] = 'Abbestellen Plus Konfiguration';

$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribeplus_inputs'] = array('Eingabefelder', 'Wählen Sie zusätzlich benötigte Eingabefelder für das Newsletter Formular aus.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['subscribeplus_required_inputs'] = array('Erforderliche Eingabefelder', 'Wählen Sie aus den zusätzlich benötigten Eingabefelder die benötigten Felder.');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_legend'] = 'CleverReach Konfiguration';
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_active'] = array('CleaverReach Support aktivieren', 'Soll CleverReach für diesen Newsletter, als Dienst aktiviert werden?');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_wsdl_url'] = array('WSDL-URL', 'Im CleverReach Backend zu finden unter "Empfänger->Gruppen->GRUPPENNAME->Einstellungen->API Key".');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_api_key'] = array('API Key', 'Im CleverReach Backend zu finden unter "Empfänger->Gruppen->GRUPPENNAME->Einstellungen->API Key".');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['cleverreach_listen_id'] = array('Listen ID', 'Im CleverReach Backend zu finden unter "Empfänger->Gruppen->GRUPPENNAME->Einstellungen->API Key".');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['checkbox_label'] = array('Checkbox Label', 'Der Text für die Checkbox, die dem Nutzer im Frontend in Anmeldeformularen angezeigt wird.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['checkbox_label_default'] = "Ja, ich möchte zukünftig %s regelmäßig per E-Mail erhalten.";
$GLOBALS['TL_LANG']['tl_newsletter_channel']['channel_page'] = array('Seite zum Abmelde-Formular', 'Für externe Module wie den Webshop ist es notwendig, dass die Newsletter-Aktivierung auf eine Seite verweißt in der das Subscribe Plus Modul hinterlegt ist.');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_subject'] = array('E-Mail Betreff', 'Hinterlegen Sie den Betreff der Opt-In E-Mail');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_text'] = array('E-Mail Text', 'Hinterlegen Sie den Text der Opt-In E-Mail');

$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_sender_name'] = array('Absender Name', 'Der Name der für diesen Verteiler stets als Absender angezeigt werden soll (Registrierung, Abmeldung etc), wenn leer dann wird die E-Mail-Adresse des Systemadministrators aus den Contao Einstellungen verwendet.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_sender_mail'] = array('Absender E-Mail-Adresse', 'Die E-Mail die für diesen Verteiler stets als Absender angezeigt werden soll (Registrierung, Abmeldung etc), wenn leer dann wird die E-Mail-Adresse des Systemadministrators aus den Contao Einstellungen verwendet.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_unsubscribe_subject'] = array('E-Mail Betreff', 'Hinterlegen Sie den Betreff der Opt-In E-Mail');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_unsubscribe_sender_name'] = array('Absender Name', 'Der Name der für diesen Verteiler stets als Absender angezeigt werden soll (Registrierung, Abmeldung etc), wenn leer dann wird die E-Mail-Adresse des Systemadministrators aus den Contao Einstellungen verwendet.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_unsubscribe_sender_mail'] = array('Absender E-Mail-Adresse', 'Die E-Mail die für diesen Verteiler stets als Absender angezeigt werden soll (Registrierung, Abmeldung etc), wenn leer dann wird die E-Mail-Adresse des Systemadministrators aus den Contao Einstellungen verwendet.');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_unsubscribe_text'] = array('E-Mail Text', 'Hinterlegen Sie den Text der Opt-Out E-Mail');


$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_subject_default'] = array('Anmeldung', 'Anmeldung ##channels##');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_text_default'] = array('Text', "Sehr ##salutation##\n\nvielen Dank für Ihre Bestellung der ##channels##. Zur finalen Bestätigung klicken Sie bitte auf den unten stehenden Link. So stellen wir sicher, dass der Newsletter von Ihnen persönlich bestellt wurde.\n\n##link##\n\n");
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_unsubscribe_subject_default'] = array('Abmeldung', 'Abmeldung ##channels##');
$GLOBALS['TL_LANG']['tl_newsletter_channel']['nl_unsubscribe_text_default'] = array('E-Mail Text', "Sehr ##salutation##\n\nSie haben folgendes Newsletter-Abonnement gekündigt:\n\n##channels##\n\nWir werden Ihnen keine weiteren Newsletter-Ausgaben des '##channels##' zusenden.");