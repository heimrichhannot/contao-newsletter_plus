<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

class NlpInputHelper extends Frontend
{
	const NLSUBSCRIBEFORMPRESENT = 'nlFormPresent';
	
	private static $hasErrors = false;
	
	private $submitted;
	
	private static $runonce = true;
	
	
	public function __construct($submitted)
	{
		parent::__construct();
		$this->submitted = $submitted;
		$this->loadLanguageFile('tl_subscribe_plus');
		$this->loadDataContainer('tl_subscribe_plus');
	}
	
	public function addInputFields($channel)
	{
		// load additional inputs
		$active = unserialize($channel->subscribeplus_inputs);
	
		$required = unserialize($channel->subscribeplus_required_inputs);
	
		$forms = array();
		
		if(count($active) > 0)
		{
			foreach ($GLOBALS['TL_DCA']['tl_subscribe_plus']['fields'] as $name => $form)
			{
				if(!in_array($name, $active) && $form['eval']['beEditable']) continue;
	
				if(in_array($name, $required))
				{
					$form['eval']['mandatory'] = true;
					$form['eval']['required'] = true;
				}
	
				
				$forms[$name] = $this->generateFormByInputType($form, $name, $default, $form['eval']);
				
				if($this->submitted && self::$runonce == true)
				{
					$forms[$name]->validate();
	
					if($forms[$name]->hasErrors())
					{
						self::$hasErrors = true;
					}
				}
			}
			
			if($this->submitted){
				self::$runonce = false;
			}
			
		}
		
		return $forms;
	}
	
	public function getPlusFieldsDcaByChannel($channel)
	{
		// load additional inputs
		$active = unserialize($channel->subscribeplus_inputs);
		
		$required = unserialize($channel->subscribeplus_required_inputs);
		
		$fields = array();
		
		if(count($active) > 0)
		{
			foreach($active as $key)
			{
				$fields[$key] = $GLOBALS['TL_DCA']['tl_subscribe_plus']['fields'][$key];
				
				if(in_array($key, $required))
				{
					$fields[$key]['eval']['mandatory'] = true;
					$fields[$key]['eval']['required'] = true;
				}
			}
		}
		
		return $fields;
	}
	
	public function addEmailField()
	{
		$form = $GLOBALS['TL_DCA']['tl_subscribe_plus']['fields']['email'];
		$name = 'email';
		$forms[$name] = $this->generateFormByInputType($form, $name, $default);
		
		if($this->submitted && self::$runonce == true)
		{
			$forms[$name]->validate();
		
			if($forms[$name]->hasErrors())
			{
				self::$hasErrors = true;
			}
		}
		
		if($this->submitted){
			self::$runonce = false;
		}
		
		return $forms;
	}
	
	public function hasErrors()
	{
		return self::$hasErrors;	
	}
	
	public function generateFormByInputType($form, $name, $default)
	{
		$strClass = $GLOBALS['TL_FFL'][$form['inputType']];
		
		if ($this->classFileExists($strClass))
		{
			switch($form['inputType'])
			{
				case 'checkbox' :
					$field = new $strClass($this->prepareForWidget($form, $name, $this->setDefault($default,$form['default'])));
					$field->addAttributes(array('strClass' => $form['eval']['tl_class']));
					$render = $field;
					break;
				default:
					$field = new $strClass($this->prepareForWidget($form, $this->setDefault($form['name'],$name), $this->setDefault($default,$form['default'])));
					$field->addAttributes(array('strClass' => $form['eval']['tl_class']));
					$render = $field;
			}
		}
		return $render;
	}
	
	public function getAvailableSubscribtionInputs()
	{
		$options = array();
		
		if(TL_MODE == 'BE'){
			foreach ($GLOBALS['TL_DCA']['tl_subscribe_plus']['fields'] as $k=>$v)
			{
				if ($v['eval']['beEditable'])
				{
					$options[$k] = $GLOBALS['TL_DCA']['tl_subscribe_plus']['fields'][$k]['label'][0];
				}
			}
		}
		
		return $options;
	}
	
	private function setDefault($input, $default){
		return(isset($input)?$input:$default);
	}

}