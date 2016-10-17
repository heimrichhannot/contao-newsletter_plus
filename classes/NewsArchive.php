<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @package ${CARET}
 * @author  Martin Kunitzsch <m.kunitzsch@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\NewsletterPlus;

class NewsArchive
{
	public function getArchives()
	{
		$arrArchives = array();

		$objArchives = \Database::getInstance()->prepare("SELECT *, YEAR(FROM_UNIXTIME(date)) as year FROM tl_newsletter ORDER BY date DESC")->execute();

		while($objArchives->next()) {
			$objNl = new \stdClass();
			$objNl->id = $objArchives->id;
			$objNl->alias = $objArchives->alias;
			$objNl->subject = $objArchives->subject;
			$objNl->summary = $objArchives->summary;
			$objNl->file = deserialize($objArchives->files, false)[0];

			$arrArchives[$objArchives->year][] = $objNl;
		}
		return $arrArchives;
	}
}