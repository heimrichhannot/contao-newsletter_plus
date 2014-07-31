<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Newsletter
 * @license    LGPL
 * @filesource
 */


/**
 * Class ModuleUnsubscribe
 *
 * Front end module "newsletter unsubscribe".
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class ModuleUnsubscribePlus extends ModuleUnsubscribe
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'nl_unsubscribe_plus';

	protected $strFormId = 'tl_unsubscribe_plus';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### NEWSLETTER UNSUBSCRIBE PLUS ###';
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
			$this->Template = new FrontendTemplate($this->nl_template);
			$this->Template->setData($this->arrData);
		}

		$inputHelper = new NlpInputHelper($submitted);
		$forms = $inputHelper->addEmailField($objChannel);
		
		
		// Unsubscribe
		if ($this->Input->post('FORM_SUBMIT') == $this->strFormId && $this->Input->post('email', true))
		{
			$this->removeRecipient();
		}

		$blnHasError = false;

		// Error message
		if (strlen($_SESSION['UNSUBSCRIBE_ERROR']))
		{
			$blnHasError = true;
			$this->Template->mclass = 'error';
			$this->Template->message = $_SESSION['UNSUBSCRIBE_ERROR'];
			$_SESSION['UNSUBSCRIBE_ERROR'] = '';
		}

		// Confirmation message
		if (strlen($_SESSION['UNSUBSCRIBE_CONFIRM']))
		{
			$this->Template->mclass = 'confirm';
			$this->Template->message = $_SESSION['UNSUBSCRIBE_CONFIRM'];
			$_SESSION['UNSUBSCRIBE_CONFIRM'] = '';
		}

		$arrChannels = array();
		$objChannel = $this->Database->execute("SELECT id, title FROM tl_newsletter_channel WHERE id IN(" . implode(',', array_map('intval', $this->nl_channels)) . ") ORDER BY title");

		// Get titles
		while ($objChannel->next())
		{
			$arrChannels[$objChannel->id] = $objChannel->title;
		}

		// Default template variables
		$this->Template->forms = $forms;
		$this->Template->channels = $arrChannels;
		$this->Template->showChannels = !$this->nl_hideChannels;
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['MSC']['unsubscribe']);
		$this->Template->channelsLabel = $GLOBALS['TL_LANG']['MSC']['nl_channels'];
		$this->Template->emailLabel = $GLOBALS['TL_LANG']['MSC']['emailAddress'];
		$this->Template->action = $objPage->numRows ? $this->generateFrontendUrl($objPage->row()) : $this->getIndexFreeRequest();
		$this->Template->formId = $this->strFormId;
		$this->Template->id = $this->id;
		$this->Template->hasError = $blnHasError;
	}


	/**
	 * Add a new recipient
	 */
	protected function removeRecipient()
	{
		$arrChannels = $this->Input->post('channels');
		$arrChannels = array_intersect($arrChannels, $this->nl_channels); // see #3240

		// Check the selection
		if (!is_array($arrChannels) || count($arrChannels) < 1)
		{
			$_SESSION['UNSUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['noChannels'];
			$this->reload();
		}

		$arrSubscriptions = array();
		
		$email = $this->idnaEncodeEmail($this->Input->post('email', true));
		
		$subscriber = new Subscriber($email);

		// Get active subscriptions
		$objSubscription = $this->Database->prepare("SELECT pid FROM tl_newsletter_recipients WHERE email=? AND active=1")
										  ->execute($email);

		if ($objSubscription->numRows)
		{
			$arrSubscriptions = $objSubscription->fetchEach('pid');
		}

		$arrRemove = array_intersect($arrChannels, $arrSubscriptions);

		// Return if there are no subscriptions to remove
		if (!is_array($arrRemove) || count($arrRemove) < 1)
		{
			$_SESSION['UNSUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['unsubscribed'];
			$this->reload();
		}

		// check for cleverreach support
		$objChannel = $this->Database->execute("SELECT id FROM tl_newsletter_channel WHERE cleverreach_active = 1 AND id IN(" . implode(',', array_map('intval', $arrRemove)) . ")");
		
		// Remove subscriptions
		$subscriber->remove($arrRemove);
		
		// optional Cleverreach Deletion
		if($objChannel->numRows > 0)
		{
			$subscriber->removeFromCR($objChannel->fetchEach('id'));
		}

		// Get channels
		$objChannels = $this->Database->execute("SELECT title FROM tl_newsletter_channel WHERE id IN(" . implode(',', array_map('intval', $arrRemove)) . ")");
		$arrChannels = $objChannels->fetchEach('title');

		// HOOK: post unsubscribe callback
		if (isset($GLOBALS['TL_HOOKS']['removeRecipient']) && is_array($GLOBALS['TL_HOOKS']['removeRecipient']))
		{
			foreach ($GLOBALS['TL_HOOKS']['removeRecipient'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($varInput, $arrRemove);
			}
		}

		global $objPage;

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

		if($subscriber->sendUnSubscribeMail($arrRemove, $this->nl_subject_unsubscribe, $this->nl_unsubscribe))
		{
			$this->reload();
		}
	}
}

?>