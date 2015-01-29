<?php

namespace HeimrichHannot\NewsletterPlus;

class ModuleSubscribePlus extends \ModuleSubscribe
{
	/**
	 * Template
	 * @var string
	 */
	protected $strFormId = 'tl_subscribe_plus';
	
	private $doNotSubmit = false;
	
	private $cleverreach = false;
	
	protected $subscriber;

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['subscribe'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;
			
			return $objTemplate->parse();
		}
		
		$this->nl_channels = deserialize($this->nl_channels);

		// Return if there are no channels
		if (!is_array($this->nl_channels) || count($this->nl_channels) < 1)
		{
			return '';
		}
		
		return parent::generate();
	}
	
	/**
	 * Generate module
	 */
	protected function compile()
	{
		// Overwrite default template
		if ($this->nl_template)
		{
			$this->Template = new \FrontendTemplate($this->nl_template);
			$this->Template->setData($this->arrData);
		}
		
		$arrChannels = array();
		$objChannel = $this->Database->execute("SELECT * FROM tl_newsletter_channel WHERE id IN(" . implode(',', array_map('intval', $this->nl_channels)) . ") ORDER BY title");
		
		// Get titles
		while ($objChannel->next())
		{
			$arrChannels[$objChannel->id] = $objChannel->title;
			$additionalInputs = deserialize($objChannel->subscribeplus_inputs);
			if(is_array($additionalInputs) && count($additionalInputs) > 0)
			{
				$submitted = $this->Input->post('FORM_SUBMIT') == $this->strFormId;
				$inputHelper = new NlpInputHelper($submitted);
				$forms = $inputHelper->addInputFields($objChannel);
				$this->doNotSubmit = $inputHelper->hasErrors();
			}
		}
		
		// Get Form Action "jumpTo" page
		$objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
		->limit(1)
		->execute($this->jumpTo);
		
		// remove token from request url, to prevent wrong token invalid messages
		$url = parse_url(\Environment::get('indexFreeRequest'));
		
		// Default template variables
		$this->Template->forms = $forms;
		$this->Template->channels = $arrChannels;
		$this->Template->showChannels = !$this->nl_hideChannels;
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['MSC']['subscribe']);
		$this->Template->channelsLabel = $GLOBALS['TL_LANG']['MSC']['nl_channels'];
		$this->Template->emailLabel = $GLOBALS['TL_LANG']['MSC']['emailAddress'];
		$this->Template->formId = $this->strFormId;
		$this->Template->id = $this->id;

		// Activate e-mail address
		if ($this->Input->get('token') && !$_SESSION['SUBSCRIBED'])
		{
			// formgen subscription > module subscription, prevent multiple activation
			$this->activateRecipient();
			return;
		}
		
		if($_SESSION['SUBSCRIBED'])
		{
			unset($_SESSION['SUBSCRIBED']);
		}
		
		// Subscribe
		if ($this->Input->post('FORM_SUBMIT') == $this->strFormId && !$this->doNotSubmit)
		{
			$this->addRecipient();
		}
		
		$blnHasError = false;
		
		// Error message
		if (strlen($_SESSION['SUBSCRIBE_ERROR']))
		{
			$blnHasError  = true;
			$this->Template->mclass = 'danger';
			$this->Template->message = $_SESSION['SUBSCRIBE_ERROR'];
			$_SESSION['SUBSCRIBE_ERROR'] = '';
		}
		
		// Confirmation message
		if (strlen($_SESSION['SUBSCRIBE_CONFIRM']))
		{
			$this->Template->mclass = 'success';
			$this->Template->message = $_SESSION['SUBSCRIBE_CONFIRM'];
			$_SESSION['SUBSCRIBE_CONFIRM'] = '';
		}

		$this->Template->action = ($objPage->numRows ? $this->generateFrontendUrl($objPage->row()) : $url['path']) . (($blnHasError || $this->doNotSubmit || $this->Template->message) ? '#' . $this->strFormId : '');
		$this->Template->hasError = ($blnHasError || $this->doNotSubmit);
	}
	

	/**
	 * Activate a recipient
	 */
	protected function activateRecipient()
	{
		global $objPage;

		// Check the token
		$objRecipient = $this->Database->prepare("SELECT r.id, r.email, c.id AS cid, c.title, c.cleverreach_active FROM tl_newsletter_recipients r LEFT JOIN tl_newsletter_channel c ON r.pid=c.id WHERE token=?")
									   ->execute($this->Input->get('token'));

		if ($objRecipient->numRows < 1)
		{
			$this->Template->mclass = 'danger';
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['invalidToken'];
			$_SESSION['SUBSCRIBED'] = true;
			$this->redirect($this->generateFrontendUrl($objPage->row()) . '#' . $this->strFormId);
		}
		
		$subscriber = new Subscriber($objRecipient->email);
		
		$arrAdd = $objRecipient->fetchEach('id');
		$arrChannels = $objRecipient->fetchEach('title');
		$arrCids = $objRecipient->fetchEach('cid');
		$arrCR = $objRecipient->fetchEach('cleverreach_active');

		if(is_array($arrCR) && !empty($arrCR))
		{
			foreach($arrCids as $key => $cid)
			{
				if(!isset($arrCR[$key])) continue;

				if($arrCR[$key]['cleverreach_active'] == 1)
				{
					$subscriber->pid = $cid;
					$subscriber->activateCR();
				}
			}
		}

		// Update subscriptions
		$this->Database->prepare("UPDATE tl_newsletter_recipients SET active=1, token='' WHERE token=?")
					   ->execute($this->Input->get('token'));

		// Log activity
		$this->log($objRecipient->email . ' has subscribed to ' . implode(', ', $arrChannels), 'ModuleSubscribe activateRecipient()', TL_NEWSLETTER);

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
		$this->Template->mclass = 'success';
		$_SESSION['SUBSCRIBE_CONFIRM'] = $GLOBALS['TL_LANG']['MSC']['nl_activate'];
		//prevent rendering messages twice a page
		$_SESSION['SUBSCRIBED'] = true;
		$this->redirect($this->generateFrontendUrl($objPage->row()) . '#' . $this->strFormId);
	}

	/**
	 * Add a new recipient
	 */
	protected function addRecipient()
	{
		global $objPage;
		$arrChannels = $this->Input->post('channels');
		$arrChannels = array_intersect($arrChannels, $this->nl_channels); // see #3240
		
		// Check the selection
		if (!is_array($arrChannels) || count($arrChannels) < 1)
		{
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['noChannels'];
			$this->reload();
		}
		
		$email = \Idna::encodeEmail($this->Input->post('email', true));
		
		$subscriber = new Subscriber($email);

		$arrSub = $subscriber->getActiveSubscriptionIds();
		
		// Get new subscriptions
		$arrNew = array_diff($arrChannels, $arrSub);
		
		// Return if there are no new subscriptions
		if (!is_array($arrNew) || count($arrNew) < 1)
		{
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['subscribed'];
			$this->redirect($this->generateFrontendUrl($objPage->row()) . '#' . $this->strFormId);
		}
		
		// Remove old subscriptions that have not been activated yet
		$subscriber->dropInactiveSubscriptions();

		$time = time();
		$strToken = md5(uniqid(mt_rand(), true));
		
		// check if additional input fields are required for this channel
		$objChannel = $this->Database->execute("SELECT * FROM tl_newsletter_channel WHERE id IN(" . implode(',', array_map('intval', $arrNew)) . ")");
		
		$addPlusFields = false;
		
		$channelInfo = array();
		
		while($objChannel->next())
		{
			$check = deserialize($objChannel->subscribeplus_inputs);
			if(is_array($check) && count($check) > 0)
			{
				$addPlusFields = true;
			}
			$channelInfo[$objChannel->id] = $objChannel->row();
		}
		
		foreach($arrNew as $cid)
		{
			$subscriber->tstamp = $time;
			$subscriber->pid = $cid;
			$subscriber->addedOn = $time;
			$subscriber->ip = $this->anonymizeIp($this->Environment->ip);
			$subscriber->token = $strToken;
			$subscriber->active = '';
			$subscriber->registered = $time;
			
			if($addPlusFields)
			{
				foreach ($GLOBALS['TL_DCA']['tl_subscribe_plus']['fields'] as $name => $form)
				{
					$subscriber->{$name} = $this->Input->post($name) ? $this->Input->post($name) : '';
				}
			}
			// Add new subscriptions
			$subscriber->add();
			
			if(array_key_exists($cid, $channelInfo))
			{
				if($channelInfo[$cid]['cleverreach_active']	== 1){
					$subscriber->addToCR();
				}
			}
		}
		
		
		// Redirect to jumpTo page
		if (strlen($this->jumpTo) && $this->jumpTo != $objPage->id)
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
			->limit(1)
			->execute($this->jumpTo);
		
			if ($objNextPage->numRows)
			{
				$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()));
			}
		}
		
		
		// Activation e-mail
		if($subscriber->sendActivationMail($objChannel->fetchEach('id')))
		{
			$this->redirect($this->generateFrontendUrl($objPage->row()) . '#' . $this->strFormId);
		}
		
	}
	
}

?>