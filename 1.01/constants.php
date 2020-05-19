<?php 
	//Data Type
	define('BOOLEAN', 	'1');
	define('INTEGER', 	'2');
	define('FLOAT', 	'3');
	define('STRING', 	'4');
	define('BLOCK', 	'5');

	//Taks Type
	define('TASK_TYPE_USER',						'USR');
	define('TASK_TYPE_TOKEN',						'TKN');
	define('TASK_TYPE_TRANSPORT',					'TRNSP');
	define('TASK_TYPE_SMS',							'SMS');
	define('TASK_TYPE_USR',							'USR');

	//SMS Message Type
	define('SMS_FREETEXT',							'FREETEXT');
	define('SMS_INVITE',							'INVITE');
	define('SMS_REGISTRATION',						'REGISTRATION');
	define('SMS_TRACKING',							'TRACKING');

	//Web link
	// define('WEB_HOME',								'webandtrace.com');
	// define('WEB_SELTRANSPORT_DL', 					'seltransport/web/download.html'); //ez így szar, meg kell csinálni normálisan (VI)
	define('WEB_SELTRANSPORT_DL',					'https://bit.ly/3bK5mYY');

	//Token
	define('TOKEN_EXP',								1800);

	//Error Codes
	define('REQUEST_METHOD_NOT_VALID',		        100);
	define('REQUEST_CONTENTTYPE_NOT_VALID',	        101);
	define('REQUEST_NOT_VALID', 			        102);
	define('REQUEST_WRONG_TASK_TYPE', 		        103);
    define('VALIDATE_PARAMETER_REQUIRED', 			104);
	define('VALIDATE_PARAMETER_DATATYPE', 			105);

	define('SMS_INVALID_PROVIDER',					200);
	define('SMS_INVALID_MESSAGE_TYPE',				201);
	define('SMS_MISSING_KEY',						202);
	define('SMS_SEND_ERROR',						203);

	define('CONNECTION_ERROR',						300);
	define('EXECUTION_ERROR',						301);

	define('DATACONNECT_INVALID_PARAMETER',			400);

	define('TOKEN_VALIDATE_ERROR',					500);
	define('TOKEN_MISSING_KEY',						501);
	define('ACCESS_TOKEN_ERRORS',					502);
	define('USER_NOT_ACTIVE',						503);
	define('MISSING_TOKEN',							504);

	define('STATUS_SWITCH_ERROR',					600);
?>
