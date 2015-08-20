<?php
/**
 * @package api
 * @subpackage objects
 */
class KalturaMediaServer extends KalturaObject implements IFilterable
{
	/**
	 * Unique identifier
	 * 
	 * @var int
	 * @filter eq,in
	 * @readonly
	 */
	public $id;

	/**
	 * Server data center id
	 *
	 * @var int
	 * @readonly
	 */
	public $partnerId;
	
	/**
	 * Server data center id
	 * 
	 * @var int
	 * @readonly
	 */
	public $dc;
	
	/**
	 * @var KalturaMediaServerStatus
	 * @filter eq,in
	 */
	public $status;
	
	/**
	 * Server host name
	 * 
	 * @var string
	 * @filter like,mlikeor,mlikeand
	 * @readonly
	 */
	public $hostname;
	
	/**
	 * Server first registration date as Unix timestamp (In seconds)
	 * 
	 * @var int
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $createdAt;
	
	/**
	 * Server last update date as Unix timestamp (In seconds)
	 * 
	 * @var int
	 * @readonly
	 * @filter gte,lte,order
	 */
	public $updatedAt;
	
	/**
	 * Server last update date as Unix timestamp (In seconds)
	 *
	 * @var int
	 * @filter gte,lte,order
	 * @readonly
	 */
	public $heartbeatTime;
	
	private static $mapBetweenObjects = array
	(
		'id',
		'partnerId',
		'dc',
		'hostname',
		'createdAt',
		'updatedAt',
		'heartbeatTime',
	);
	
	/* (non-PHPdoc)
	 * @see KalturaObject::getMapBetweenObjects()
	 */
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$mapBetweenObjects);
	}
	
	/* (non-PHPdoc)
	 * @see KalturaObject::toObject()
	 */
	public function toObject ( $object_to_fill = null , $props_to_skip = array() )
	{
		if(is_null($object_to_fill))
			$object_to_fill = new MediaServer();
	
	
		$object_to_fill =  parent::toObject($object_to_fill, $props_to_skip);
	
		return $object_to_fill;
	}
	
	/* (non-PHPdoc)
	 * @see IFilterable::getExtraFilters()
	 */
	public function getExtraFilters()
	{
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see IFilterable::getFilterDocs()
	 */
	public function getFilterDocs()
	{
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see KalturaObject::toInsertableObject()
	 */
	public function toInsertableObject ( $object_to_fill = null , $props_to_skip = array() )
	{
		if(is_null($object_to_fill))
			$object_to_fill = new MediaServer();
			
		return parent::toInsertableObject($object_to_fill, $props_to_skip);
	}
	
	/* (non-PHPdoc)
	 * @see KalturaObject::validateForInsert()
	 */
	public function validateForInsert($propertiesToSkip = array())
	{
		$this->validateMandatoryAttributes(true);
		$this->validateDuplications();
	
		return parent::validateForInsert($propertiesToSkip);
	}
	
	/* (non-PHPdoc)
	 * @see KalturaObject::validateForUpdate()
	 */
	public function validateForUpdate($sourceObject, $propertiesToSkip = array())
	{
		$this->validateMandatoryAttributes();
		$this->validateDuplications($sourceObject->getId());
	
		return parent::validateForUpdate($sourceObject, $propertiesToSkip);
	}
	
	public function validateMandatoryAttributes($isInsert = false)
	{
		$this->validatePropertyMinLength("hostName", 1, !$isInsert);
	}
	
	public function validateDuplications($mediaServerId = null)
	{
		if($this->hostName)
			$this->validateHostNameDuplication($mediaServerId);
	}
	
	public function validateHostNameDuplication($mediaServerId = null)
	{
		$c = KalturaCriteria::create(EdgeServerPeer::OM_CLASS);
	
		if($mediaServerId)
			$c->add(MediaServerPeer::ID, $mediaServerId, Criteria::NOT_EQUAL);
	
		$c->add(MediaServerPeer::HOSTNAME, $this->hostname);
		$c->add(MediaServerPeer::STATUS, array(MediaServerStatus::ACTIVE, MediaServerStatus::DISABLED), Criteria::IN);
	
		if(MediaServerPeer::doCount($c))
			throw new KalturaAPIException(KalturaErrors::HOST_NAME_ALREADY_EXISTS, $this->hostName);
	}
}


