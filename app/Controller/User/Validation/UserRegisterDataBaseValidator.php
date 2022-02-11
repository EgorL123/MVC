<?php

namespace App\Controller\User\Validation;

use App\Controller\User\UserRegister;
use App\Model\User;

class UserRegisterDataBaseValidator extends UserRegisterValidate
{
    /**
     * @var int[]|mixed
     */
    public $errors;
    /**
     * @return bool|string
     */
    public function validateDataBaseData(UserRegister $userRegister)
    {
        $user = new User();
        if ($user->getByName($userRegister->name) !== null) {
            $this->errors[] = NAME_ALREADY_EXIST_REGISTRATION;
            return $userRegister->getPattern(['errCodes' => $this->errors], $userRegister->patternNames['registerFail']);
        }

        if ($user->getByEmail($userRegister->email) !== null) {
            $this->errors[] = EMAIL_ALREADY_EXIST_REGISTRATION;
            return $userRegister->getPattern(['errCodes' => $this->errors], $userRegister->patternNames['registerFail']);
        }

        return true;
    }
}
