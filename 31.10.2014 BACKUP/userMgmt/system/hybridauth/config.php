<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

return 
	array(
		"active" => 2, 
		"base_url" => "https://www.owloo.com/userMgmt/system/hybridauth/", 

		"providers" => array ( 
		
			"Yahoo" => array ( 
				"enabled" => 0,
				"keys"    => array ( "id" => "", "secret" => "" )
			),

			"Google" => array ( 
				"enabled" => 1,
				"keys"    => array ( "id" => "708948513893-cl02uj2na89a5cabjqq7measvmqeagof.apps.googleusercontent.com", "secret" => "IQnIjWPskPw2PBHOu0vg4kYP" )
			),

			"Facebook" => array ( 
				"enabled" => 1,
				"keys"    => array ( "id" => "322156671269307", "secret" => "936ef10ff051776798f7f4ff4e7145ec" )
			),

			"Twitter" => array ( 
				"enabled" => 1,
				"keys"    => array ( "key" => "Pm7jYXQwE8olF5kJthBqekabd", "secret" => "0f3CKQkXYBWAJxlRuQOn3muPoCxpJ33D8nl9LZbav21cqkxUfC" ) 
			),

			"Tumblr" => array ( 
				"enabled" => 0,
				"keys"    => array ( "key" => "", "secret" => "" ) 
			),

			"LinkedIn" => array ( 
				"enabled" => 1,
				"keys"    => array ( "key" => "758u68xwwzrjf1", "secret" => "fZlbbpgvzf2x5VMV" ) 
			)
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => false,

		"debug_file" => ""
	);
