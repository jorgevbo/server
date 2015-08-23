<?php

/**
 * Manage media servers
 *
 * @service mediaServer
 */
class MediaServerService extends KalturaBaseService
{
	public function initService($serviceId, $serviceName, $actionName)
	{		
		parent::initService($serviceId, $serviceName, $actionName);
		
		$partnerId = $this->getPartnerId();
		if(!$this->getPartner()->getEnabledService(PermissionName::FEATURE_EXTERNAL_MEDIA_SERVER) && $partnerId !== partner::MEDIA_SERVER_PARTNER_ID)
			throw new KalturaAPIException(KalturaErrors::SERVICE_FORBIDDEN, $this->serviceName.'->'.$this->actionName);
			
		$this->applyPartnerFilterForClass('MediaServer');
	}
	
	/**
	 * Adds a media server to the Kaltura DB.
	 *
	 * @action add
	 * @param KalturaEdgeServer $edgeServer sto
	 * @return KalturaEdgeServer
	 * @throws KalturaErrors::PROPERTY_VALIDATION_NOT_UPDATABLE
	 * @throws KalturaErrors::HOST_NAME_ALREADY_EXISTS
	 */
	function addAction(KalturaMediaServer $mediaServer)
	{
		if(isset($mediaServer->dc) && $this->getPartnerId() !== Partner::MEDIA_SERVER_PARTNER_ID)
			throw new KalturaAPIException(KalturaErrors::PROPERTY_VALIDATION_NOT_UPDATABLE, "dc");
			
		$dbMediaServer = $mediaServer->toInsertableObject();
		$dbMediaServer->setPartnerId($this->getPartnerId());
		$dbMediaServer->save();
	
		$mediaServer = new KalturaMediaServer();
		$mediaServer->fromObject($dbMediaServer, $this->getResponseProfile());
		return $mediaServer;
	}
	
	/**
	 * Get media server by hostname
	 * 
	 * @action get
	 * @param string $hostname
	 * @return KalturaMediaServer
	 * 
	 * @throws KalturaErrors::MEDIA_SERVER_HOST_NAME_NOT_FOUND
	 */
	function getAction($hostname)
	{
		$dbMediaServer = MediaServerPeer::retrieveByHostname($hostname);
		if (!$dbMediaServer)
			throw new KalturaAPIException(KalturaErrors::MEDIA_SERVER_HOST_NAME_NOT_FOUND, $hostname);
			
		$mediaServer = new KalturaMediaServer();
		$mediaServer->fromObject($dbMediaServer, $this->getResponseProfile());
		return $mediaServer;
	}
	
	/**
	 * Update media server by id
	 *
	 * @action update
	 * @param int $mediaServerId
	 * @param KalturaEdgeServer $edgeServer
	 * @return KalturaEdgeServer
	 */
	function updateAction($mediaServerId, KalturaMediaServer $mediaServer)
	{
		$dbMediaServer = MediaServerPeer::retrieveByPK($mediaServerId);
		if (!$dbMediaServer)
			throw new KalturaAPIException(KalturaErrors::MEDIA_SERVER_NOT_FOUND, $mediaServerId);
			
		$dbMediaServer = $mediaServer->toUpdatableObject($dbMediaServer);
		$dbMediaServer->save();
	
		$mediaServer = new KalturaMediaServer();
		$mediaServer->fromObject($dbMediaServer, $this->getResponseProfile());
		return $mediaServer;
	}
	
	/**
	 * delete media server by id
	 *
	 * @action delete
	 * @param int $mediaServerId
	 * @throws KalturaErrors::INVALID_OBJECT_ID
	 */
	function deleteAction($mediaServerId)
	{
		$dbMediaServer = MediaServerPeer::retrieveByPK($mediaServerId);
		if(!$dbMediaServer)
			throw new KalturaAPIException(KalturaErrors::MEDIA_SERVER_NOT_FOUND, $mediaServerId);
	
		$dbMediaServer->setStatus(MediaServerStatus::DELETED);
		$dbMediaServer->save();
	}
	
	/**
	 * list avaliable media servers
	 * 
	 * @action list
	 * @param KalturaEdgeServerFilter $filter
	 * @param KalturaFilterPager $pager
	 * @return KalturaEdgeServerListResponse
	 */
	public function listAction(KalturaEdgeServerFilter $filter = null, KalturaFilterPager $pager = null)
	{
		$c = new Criteria();
	
		if (!$filter)
			$filter = new KalturaEdgeServerFilter();
	
		$edgeSeverFilter = new EdgeServerFilter();
		$filter->toObject($edgeSeverFilter);
		$edgeSeverFilter->attachToCriteria($c);
		$list = EdgeServerPeer::doSelect($c);
			
		if (!$pager)
			$pager = new KalturaFilterPager();
			
		$pager->attachToCriteria($c);
	
		$response = new KalturaEdgeServerListResponse();
		$response->totalCount = EdgeServerPeer::doCount($c);
		$response->objects = KalturaEdgeServerArray::fromDbArray($list, $this->getResponseProfile());
		return $response;
	}
	
	/**
	 * report media server heartbeat
	 * 
	 * @action reportStatus
	 * @param string $hostname
	 * @param KalturaMediaServerStatus $mediaServerStatus
	 * @return KalturaMediaServer
	 */
	function reportStatusAction($hostname, KalturaMediaServerStatus $mediaServerStatus)
	{
		$dbMediaServer = MediaServerPeer::retrieveByHostname($hostname);
		if (!$dbMediaServer)
		{
			$dbMediaServer = new MediaServer();
			$dbMediaServer->setHostname($hostname);
			$dbMediaServer->setDc(kDataCenterMgr::getCurrentDcId());
		}
		
		$mediaServerStatus->toUpdatableObject($dbMediaServer);
		$dbMediaServer->setHeartbeatTime(time());
		$dbMediaServer->save();
		
		$mediaServer = new KalturaMediaServer();
		$mediaServer->fromObject($dbMediaServer, $this->getResponseProfile());
		return $mediaServer;
	}
}