<?php
namespace core\exceptions;

use Exception;
use kernel\Interfaces\IException;

class ServiceException extends Exception implements IException {

    public $data;

    const FILE_TOO_LARGE = 'The file size should not exceed '.__MAX_UPLOAD_SIZE;

    const EXTENSION_NOT_ALLOWED = 'The file extension is not allowed';

    const FILE_NOT_SAVED = 'The file is not saved';

    const PROPERTY_DOSE_NOT_EXIST = 'This property does not exist!';

    const UNIT_DOSE_NOT_EXIST = 'This unit does not exist in the system';

    const SHAREABLE_UNITS_NOT_ALLOWED = 'This type of property does not allow sharable units';

    const UNIT_AT_CAPACITY = 'This unit is at capacity';

    const UNITS_NOT_ALLOWED = 'This type of property does not allow units';

    const NOT_SHAREABLE = 'This property is not shareable';

    const UNIT_NOT_ON_PROPERTY = 'This unit is not found on this property';

    const INVALID_SUBSCRIPTION = "You do not have a valid subscription plan";

    const PACKAGE_LIMIT = "Please Upgrade your package and try again!";

    const PACKAGE_EXPIRED = "Your subscription is expired kindly renew your subscription";

    const TENANT_NOT_SAVED = "Whoops! Tenant could not be saved!";

    const ACCOUNT_DETAILS_NOT_CHANGED = "Your account details were not changed";

    const UNKNOWN_WALLET_REFERENCE =  'Unknown wallet reference';

    const UNKNOWN_TRANSACTION_TYPE = "Unknown transaction type";

    const UNKNOWN_TRANSACTION_MODE = "Unknown transaction mode";

    public function __construct($error , $data = null)
    {   
        $message = (!is_null($data) && isset($data['message'])) ? $data['message'] : $error; 
        parent::__construct($message);

    }




    public function errorMessage(): array
    {
      return ['error' => $this->getMessage()];   
    }

    public function infoMessage(): array
    {
        return ['info' => $this->getMessage()];
    }

}