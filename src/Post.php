<?php

namespace Core;

use PHP_CodeSniffer\Tests\Core\File\testInterfaceThatExtendsFQCNInterface;
use Ssch\TYPO3Rector\FileProcessor\Resources\Icons\IconsFileProcessor;

class Post implements IPost
{
    private array $variables = [];


    public function __construct(
        IField $email,
        IField $name,
        IField $password,
        IField $repeatPassword,
        IField $text,
        IField $messageId,
        IField $userId
    ) {
        $this->variables['email'] = $email;
        $this->variables['name'] = $name;
        $this->variables['password'] = $password;
        $this->variables['passwordRepeat'] = $repeatPassword;
        $this->variables['blogMessageText'] = $text;
        $this->variables['messageId'] = $messageId;
        $this->variables['userId'] = $userId;
    }


    /**
     * Нормализация данных подразумевает приведение данных к приемлемому для дальнейшей обработки виду
     * Происходит замена спецсимволов а также удаление лишних пробелов(пробелы не удаляются в поле с текстом сообщения)
     */
    public function normalizeData(): void
    {
        foreach ($this->variables as $key => $field) {
            $this->variables[$key]->set(Normalizer::normalizeSpecialChars($field));

            if ($key != 'blogMessageText') {
                $this->variables[$key]->set(Normalizer::normalizeSpaces($field));
            }
        }
    }

    public function getEmail(): ?IField
    {
        return $this->variables['email'];
    }

    public function getName(): ?IField
    {
        return $this->variables['name'];
    }

    public function getPassword(): ?IField
    {
        return $this->variables['password'];
    }

    public function getRepeatPassword(): ?IField
    {
        return $this->variables['passwordRepeat'];
    }

    public function getBlogMessageText(): ?IField
    {
        return $this->variables['blogMessageText'];
    }


    public function setEmail(IField $field): void
    {
        $this->variables['email'] = $field;
    }

    public function setName(IField $field): void
    {
        $this->variables['name'] = $field;
    }

    public function setPassword(IField $field): void
    {
        $this->variables['password'] = $field;
    }

    public function setRepeatPassword(IField $field): void
    {
        $this->variables['passwordRepeat'] = $field;
    }

    public function setBlogMessageText(IField $field): void
    {
        $this->variables['blogMessageText'] = $field;
    }

    public function setMessageId(IField $field): void
    {
        $this->variables['messageId'] = $field;
    }

    public function getMessageId(): ?IField
    {
        return $this->variables['messageId'];
    }

    /**
     * @return mixed
     */
    public function getUserId(): IField
    {
        return $this->variables['userId'];
    }

    public function setUserId(string $userId): void
    {
        $this->variables['userId'] = $userId;
    }
}
