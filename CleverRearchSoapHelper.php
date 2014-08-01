<?php

namespace HeimrichHannot\NewsletterPlus;

class CleverRearchSoapHelper extends \System
{
	
	private static $wsdl_url = "http://api.cleverreach.com/soap/interface_v4.1.php?wsdl";
	
	protected $method;
	
	protected $parameter = array();
	
	protected $result;
	
	protected $channelId;
	
	protected $apiKey;
	
	protected $listId;
	
	public function __construct($channelId){
		parent::__construct();
		$this->channelId = $channelId;
		$this->import('Database');
	}
	
	public function getLists()
	{
		$this->method = 'getLists';
		return $this->getResult();
	} 
	
	public function add(Subscriber $subscriber)
	{
		$this->method = 'add';
		$this->parameter[] = $subscriber;
		return $this->getResult();
	} 

	public function update(Subscriber $subscriber)
	{
		$this->method = 'update';
		$this->parameter[] = $subscriber;
		return $this->getResult();
	} 
	
	public function delete(Subscriber $subscriber)
	{
		$this->method = 'delete';
		$this->parameter[] = $subscriber->email;
		return $this->getResult();
	}
	
	public function setActive(Subscriber $subscriber)
	{
		$this->method = 'setActive';
		$this->parameter[] = $subscriber->email;
		return $this->getResult();
	}
	
	public function setInActive(Subscriber $subscriber)
	{
		$this->method = 'setInactive';
		$this->parameter[] = $subscriber->email;
		return $this->getResult();
	}
	
	private function getResult()
	{
		if($this->isCleverReachActive() && $this->callSoap())
		{
			return $this->result;
		}
	}
	
	private function callSoap()
	{
		$soap = new SoapClient(self::$wsdl_url);
		try {
			$this->result = $soap->__soapCall($this->method, $this->parameter);
			return true;
		}
		catch (SoapFault $fault)
		{
			$this->log('Could not load data from cleverreach webservice (Method: '.$this->method.', Options:'.$this->parameter.').', 'CleverRearchSoapHelper callSoap()', TL_ERROR);
		}
		return false;
	}
	
	public function isCleverReachActive(){
		$objChannel = $this->Database
									->prepare("SELECT * FROM tl_newsletter_channel WHERE id=? AND cleverreach_active=1 AND cleverreach_wsdl_url != '' AND cleverreach_api_key != '' AND cleverreach_listen_id != ''")
									->execute($this->channelId);
		if($objChannel->numRows > 0)
		{
			array_unshift($this->parameter, $objChannel->cleverreach_api_key, $objChannel->cleverreach_listen_id);
			return true;
		}
		return false;
	}
	
}