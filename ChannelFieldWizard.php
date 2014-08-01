<?php

namespace HeimrichHannot\NewsletterPlus;

class ChannelFieldWizard extends \Widget
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
	protected $strTemplate = 'be_widget';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'mandatory':
				$this->arrConfiguration['mandatory'] = $varValue ? true : false;
				break;

			case 'maxlength':
				$this->arrAttributes[$strKey] = ($varValue > 0) ? $varValue : '';
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Validate input and set value
	 */
	public function validate()
	{
		$mandatory = $this->mandatory;
		$options = deserialize($this->getPost($this->strName));
		
		// Check labels only (values can be empty)
		if (is_array($options))
		{
			foreach ($options as $key=>$option)
			{
				// Unset empty rows
				if ($option['label'] == '')
				{
					unset($options[$key]);
					continue;
				}

				$options[$key]['label'] = trim($option['label']);
				$options[$key]['value'] = trim($option['value']);

				if ($options[$key]['label'] != '')
				{
					$this->mandatory = false;
				}
			}
		}

		$options = array_values($options);
		$varInput = $this->validator($options);

		if (!$this->hasErrors())
		{
			$this->varValue = $varInput;
		}

		// Reset the property
		if ($mandatory)
		{
			$this->mandatory = true;
		}
	}


	/**
	 * Generate the widget and return it as string
	 * @return string
	 */
	public function generate()
	{

		// Make sure there is at least an empty array
		if (!is_array($this->varValue) || !$this->varValue[0])
		{
			$this->varValue = array(array(''));
		}
		

		// Begin table
		$return .= '<table class="tl_optionwizard" id="ctrl_'.$this->strId.'">
  <thead>
    <tr>
      <th>'.$GLOBALS['TL_LANG']['MSC']['nl_field'].'</th>
      <th>'.$GLOBALS['TL_LANG']['MSC']['form_field'].'</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>';

		$tabindex = 0;

		$fields = $this->getChannelFields();
		
		$formGenFields = $fields['formGenFields'];
		
		$nlFields = $fields['nlFields'];
		
		
		// Add fields
		for ($i=0; $i<count($nlFields); $i++)
		{
			$return .= '<tr>';
    
      $return .= '<td><input type="text" name="'.$this->strId.'['.$i.'][label]" id="'.$this->strId.'_label_'.$i.'" class="tl_text" tabindex="'.++$tabindex.'" value="'.$nlFields[$i].'" readonly></td>';
      
     	$return .= '<td><select name="'.$this->strId.'['.$i.'][value]" id="'.$this->strId.'_value_'.$i.'" class="tl_select_interval" tabindex="'.++$tabindex.'">';
     	
      foreach ($formGenFields as $fid => $fname)
      {
      	$selected = ($this->varValue[$i]['value'] == $fname) ? ' selected="selected"' : '';
      	
      	$return .= '<option value="'.$fname.'"'.$selected.'>'.$fname.'</option>';
      }
      $return .= '</select></td>';
      $return .= '</tr>';
		}

		return $return.'</tbody></table>';
	}
	
	public function getChannelFields()
	{
		global $objPage;
		
		$this->import('Database');
		
		$this->loadDataContainer('tl_subscribe_plus');
		$this->loadLanguageFile('tl_subscribe_plus');
		
		$options = array();
		
		$objForm = $this->Database->prepare('SELECT pid FROM tl_form_field WHERE id = ?')->execute($this->Input->get('id'));
		
		if($objForm->numRows)
		{
			$channels = array();
			
			$objChannel = $this->Database->prepare('SELECT nlChannels FROM tl_form_field WHERE pid = ? AND type=?')->limit(1)->execute($objForm->pid, 'nlSubscribe');
			
			if($objChannel->numRows)
			{
				$channels = deserialize($objChannel->nlChannels);
			}
			
			if(!empty($channels[0]['value']))
			{
				// available newsletter subscription fields (last name, first name ....)
				$objNlFields = $this->Database->prepare('SELECT subscribeplus_inputs FROM tl_newsletter_channel WHERE id = ?')->limit(1)->execute($channels[0]['value']);
				
				$objForm = $this->Database->prepare('SELECT pid FROM tl_form_field WHERE id = ?')->execute($this->Input->get('id'));
				
				// available fields from current form generator form
				$objFormGenField = $this->Database->prepare('SELECT id, name FROM tl_form_field WHERE pid = ? AND id != ? AND type IN (\'text\', \'radio\')')
				->execute($objForm->pid, $this->Input->get('id'));
				
				if($objNlFields->numRows)
				{
					$activeNlFields = deserialize($objNlFields->subscribeplus_inputs);
				}
				
				foreach ($GLOBALS['TL_DCA']['tl_subscribe_plus']['fields'] as $name => $form)
				{
					if(!in_array($name, $activeNlFields) && $form['eval']['beEditable']) continue;
				
					$options['nlFields'][] = $name;
				}
				
				while($objFormGenField->next())
				{
					$options['formGenFields'][$objFormGenField->id] = $objFormGenField->name;
				}
			}
		}
		return $options;
	}
}
