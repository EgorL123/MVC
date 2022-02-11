<?php

namespace App\Controller\User\Validation;

use App\Controller\User\UserRegister;

class UserRegisterCurrentDataValidator extends UserRegisterValidate
{
    /**
     * @var mixed[]|int[]|mixed
     */
    public $errors;
    /**
     * Валидация присланных данных
     */
    public function validateCurrentData(array $validators, array $data, UserRegister $userRegister)
    {

        $notEmptyFields = $validators['empty']->validateFormNotEmpty($data, ['password_repeat']);

        if (count($notEmptyFields) !== count($userRegister->fieldsErrors)) {
            return $userRegister->getPattern(
                ['errCodes' => array_diff_key($userRegister->fieldsErrors, array_flip($notEmptyFields))],
                $userRegister->patternNames['registerFail']
            );
        }

        if (!empty($errors = $validators['email']->validate($userRegister->email))) {
            $this->errors[] = $errors;
        }

        if (!empty($errors = $validators['password']->validate($userRegister->password))) {
            $this->errors[] = $errors;
        }

        if ($userRegister->password != $userRegister->passwordRepeat) {
            $this->errors[] = PASSWORDS_IN_FORM_NOT_MATCHES;
        }

        if (!empty($errors = $validators['name']->validate($userRegister->name))) {
            $this->errors[] = $errors;
        }

        if (!empty($this->errors)) {
            return $userRegister->getPattern(['errCodes' => $this->errors], $userRegister->patternNames['registerFail']);
        }



        return true;
    }
}
