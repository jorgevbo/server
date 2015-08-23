<?php
/**
 * @package Core
 * @subpackage model.enum
 */ 
interface MediaServerStatus extends BaseEnum
{
	const ACTIVE = 1;
	const DISABLED = 2;
	const DELETED = 3;
}
