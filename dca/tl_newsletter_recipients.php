<?php

\Controller::loadDataContainer('tl_subscribe_plus');

$dc = &$GLOBALS['TL_DCA']['tl_newsletter_recipients'];

/**
 * Table tl_newsletter_recipients
 */
$dc['list']['operations']['toggle']['button_callback'] = array('tl_newsletter_recipients_plus', 'toggleIcon');
$dc['config']['ondelete_callback']                     = array(array('tl_newsletter_recipients_plus', 'deletePlus'));
$dc['config']['onsubmit_callback']                     = array(array('tl_newsletter_recipients_plus', 'submitPlus'));
$dc['config']['onload_callback']                       = array(array('tl_newsletter_recipients_plus', 'addPlusFields'));

$dc['fields'] = array_merge($dc['fields'], $GLOBALS['TL_DCA']['tl_subscribe_plus']['fields']);

//$fields = array_merge($GLOBALS['TL_DCA']['tl_newsletter_recipients']['fields'], $fieldsPlus);

//$GLOBALS['TL_DCA']['tl_newsletter_recipients']['fields'] = $fields;


// $helper = new tl_newsletter_recipients_plus();
// $helper->addPlusFields();

class tl_newsletter_recipients_plus extends tl_newsletter_recipients
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function addPlusFields(DataContainer $dc)
	{
		$stmt       = 'SELECT * FROM tl_newsletter_recipients r LEFT JOIN tl_newsletter_channel c ON c.id = r.pid WHERE r.id = ?';
		$objChannel = $this->Database->prepare($stmt)->limit(1)->execute($dc->id);

		if ($objChannel->numRows) {
			$helper = new HeimrichHannot\NewsletterPlus\NlpInputHelper(false);
			$fields = $helper->getPlusFieldsDcaByChannel($objChannel);
			if (is_array($fields))
			{
				$strFields = implode(',', array_keys($fields));

				//extend the palette with enabled tl_subscribe_plus fields
				$GLOBALS['TL_DCA']['tl_newsletter_recipients']['palettes']['default'] = str_replace('active', sprintf('active;{fields_plus_legend},%s', $strFields),
					$GLOBALS['TL_DCA']['tl_newsletter_recipients']['palettes']['default']);

				//extend the fields with enabled tl_subscribe_plus fields
				$GLOBALS['TL_DCA']['tl_newsletter_recipients']['fields'] = array_merge($GLOBALS['TL_DCA']['tl_newsletter_recipients']['fields'], $fields);
			}
		}
	}

	private function isCRActive($cid)
	{
		$objChannel = $this->Database->prepare('SELECT * FROM tl_newsletter_channel WHERE id = ?')->limit(1)->execute($cid);

		return ($objChannel->numRows && $objChannel->cleverreach_active == 1);
	}

	public function submitPlus(DataContainer $dc)
	{
		$subscriber = new Subscriber($dc->activeRecord->email);
		$subscriber->setByDc($dc);

		$cid  = $dc->activeRecord->pid;
		$time = time();

		// set active
		if ($dc->activeRecord->active == 1) {
			$subscriber->activated   = $time;
			$subscriber->deactivated = '';
		} else {
			$subscriber->deactivated = $time;
		}

		if ($this->isCRActive($cid)) {
			// new record - added manually --> call add() method
			if (!$dc->activeRecord->addedOn && !$dc->activeRecord->tstamp) {
				$subscriber->registered = $time;
				$subscriber->addToCR();
			}

			// existing record - call update() method
			if ($dc->activeRecord->tstamp) {
				$subscriber->updateCR();
			}
		}
	}

	public function deletePlus(DataContainer $dc)
	{
		$subscriber = new HeimrichHannot\NewsletterPlus\Subscriber($dc->activeRecord->email);
		$cid        = $dc->activeRecord->pid;
		if ($this->isCRActive($cid)) {
			$subscriber->removeFromCR(array($cid));
		}
	}

	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid'))) {
			$this->toggleVisibilityPlus($this->Input->get('tid'), ($this->Input->get('state') == 1), $this->Input->get('id'));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_newsletter_recipients::active', 'alexf')) {
			return '';
		}

		$href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['active'] ? '' : 1);

		if (!$row['active']) {
			$icon = 'invisible.gif';
		}

		return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
	}

	public function toggleVisibilityPlus($intId, $blnVisible, $cid)
	{
		parent::toggleVisibility($intId, $blnVisible);

		if ($this->isCRActive($cid)) {
			$subscriber = new HeimrichHannot\NewsletterPlus\Subscriber(null);
			$subscriber->getById($intId);
			($blnVisible == 1) ? $subscriber->activateCR() : $subscriber->deActivateCR();
		}
	}

}

?>