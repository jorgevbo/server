<?php
/**
 * @package api
 * @subpackage filters.base
 * @abstract
 */
abstract class KalturaMediaServerBaseFilter extends KalturaFilter
{
	static private $map_between_objects = array
	(
		"idEqual" => "_eq_id",
		"idIn" => "_in_id",
		"statusEqual" => "_eq_status",
		"statusIn" => "_in_status",
		"hostnameLike" => "_like_hostname",
		"hostnameMultiLikeOr" => "_mlikeor_hostname",
		"hostnameMultiLikeAnd" => "_mlikeand_hostname",
		"createdAtGreaterThanOrEqual" => "_gte_created_at",
		"createdAtLessThanOrEqual" => "_lte_created_at",
		"updatedAtGreaterThanOrEqual" => "_gte_updated_at",
		"updatedAtLessThanOrEqual" => "_lte_updated_at",
		"heartbeatTimeGreaterThanOrEqual" => "_gte_heartbeat_time",
		"heartbeatTimeLessThanOrEqual" => "_lte_heartbeat_time",
	);

	static private $order_by_map = array
	(
		"+createdAt" => "+created_at",
		"-createdAt" => "-created_at",
		"+updatedAt" => "+updated_at",
		"-updatedAt" => "-updated_at",
		"+heartbeatTime" => "+heartbeat_time",
		"-heartbeatTime" => "-heartbeat_time",
	);

	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}

	public function getOrderByMap()
	{
		return array_merge(parent::getOrderByMap(), self::$order_by_map);
	}

	/**
	 * @var int
	 */
	public $idEqual;

	/**
	 * @var string
	 */
	public $idIn;

	/**
	 * @var KalturaMediaServerStatus
	 */
	public $statusEqual;

	/**
	 * @var string
	 */
	public $statusIn;

	/**
	 * @var string
	 */
	public $hostnameLike;

	/**
	 * @var string
	 */
	public $hostnameMultiLikeOr;

	/**
	 * @var string
	 */
	public $hostnameMultiLikeAnd;

	/**
	 * @var int
	 */
	public $createdAtGreaterThanOrEqual;

	/**
	 * @var int
	 */
	public $createdAtLessThanOrEqual;

	/**
	 * @var int
	 */
	public $updatedAtGreaterThanOrEqual;

	/**
	 * @var int
	 */
	public $updatedAtLessThanOrEqual;

	/**
	 * @var int
	 */
	public $heartbeatTimeGreaterThanOrEqual;

	/**
	 * @var int
	 */
	public $heartbeatTimeLessThanOrEqual;
}
