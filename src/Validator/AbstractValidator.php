<?php

namespace Core\Validator;

abstract class AbstractValidator
{
    abstract public function validate(string $subject);

    protected array $errors = [];

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Рекурсивная функция для помещения всех ошибок в единый массив
     * Нужна для обработки неопределенного числа вложенных массивов ошибок
     * для их последующего вывода
     * @return mixed[]
     */
    public function getErrorsRecurs(array $errors): array
    {
        $output = [];

        foreach ($errors as $error) {
            if (is_array($error)) {
                $output = $this->getErrorsRecurs($error);
            } else {
                $output[] = $error;
            }
        }

        return $output;
    }
}
