<?php
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Parser;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;

include_once(__s_applib_folder__.'/classes/EmailValidator/AbstractLexer.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/EmailValidator.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/EmailLexer.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/EmailParser.php');

include_once(__s_applib_folder__.'/classes/EmailValidator/Parser/Parser.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/Parser/LocalPart.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/Parser/DomainPart.php');

include_once(__s_applib_folder__.'/classes/EmailValidator/Exception/InvalidEmail.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/Exception/NoDomainPart.php');

include_once(__s_applib_folder__.'/classes/EmailValidator/Validation/EmailValidation.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/Validation/DNSCheckValidation.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/Validation/MultipleValidationWithAnd.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/Validation/RFCValidation.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/Validation/MultipleErrors.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/Warning/Warning.php');

foreach (glob(__s_applib_folder__.'/classes/EmailValidator/Warning/*.php') as $filename){
	include_once $filename;
}

include_once(__s_applib_folder__.'/classes/EmailValidator/Exception/InvalidEmail.php');
include_once(__s_applib_folder__.'/classes/EmailValidator/Exception/AtextAfterCFWS.php');

foreach (glob(__s_applib_folder__.'/classes/EmailValidator/Exception/*.php') as $filename){
	include_once $filename;
}


class _email_validator{
	private $_email;
	private $_validator;
	private $_multipleValidations;

	public function __construct(){
		$this->_validator = new EmailValidator();
		$this->_multipleValidations = new MultipleValidationWithAnd([
			new RFCValidation(),
			new DNSCheckValidation()
		]);
	}

	public function _check($_e){
		return $this->_validator->isValid($_e, $this->_multipleValidations);
	}

}

?>