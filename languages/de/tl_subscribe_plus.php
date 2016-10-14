<?php

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_subscribe_plus']['sex'] = array('Anrede', 'Das Geschlecht des Newsletter Abonnenten.');
$GLOBALS['TL_LANG']['tl_subscribe_plus']['sex']['male'] = 'Herr';
$GLOBALS['TL_LANG']['tl_subscribe_plus']['sex']['female'] = 'Frau';


$GLOBALS['TL_LANG']['tl_subscribe_plus']['title'] = array('Titel', 'Der akademische Titel des Newsletter Abonnenten.');
$GLOBALS['TL_LANG']['tl_subscribe_plus']['title']['dr'] = 'Dr.';
$GLOBALS['TL_LANG']['tl_subscribe_plus']['title']['prof.dr'] = 'Prof. Dr.';

$GLOBALS['TL_LANG']['tl_subscribe_plus']['firstname'] = array('Ihr Vorname', 'Der Vorname des Newsletter Abonnenten.');
$GLOBALS['TL_LANG']['tl_subscribe_plus']['lastname'] = array('Ihr Nachname', 'Der Nachname des Newsletter Abonnenten.');
$GLOBALS['TL_LANG']['tl_subscribe_plus']['company'] = array('Firma', 'Die Firma des Newsletter Abonnenten.');
$GLOBALS['TL_LANG']['tl_subscribe_plus']['email'] = array('Ihre E-Mail-Adresse', 'Die E-Mail des Newsletter Abonnenten.');
$GLOBALS['TL_LANG']['tl_subscribe_plus']['emailPlaceHolder'] = 'IhreMail@email.de';
$GLOBALS['TL_LANG']['tl_subscribe_plus']['street'] = array('Ihre Straße und Hausnummer', 'Die Straße und Hausnummer des Newsletter Abonnenten.');
$GLOBALS['TL_LANG']['tl_subscribe_plus']['ziploc'] = array('Ihre PLZ und Ort', 'Die PLZ und Ort des Newsletter Abonnenten.');
$GLOBALS['TL_LANG']['tl_subscribe_plus']['phone'] = array('Ihre Telefonnummer', 'Die Telefonnummer des Newsletter Abonnenten.');
$GLOBALS['TL_LANG']['tl_subscribe_plus']['comment'] = array('Bemerkungen', 'Die Bemerkungen des Newsletter Abonnenten.');

/**
 * E-Mail Message Keys
 */
$GLOBALS['TL_LANG']['tl_subscribe_plus']['salutation']['male'] = "geehrter Herr %s,";
$GLOBALS['TL_LANG']['tl_subscribe_plus']['salutation']['female'] = "geehrte Frau %s,";
$GLOBALS['TL_LANG']['tl_subscribe_plus']['salutation']['neutral'] = "geehrter Nutzer,";

$GLOBALS['TL_LANG']['tl_subscribe_plus']['shopEmailSubject'] = "Anmeldung ##channels##";

$GLOBALS['TL_LANG']['tl_subscribe_plus']['shopEmailText'] = "Sehr ##salutation##\n\nvielen Dank für Ihre Bestellung der ##channels##. Zur finalen Bestätigung klicken Sie bitte auf den unten stehenden Link. So stellen wir sicher, dass der Newsletter von Ihnen persönlich bestellt wurde.\n\n##link##\n\nIhre Bayerische Eisenbahngesellschaft\n\nBayerische Eisenbahngesellschaft mbH\nBoschetsrieder Straße 69 | 81379 München\nTelefon: 089 748825-0 | Telefax: 089 748825-51\nE-Mail: info@bahnland-bayern.de\n\nAufsichtsratsvorsitzender: Martin Zeil | Geschäftsführer: Fritz Czeschka\nSitz der Gesellschaft: München | Amtsgericht München, HRB 111279\n\n\nwww.bahnland-bayern.de | www.bayern-fahrplan.de | www.bahnland-bayern.de/beg";


/**
 *  Error Messages
 */

$GLOBALS['TL_LANG']['ERR']['subscribed_single'] = "Sie haben den Verteiler '%s' bereits abonniert.";

/**
 * Placeholders
 */
$GLOBALS['TL_LANG']['tl_subscribe_plus']['placeholders']['email'] = 'E-mail';