<?php

namespace HeimrichHannot\NewsletterPlus;

class NewsletterFormSubscribe extends \Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_checkbox';
	
	/**
	 * Options
	 * @var array
	 */
	protected $arrOptions = array();
	
	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'nlChannels':
				$this->arrOptions = deserialize($varValue);
				break;
	
			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;
	
			case 'rgxp':
				break;
	
			default:
				parent::__set($strKey, $varValue);
			break;
		}
	}
	
	/**
	 * Return a parameter
	 * @return string
	 * @throws Exception
	 */
	public function __get($strKey)
	{
		switch ($strKey)
		{
			case 'options':
				return $this->arrOptions;
				break;
	
			default:
				return parent::__get($strKey);
			break;
		}
	}	
	
	/**
	 * Check options if the field is mandatory
	 */
	public function validate()
	{
		$mandatory = $this->mandatory;
		
		$options = deserialize($_POST[$this->strName]);
		
		// Check if there is at least one value
		if ($mandatory && is_array($options))
		{
			foreach ($options as $option)
			{
				if (strlen($option))
				{
					$this->mandatory = false;
					break;
				}
			}
		}
	
		$varInput = $this->validator($options);
	
		if ($this->hasErrors())
		{
			$this->class = 'error';
		}
		else{
			$this->varValue = $varInput;
		}
	
		// Reset the property
		if ($mandatory)
		{
			$this->mandatory = true;
		}
	
		// Clear result if nothing has been submitted
		if (!isset($_POST[$this->strName]))
		{
			$this->varValue = '';
		}
	}
	
	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{
		// newsletter activation
		if($this->Input->get('token'))
		{
			$this->import('Database');
			
			$objChannelPage = $this->Database->prepare("SELECT p.id, p.alias FROM tl_newsletter_recipients r LEFT JOIN tl_newsletter_channel c ON r.pid=c.id LEFT JOIN tl_page p ON p.id = c.channel_page WHERE token=?")
			->execute($this->Input->get('token'));
		
			if($objChannelPage->numRows)
			{
				$this->redirect($this->generateFrontendUrl($objChannelPage->fetchAssoc()) . ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'token=' . $this->Input->get('token'));
			}
		}
		
		$strOptions = '';
		
		if(count($this->arrOptions) > 0) 
		{
			$strOptions .= sprintf('<fieldset id="ctrl_%s" class="checkbox_container%s">',
										$this->strId,
										(($this->strClass != '') ? ' ' . $this->strClass : ''),
										($this->required ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].'</span> ' : ''),
										$this->strLabel,
										($this->required ? '<span class="mandatory">*</span>' : ''),
										$this->xlabel);
			
			foreach($this->arrOptions as $channel)
			{
				$checked = '';
				
				if((is_array($this->varValue) && in_array($channel['value'] , $this->varValue) || $this->varValue == $channel['value']) 
					|| ($channel['selected'] == 1) && !(array_key_exists('FORM_SUBMIT', $_POST))){
					$checked = ' checked="checked"';
				}
				
				$strOptions .=  sprintf('<span><input type="checkbox" name="%s" id="opt_%s" class="checkbox" value="%s"%s%s <label for="opt_%s">%s</label></span>',
										$this->strName . ((count($this->arrOptions) > 1) ? '[]' : ''),
										$this->strId.'_'.$channel['value'],
										$channel['value'],
										$checked,
										$this->strTagEnding,
										$this->strId.'_'.$channel['value'],
										$channel['label']);
			}
			
			$strOptions .= '</fieldset>'. $this->addSubmit();
		}
		return $strOptions;
	}
	
	/* Subscribe to the Newsletter, if key nl_channels is present and selected by the user */
	public function processSubmittedData($arrSubmitted, $arrForm=false, $arrFiles=false, $arrLabels)
	{
		$this->import('Database');
		
		// check if the posted channel is available (catch form manipulations)
		$objForm = $this->Database->prepare('SELECT name, nlChannels, nlFieldMapping FROM tl_form_field WHERE pid = ? and type = ?')
		->execute($arrForm['id'], 'nlSubscribe');
		
		$channels = array();
		
		// get channel by form field name and its field mappings
		while($objForm->next())
		{
			if(isset($arrSubmitted[$objForm->name]) && $arrSubmitted[$objForm->name] != '' &&!in_array($arrSubmitted[$objForm->name], $channels))
			{
				$objChannels = deserialize($objForm->nlChannels);
				$objFields = deserialize($objForm->nlFieldMapping);
				$channels[$objChannels[0]['value']] = $objFields;
			}
		}
		
		if(count($channels) > 0)
		{		
			// subscribe to each channel - set initial user to dummy user, correctly filled in iteration
			$subscriber = new Subscriber('new@email.user');		
		
			foreach($channels as $cid => $fields)
			{
				foreach($fields as $field)
				{
					$subscriber->{$field['label']} = $arrSubmitted[$field['value']];
				}
			}
			
			$subscriber->addFromExtForm(array_keys($channels));
		}
	}
}