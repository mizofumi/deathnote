<?php

/**
 * HTTP Status Code
 */
class HTTP_STATUS_CODE {
	
	/* HTTP Status Code [Success!] */
	public function Code_200()
	{
		header("HTTP/1.0 200 OK", FALSE);
	}
	
	/* HTTP Status Code [Bad Request] */
	public function Code_400()
	{
		header("HTTP/1.0 400 Bad Request", FALSE);
	}
}
