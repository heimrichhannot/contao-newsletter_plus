<?php

namespace HeimrichHannot\NewsletterPlus;

class Subscriber extends \Controller
{
	public $email;
	public $pid;
	public $tstamp;
	public $active;
	public $addedOn;
	public $ip;
	public $token;
	public $registered;
	public $activated = 0;
	public $deactivated = 0;
	public $source;
	public $firstname;
	public $lastname;
	public $salutation;
	public $title;
	public $company;
	public $street;
	public $ziploc;
	public $phone;
	public $comment;

	private static $crsuccess = "SUCCESS";

	public function __construct($email)
	{
		parent::__construct();
		$this->email = $email;
		\Controller::loadLanguageFile('tl_subscribe_plus');
	}

	/**
	 * Remove old subscriptions that have not been activated yet
	 */
	public function dropInactiveSubscriptions()
	{
		// Remove old subscriptions that have not been activated yet
		\Database::getInstance()->prepare("DELETE FROM tl_newsletter_recipients WHERE email=? AND active!=1")
														->execute($this->email, $this->pid);
	}

	public function getByChannel($pid)
	{
		if(!is_numeric($pid))
		{
			return false;
		}

		$objSubscriber = \Database::getInstance()->prepare('SELECT * FROM tl_newsletter_recipients WHERE email = ? AND pid = ?')->limit(1)->execute($this->email, $pid);

		if($objSubscriber->numRows)
		{
			foreach($objSubscriber->fetchAssoc() as $key => $value)
			{
				$this->{$key} = $value;
			}
		}
	}

	public function getById($id)
	{
		$objSubscriber = \Database::getInstance()->prepare('SELECT * FROM tl_newsletter_recipients WHERE id = ?')->limit(1)->execute($id);

		if($objSubscriber->numRows)
		{
			foreach($objSubscriber->fetchAssoc() as $key => $value)
			{
				$this->{$key} = $value;
			}
		}
	}

	public function setByDc(\DataContainer $dc, $overwrite=false)
	{
		foreach($this as $key => $value)
		{
			if(!empty($this->{$key}) && !$overwrite) continue;
			$this->{$key} = $dc->activeRecord->{$key};
		}
	}

	/**
	 * add new Subscriber to Contao Database
	 */
	public function add()
	{
		$stmt = "INSERT INTO tl_newsletter_recipients
						 (pid, tstamp, email, active, addedOn, ip, token, salutation, title, firstname, lastname, company, street, ziploc, phone, comment)
						 VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		\Database::getInstance()->prepare($stmt)->execute(
			$this->pid,
			$this->tstamp,
			$this->email,
			$this->active,
			$this->addedOn,
			$this->ip,
			$this->token,
			$this->salutation,
			$this->title,
			$this->firstname,
			$this->lastname,
			$this->company,
			$this->street,
			$this->ziploc,
			$this->phone,
			$this->comment
		);

		// Log activity

		\Controller::log($this->email . ' subscribed to Channel with ID: ' . $this->pid, 'Subscriber add()', TL_NEWSLETTER);
	}

	public function remove($channels)
	{
		// Remove subscriptions
		\Database::getInstance()->prepare("DELETE FROM tl_newsletter_recipients WHERE email=? AND pid IN(" . implode(',', array_map('intval', $channels)) . ")")
		->execute($this->email);

		// Log activity
		\Controller::log($this->email . ' unsubscribed from channels with IDs: ' . implode(', ', $channels), 'Subscriber remove()', TL_NEWSLETTER);
	}

	public function activateByToken($token)
	{
		// Check the token
		$objRecipient = \Database::getInstance()->prepare("SELECT r.id, r.email, c.id AS cid, c.title, c.cleverreach_active FROM tl_newsletter_recipients r LEFT JOIN tl_newsletter_channel c ON r.pid=c.id WHERE token=?")
		->execute($token);

		if ($objRecipient->numRows < 1)
		{
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['invalidToken'];
			$_SESSION['SUBSCRIBED'] = true;
			return;
		}

		$this->email = $objRecipient->email;

		$arrAdd = $objRecipient->fetchEach('id');
		$arrChannels = $objRecipient->fetchEach('title');
		$arrCids = $objRecipient->fetchEach('cid');
		$arrCR = $objRecipient->fetchEach('cleverreach_active');

		foreach($arrCids as $key => $cid)
		{
			if($arrCR[$key]['cleverreach_active']	== 1){
				$this->pid = $cid;
				$this->activateCR();
			}
		}

		// Update subscriptions
		\Database::getInstance()->prepare("UPDATE tl_newsletter_recipients SET active=1, token='' WHERE token=?")
		->execute($token);

		// Log activity
		\Controller::log($objRecipient->email . ' has subscribed to ' . implode(', ', $arrChannels), 'Subscriber activateByToken()', TL_NEWSLETTER);

		// HOOK: post activation callback
		if (isset($GLOBALS['TL_HOOKS']['activateRecipient']) && is_array($GLOBALS['TL_HOOKS']['activateRecipient']))
		{
			$arrCids = $objRecipient->fetchEach('cid');

			foreach ($GLOBALS['TL_HOOKS']['activateRecipient'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objRecipient->email, $arrAdd, $arrCids);
			}
		}

		// Confirm activation
		$_SESSION['SUBSCRIBE_CONFIRM'] = $GLOBALS['TL_LANG']['MSC']['nl_activate'];
		$_SESSION['SUBSCRIBED'] = true; // tell the module (multiple form on one page), that activation is already done
	}


	public function addFromExtForm($channels, $subject = '', $text = '')
	{
		if (!is_array($channels) || count($channels) < 1)
		{
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['noChannels'];
			return false;
		}

		// Get active subscriptions
		$arrSub = $this->getActiveSubscriptionIds();

		$arrNew = array_diff($channels, $arrSub);

		// Return if there are no new subscriptions
		if (!is_array($arrNew) || count($arrNew) < 1)
		{
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['subscribed'];
			return false;
		}

		// Remove old subscriptions that have not been activated yet
		$this->dropInactiveSubscriptions();

		$time = time();
		$strToken = md5(uniqid(mt_rand(), true));

		foreach ($arrNew as $id)
		{
			$this->tstamp = $time;
			$this->pid = $id;
			$this->addedOn = $time;
			$this->ip = $this->Environment->ip;
			$this->token = $strToken;
			$this->active = '';
			$this->registered = $time;
			$this->add();

			$objChannel = \Database::getInstance()->prepare("SELECT id FROM tl_newsletter_channel WHERE id = ? and cleverreach_active = 1")->limit(1)->execute($id);

			if($objChannel->numRows > 0)
			{
				$this->addToCR();
			}
		}

		if($this->sendActivationMail($arrNew, $subject, $text))
		{
			return true;
		}
	}

	public function sendActivationMail($channels, $subject='', $text='')
	{
		$objChannel = \Database::getInstance()->prepare("SELECT * FROM tl_newsletter_channel WHERE id IN (".implode(',', $channels).")")->limit(1)->execute();

		$objEmail = new \Email();

		if(empty($subject))
		{
			$subject = $objChannel->first()->nl_subject;
		}

		if(empty($text))
		{
			$text = $objChannel->first()->nl_text;
		}

		$strSubject = str_replace(array('##channel##', '##channels##'), implode(",", $objChannel->fetchEach('title')) , $subject);
		$strText = str_replace('##salutation##', $this->getSalutation() , $text);
		$strText = str_replace('##domain##', \Idna::decode(\Environment::get('host')), $strText);
		$strText = str_replace('##link##', \Idna::decode(\Environment::get('base')) . \Environment::get('request') . ((\Config::get('disableAlias') || strpos(\Environment::get('request'), '?') !== false) ? '&' : '?') . 'token=' . $this->token, $strText);
		$strText = str_replace(array('##channel##', '##channels##'), implode("\n", $objChannel->fetchEach('title')), $strText);

		$objEmail->from = $objChannel->first()->nl_sender_mail ?  $objChannel->first()->nl_sender_mail : $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->fromName = $objChannel->first()->nl_sender_name ?  $objChannel->first()->nl_sender_name : $GLOBALS['TL_ADMIN_NAME'];
		$objEmail->subject = $this->replaceInsertTags($strSubject);
		$objEmail->text = $this->replaceInsertTags($strText);

		if($objEmail->sendTo($this->email))
		{
			$_SESSION['SUBSCRIBE_CONFIRM'] = $GLOBALS['TL_LANG']['MSC']['nl_confirm'];
			return true;
		}

		return false;
	}

	public function sendUnSubscribeMail($channels, $subject='', $text='')
	{
		$objChannel = \Database::getInstance()->prepare("SELECT * FROM tl_newsletter_channel WHERE id IN (".implode(',', $channels).")")->limit(1)->execute();

		$objEmail = new \Email();

		if(empty($subject))
		{
			$subject = $objChannel->first()->nl_unsubscribe_subject;
		}

		if(empty($text))
		{
			$text = $objChannel->first()->nl_unsubscribe_text;
		}

		ob_start();
		print_r($this);
		print "\n";
		file_put_contents(TL_ROOT . '/debug.txt', ob_get_contents(), FILE_APPEND);
		ob_end_clean();

		$strSubject = str_replace(array('##channel##', '##channels##'), implode(",", $objChannel->fetchEach('title')) , $subject);
		$strText = str_replace('##salutation##', $this->getSalutation() , $text);
		$strText = str_replace('##domain##', \Idna::decode(\Environment::get('host')), $strText);
		$strText = str_replace('##link##', \Idna::decode(\Environment::get('base')) . \Environment::get('request') . ((\Config::get('disableAlias') || strpos(\Environment::get('request'), '?') !== false) ? '&' : '?') . 'token=' . $this->token, $strText);
		$strText = str_replace(array('##channel##', '##channels##'), implode("\n", $objChannel->fetchEach('title')), $strText);

		$objEmail->from = $objChannel->first()->nl_unsubscribe_sender_mail ?  $objChannel->first()->nl_unsubscribe_sender_mail : $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->fromName = $objChannel->first()->nl_unsubscribe_sender_name ?  $objChannel->first()->nl_unsubscribe_sender_name : $GLOBALS['TL_ADMIN_NAME'];
		$objEmail->subject = $this->replaceInsertTags($strSubject);
		$objEmail->text = $this->replaceInsertTags($strText);

		if($objEmail->sendTo($this->email))
		{
			$_SESSION['UNSUBSCRIBE_CONFIRM'] = $GLOBALS['TL_LANG']['MSC']['nl_removed'];
			return true;
		}

		return false;
	}

	public function getActiveSubscriptionIds()
	{
		$stmt = "SELECT pid FROM tl_newsletter_recipients WHERE email=? AND active=1";

		$objSubscription = \Database::getInstance()->prepare($stmt)->execute($this->email);

		$subscriptions = array();

		if ($objSubscription->numRows)
		{
			$subscriptions = $objSubscription->fetchEach('pid');
		}

		return $subscriptions;
	}

	public function getSalutation()
	{
		if(strstr($this->salutation, 'male'))
		{
			return sprintf($GLOBALS['TL_LANG']['tl_subscribe_plus']['salutation'][$this->salutation], $this->lastname);
		}
		return sprintf($GLOBALS['TL_LANG']['tl_subscribe_plus']['salutation']['neutral'], $this->lastname);
	}

	public function addToCR()
	{
		$soap = new CleverRearchSoapHelper($this->pid);
		$this->setFieldsForCR();
		$result = $soap->add($this);
		if($result->status == self::$crsuccess)
		{
			\Controller::log($this->email . ' has subscribed to ' . $this->pid .' and successfully added to CleverReach with status inactive', 'Subscriber addToCR()', TL_NEWSLETTER);
		}
	}

	public function updateCR()
	{
		$soap = new CleverRearchSoapHelper($this->pid);
		$this->setFieldsForCR();
		$result = $soap->update($this);
		if($result->status == self::$crsuccess)
		{
			\Controller::log('Subscriber with E-Mail: '.$this->email . ' and channel ' . $this->pid .' successfully updated in CleverReach', 'Subscriber updateCR()', TL_NEWSLETTER);
		}
	}

	public function removeFromCR($channels)
	{
		foreach($channels as $cid)
		{
			$this->pid = $cid;
			$soap = new CleverRearchSoapHelper($cid);
			$result = $soap->delete($this);
			if($result->status == self::$crsuccess)
			{
				\Controller::log($this->email . ' has unsubscribed to ' . $cid .' and successfully removed from CleverReach', 'Subscriber removeFromCR()', TL_NEWSLETTER);
			}
		}
	}

	public function activateCR()
	{
		$soap = new CleverRearchSoapHelper($this->pid);
		$result = $soap->setActive($this);
		if($result->status == self::$crsuccess)
		{
			\Controller::log($this->email . ' has been successfully activated to CleverReach for Channel ID' . $this->pid , 'Subscriber activateCR()', TL_NEWSLETTER);
		}
	}

	public function deActivateCR()
	{
		$soap = new CleverRearchSoapHelper($this->pid);
		$result = $soap->setInActive($this);
		if($result->status == self::$crsuccess)
		{
			\Controller::log($this->email . ' has been successfully deactivated to CleverReach for Channel ID' . $this->pid , 'Subscriber deActivateCR()', TL_NEWSLETTER);
		}
	}

	protected function setFieldsForCR()
	{
		$this->salutation = $GLOBALS['TL_LANG']['tl_subscribe_plus']['sex'][$this->salutation];
		$this->title = $GLOBALS['TL_LANG']['tl_subscribe_plus']['title'][$this->title];
		$this->source = 'API';
	}

}