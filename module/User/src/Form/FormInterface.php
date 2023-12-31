<?php

declare(strict_types=1);

namespace User\Form;

interface FormInterface
{
    public const CREATE_MODE = 'create';
    public const EDIT_MODE   = 'edit';
}