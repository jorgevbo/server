<?php


/**
 * Skeleton subclass for performing query and update operations on the 'response_profile' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package Core
 * @subpackage model
 */
class ResponseProfilePeer extends BaseResponseProfilePeer {

	public static function setDefaultCriteriaFilter()
	{
		if(self::$s_criteria_filter == null)
			self::$s_criteria_filter = new criteriaFilter();
		
		$c = new Criteria();
		$c->addAnd(ResponseProfilePeer::STATUS, ResponseProfileStatus::DELETED, Criteria::NOT_EQUAL);
		self::$s_criteria_filter->setFilter($c);
	}
	
	/**
	 * @param string $systemName
	 * @return ResponseProfile
	 */
	public static function retrieveBySystemName($systemName)
	{
		$criteria = new Criteria(ResponseProfilePeer::DATABASE_NAME);
		$criteria->add(ResponseProfilePeer::SYSTEM_NAME, $systemName);
		$criteria->addDescendingOrderByColumn(ResponseProfilePeer::PARTNER_ID);

		return ResponseProfilePeer::doSelectOne($criteria);
	}
	
} // ResponseProfilePeer
