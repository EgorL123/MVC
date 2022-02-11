<?php

namespace Core;

interface IPost
{
    public function getEmail();

    public function getName();

    public function getPassword();

    public function getBlogMessageText();

    public function getMessageId();

    public function normalizeData();

    public function getUserId();

    public function getRepeatPassword();
}
