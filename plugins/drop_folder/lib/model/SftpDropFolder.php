<?php

class SftpDropFolder extends SshDropFolder
{
    
    // ------------------------------------------
	// -- File Transfer Manager -----------------
	// ------------------------------------------
    
    /**
	 * @return kFileTransferMgr
	 */
	public function getFileTransferManager()
	{
	    return kFileTransferMgr::getInstance(kFileTransferMgrType::SFTP);
	}
    
}