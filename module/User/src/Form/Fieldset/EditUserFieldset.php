<?php

declare(strict_types=1);

namespace User\Form\Fieldset;

use Laminas\Filter;
use Laminas\Form\Element\Hidden;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;
use Laminas\Hydrator\ReflectionHydrator;
use Limatus\Form\Fieldset;
use Limatus\Form\Element;
use User\Db\UserModel;
use Webinertia\Validator\Password as PassValidator;
use Webinertia\Filter\PasswordHash;

class EditUserFieldset extends Fieldset implements InputFilterProviderInterface
{
    protected $attributes = ['class' => 'row g-3 grid-login'];
    public function __construct($name = 'user-data', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        $this->setObject(new UserModel());
        $this->setHydrator(new ReflectionHydrator());
        $this->setUseAsBaseFieldset(true);
        $this->add([
            'name' => 'id',
            'type' => Hidden::class,
        ]);
        $this->add([
            'name'       => 'text_hint',
            'type'       => Element\Text::class,
            'attributes' => [
                'class'       => 'form-control-plaintext',
                'placeholder' => 'Please enter your desired account details to create an account.',
                'readonly'    => true,
                'disabled'    => true,
            ],
            'options'    => [
                'label'                => '',
                'bootstrap_attributes' => [
                    'class' => 'col-md-12',
                ],
            ],
        ]);
        $this->add([
            'name'       => 'userName',
            'type'       => Element\Text::class,
            'attributes' => [
                'class'            => 'form-control userName',
                'placeholder'      => 'Username',
                'aria-describedby' => 'userNameHelp',
            ],
            'options'    => [
                'label'                => 'Username',
                'help'                 => 'Must be a valid email no more than 320 characters in length.',
                'bootstrap_attributes' => [
                    'class' => 'col-md-6'
                ],
                'help_attributes'      => [
                    'class' => 'form-text text-muted',
                ],
            ],
        ]);
        $this->add([
            'name'       => 'email',
            'type'       => Element\Text::class,
            'attributes' => [
                'class'            => 'form-control email',
                'placeholder'      => 'Email',
                'aria-describedby' => 'emailHelp',
            ],
            'options'    => [
                'label'                => 'Email',
                'help'                 => 'Must be a valid email no more than 320 characters in length.',
                'bootstrap_attributes' => [
                    'class' => 'col-md-6'
                ],
                'help_attributes'      => [
                    'class' => 'form-text text-muted',
                ],
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'id' => [
                'required' => false,
                'allow_empty' => true,
                'filters' => [
                    ['name' => Filter\ToInt::class],
                    ['name' => Filter\ToNull::class],
                ],
            ],
            'userName'       => [
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ],
                    ],
                ],
            ],
            'email'       => [
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 320, // true, we may never see an email this length, but they are still valid
                        ],
                    ],
                    // @see EmailAddress for $options
                    ['name' => Validator\EmailAddress::class],
                ],
            ],
        ];
    }
}
