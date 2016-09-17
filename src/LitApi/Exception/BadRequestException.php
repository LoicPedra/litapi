<?php

namespace LitApi\Exception;

class BadRequestException extends \Exception
{
	public function __construct($customMessage)
    {
    	if (!is_null($customMessage))
            parent::__construct("400 - " . $customMessage, 0);
        else
            parent::__construct("400 - Bad request", 0);
    }
}

?>