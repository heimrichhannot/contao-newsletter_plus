<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

$GLOBALS['TL_DCA']['tl_member']['fields']['newsletter']['options_callback'] = array('tl_member_nlplus', 'getNewsletters');


class tl_member_nlplus extends Backend
{
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	public function getNewsletters($objModule)
	{
		$objNewsletter = $this->Database->execute("SELECT id, title, checkbox_label FROM tl_newsletter_channel");

		if ($objNewsletter->numRows < 1)
		{
			return array();
		}

		$arrNewsletters = array();

		// Back end
		if (TL_MODE == 'BE')
		{
			while ($objNewsletter->next())
			{
				$arrNewsletters[$objNewsletter->id] = TL_MODE == 'FE' ? $objNewsletter->checkbox_label : $objNewsletter->title;
			}

			return $arrNewsletters;
		}

		// Front end
		$newsletters = deserialize($objModule->newsletters, true);

		if (!is_array($newsletters) || empty($newsletters))
		{
			return array();
		}

		while ($objNewsletter->next())
		{
			if (in_array($objNewsletter->id, $newsletters))
			{
				$arrNewsletters[$objNewsletter->id] = TL_MODE == 'FE' ? $objNewsletter->checkbox_label : $objNewsletter->title;
			}
		}

		return $arrNewsletters;
	}
}