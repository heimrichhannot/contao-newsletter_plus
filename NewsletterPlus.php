<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

class NewsletterPlus extends Newsletter
{

	public function __construct()
	{
		parent::__construct();
	}

	public function createNewUser($userId, $arrData)
	{
		$arrNewsletters = deserialize($arrData['newsletter'], true);

		// Return if there are no newsletters
		if (!is_array($arrNewsletters))
		{
			return;
		}

		$time = time();

		// Add recipients
		foreach ($arrNewsletters as $intNewsletter)
		{
			$intNewsletter = intval($intNewsletter);

			if ($intNewsletter < 1)
			{
				continue;
			}

			$objRecipient = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_newsletter_recipients WHERE pid=? AND email=?")
			->execute($intNewsletter, $arrData['email']);

			if ($objRecipient->total < 1)
			{
				$subscriber = new Subscriber($arrData['email']);
				$subscriber->tstamp = $time;
				$subscriber->salutation = $arrData['gender'];
				$subscriber->firstname = $arrData['firstname'];
				$subscriber->lastname = $arrData['lastname'];
				$subscriber->company = $arrData['company'];
				$subscriber->addedOn = $time;
				$subscriber->ip = $this->anonymizeIp($this->Environment->ip);
				$subscriber->pid = $intNewsletter;
				$subscriber->active = ''; 
				$subscriber->token = ''; // account will get activated by registration token from registration E-Mail
				$subscriber->registered = $time;
				$subscriber->add();
			}
		}
	}
}