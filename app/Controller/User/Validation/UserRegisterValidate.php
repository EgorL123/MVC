<?php

namespace App\Controller\User\Validation;

use App\Controller\User\UserRegister;

class UserRegisterValidate
{
    /**
     * @return bool|string
     */
    public function validate(array $validators, array $data, UserRegister $userRegister)
    {
        $userRegisterCurrentDataValidator = new UserRegisterCurrentDataValidator();
        $userRegisterDataBaseValidator = new UserRegisterDataBaseValidator();
        $result1 = $userRegisterCurrentDataValidator->validateCurrentData($validators, $data, $userRegister);
        $result2 = $userRegisterDataBaseValidator->validateDataBaseData($userRegister);
        if (is_string($result1)) {
            return $result1;
        }

        if (is_string($result2)) {
            return $result2;
        }

        return true;
    }
}
