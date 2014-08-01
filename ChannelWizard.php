<?php

namespace HeimrichHannot\NewsletterPlus;

class ChannelWizard extends \Widget
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
					$this->addError($GLOBALS['TL_LANG']['FFL']['tl_form_field']['nlChannels']['error']['emptyDescription']);
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
      <th>'.$GLOBALS['TL_LANG']['MSC']['ow_value'].'</th>
      <th>'.$GLOBALS['TL_LANG']['MSC']['ow_label'].'</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>';

		$tabindex = 0;

		$channels = $this->getChannels();
		
		$count = count($this->varValue) < count($channels) ? count($this->varValue) : count($channels);
		
		// Add fields
		for ($i=0; $i<$count; $i++)
		{
			$return .= '
    <tr>
      <td><select name="'.$this->strId.'['.$i.'][value]" id="'.$this->strId.'_value_'.$i.'" class="tl_select_interval" tabindex="'.++$tabindex.'" onchange="this.form.submit();">';
			$return .= '<option value=""'.$selected.'>-</option>';
      foreach ($this->getChannels() as $cid => $cname)
      {
      	$selected = ($this->varValue[$i]['value'] == $cid) ? ' selected="selected"' : '';
      	
      	$return .= '<option value="'.$cid.'"'.$selected.'>'.$cname.'</option>';
      }
      $return .= '</select></td>';
      $return .= '<td><input type="text" name="'.$this->strId.'['.$i.'][label]" id="'.$this->strId.'_label_'.$i.'" class="tl_text" tabindex="'.++$tabindex.'" value="'.specialchars($this->varValue[$i]['label']).'"></td>
      <td><input type="checkbox" name="'.$this->strId.'['.$i.'][selected]" id="'.$this->strId.'_selected_'.$i.'" class="fw_checkbox" tabindex="'.++$tabindex.'" value="1"'.($this->varValue[$i]['selected'] ? ' checked="checked"' : '').'> <label for="'.$this->strId.'_selected_'.$i.'">'.$GLOBALS['TL_LANG']['MSC']['cw_selected'].'</label></td>';
    '</tr>';
		}

		return $return.'
  </tbody>
  </table>';
	}
	
	public function getChannels()
	{
		$this->import('Database');
		$options = array();
		$objChannel = $this->Database->execute('SELECT id, title FROM tl_newsletter_channel');
		while($objChannel->next())
		{
			$options[$objChannel->id] = $objChannel->title;
		}
		return $options;
	}
}
