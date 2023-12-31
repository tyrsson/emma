<?php

declare(strict_types=1);

namespace User\Form\Fieldset;

use Laminas\Filter;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;
use Laminas\Hydrator\ReflectionHydrator;
use Limatus\Form\Fieldset;
use Limatus\Form\Element;
use Reflection;
use User\Db\UserModel;
use Webinertia\Validator\Password as PassValidator;
use Webinertia\Filter\PasswordHash;

class Grid extends Fieldset implements InputFilterProviderInterface
{
    protected $attributes = ['class' => 'row g-3 grid-login'];
    public function __construct($name = 'demo', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        $this->setObject(new UserModel());
        $this->setHydrator(new ReflectionHydrator());
        $this->setUseAsBaseFieldset(true);
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
        $this->add([
            'name'       => 'password',
            'type'       => Element\Password::class,
            'attributes' => [
                'class'            => 'form-control password',
                'placeholder'      => 'Password',
                'aria-describedby' => 'passwordHelp',
            ],
            'options' => [
                'label'                => 'Password',
                'help'                 => '8 characters, atleast 1 uppercase, 2 digits, and 2 special characters.',
                'bootstrap_attributes' => [
                    'class' => 'col-md-6',
                ],
                'help_attributes'      => [
                    'class' => 'form-text text-muted',
                ],
            ],
        ]);
        $this->add([
            'name'       => 'conf_password',
            'type'       => Element\Password::class,
            'attributes' => [
                'class'            => 'form-control password',
                'placeholder'      => 'Password',
                'aria-describedby' => 'passwordHelp',
            ],
            'options' => [
                'label'                => 'Confirm Password',
                'help'                 => 'Please confirm your password.',
                'bootstrap_attributes' => [
                    'class' => 'col-md-6',
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
            'password'    => [
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                    ['name' => PasswordHash::class],
                ],
                'validators' => [
                    [
                        'name' => PassValidator::class,
                        'options' => [
                            'length'  => 8, // overall length of password
                            'upper'   => 1, // uppercase count
                            'lower'   => 2, // lowercase count
                            'digit'   => 2, // digit count
                            'special' => 2, // special char count
                        ],
                    ],
                ],
            ],
            'conf_password' => [
                'required' =>  true,
                'validators' => [
                    [
                        'name'    => Validator\Identical::class,
                        'options' => [
                            'token'    => 'password',
                            'messages' => [
                               Validator\Identical::NOT_SAME => 'Passwords are not the same',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
