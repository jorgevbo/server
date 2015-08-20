<?php
/**
 * @package api
 * @subpackage filters
 */
class KalturaMediaServerFilter extends KalturaMediaServerBaseFilter
{
	/* (non-PHPdoc)
	 * @see KalturaFilter::getCoreFilter()
	 */
	protected function getCoreFilter()
	{
		return new MediaServerFilter();
	}
}

