<?php

namespace LitApi\Exception;

class NotFoundException extends \Exception
{
	public function __construct($customMessage)
    {
    	if (!is_null($customMessage))
            parent::__construct("404 - " . $customMessage, 0);
        else
        	parent::__construct("404 - Not found", 0);
    }
}

?>