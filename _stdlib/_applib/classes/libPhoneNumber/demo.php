<?php
use com\google\i18n\phonenumbers\PhoneNumberUtil;
use com\google\i18n\phonenumbers\PhoneNumberFormat;
use com\google\i18n\phonenumbers\NumberParseException;

require_once 'PhoneNumberUtil.php';

$numberStr = "079222833892";
$phoneUtil = PhoneNumberUtil::getInstance();
try {
	$numberProto = $phoneUtil->parseAndKeepRawInput($numberStr, "GB");
	//var_dump($swissNumberProto);
} catch (NumberParseException $e) {
	echo $e;
}
$isValid = $phoneUtil->isValidNumber($numberProto);//return true
//var_dump($isValid);
// Produces "+41446681800"
echo $phoneUtil->format($numberProto, PhoneNumberFormat::INTERNATIONAL) . PHP_EOL;
echo $phoneUtil->format($numberProto, PhoneNumberFormat::NATIONAL) . PHP_EOL;
echo $phoneUtil->format($numberProto, PhoneNumberFormat::E164) . PHP_EOL;
echo $phoneUtil->formatOutOfCountryCallingNumber($numberProto, "US") . PHP_EOL;



if (isValid){print "Valid number";}else{print "Invalid number";}