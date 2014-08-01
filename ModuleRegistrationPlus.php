<?php

namespace HeimrichHannot\NewsletterPlus;

class ModuleRegistrationPlus extends \ModuleRegistration
{

	protected function compile()
	{
		$this->newsletters = deserialize($this->newsletters);

		parent::compile();
		
		// add custom newsletter subscription Checkbox to the Form
		if (in_array('newsletter', $this->Config->getActiveModules()))
		{
			// newsletter subscription
			if(is_array($this->newsletters) && !empty($this->newsletters))
			{
			
				$objChannels = $this->Database->execute('SELECT * FROM tl_newsletter_channel WHERE id IN ('.implode(',' , $this->newsletters).')');
			
				if($objChannels->numRows)
				{
					$strForm = '<label for="ctrl_channels_' . $this->id . '" class="invisible">' . $this->channelsLabel . '</label>';
					$strForm .= '<div id="ctrl_channels_' . $this->id . '" class="checkbox_container">';
			
					
					if($objChannels->numRows == 1)
					{
						$strForm .= '<span><input type="checkbox" name="newsletter" id="opt_newsletter_' . $this->id . '_' . $objChannels->id . '" value="' . $objChannels->id . '" class="checkbox"><label for="opt_newsletter_' . $this->id . '_' . $objChannels->id . '">' . $objChannels->checkbox_label .'</label></span>';
					}else
					{
						while($objChannels->next())
						{
							$strForm .= '<span><input type="checkbox" name="newsletter[]" id="opt_newsletter_' . $this->id . '_' . $objChannels->id . '" value="' . $objChannels->id . '" class="checkbox"><label for="opt_newsletter_' . $this->id . '_' . $objChannels->id . '">' . $objChannels->checkbox_label .'</label></span>';
						}
					}
					$strForm .=  '</div>';
				}
			}
			
			$this->Template->fields .= $strForm;
		}
	}
	
}