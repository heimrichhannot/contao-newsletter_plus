<?php

namespace HeimrichHannot\NewsletterPlus;

class NewsletterFormSubscribeMsg extends \Widget
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_widget';
	
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			default:
				parent::__set($strKey, $varValue);
			break;
		}
	}
	
	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		$strOptions = '';
		
		if(strlen($_SESSION['SUBSCRIBE_ERROR']))
		{
			$type = 'error';
			$message = $_SESSION['SUBSCRIBE_ERROR'];
			$_SESSION['SUBSCRIBE_ERROR'] = '';
		}
		
		if(strlen($_SESSION['SUBSCRIBE_CONFIRM']))
		{
			$type = 'confirm';
			$message = $_SESSION['SUBSCRIBE_CONFIRM'];
			$_SESSION['SUBSCRIBE_CONFIRM'] = '';
		}
		
		if(isset($type)){
			$strOptions = sprintf('<div class="nl-messages form-message" id="nl_message"><p class="%s">%s</p></div>', 
			$type, $message);
			
			$strOptions .= "<script>try {
				window.scrollTo(null, ($('nl_message').getPosition().y - 20));
			} catch(e) {}</script>";
		}
		
		return $strOptions;
	}
}