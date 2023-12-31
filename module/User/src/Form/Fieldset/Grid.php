<?php

declare(strict_types=1);

namespace User\Form\Fieldset;

use Laminas\Filter;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;
use Limatus\Form\Fieldset;
use Limatus\Form\Element;

class Grid extends Fieldset implements InputFilterProviderInterface
{
    protected $attributes = ['class' => 'row g-3 grid-login'];
    public function __construct($name = 'demo', $options = [])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
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
            'type'       => Element\Text::class,
            'attributes' => [
                'class'            => 'form-control password',
                'placeholder'      => 'Password',
                'aria-describedby' => 'passwordHelp',
            ],
            'options' => [
                'label'                => 'Password',
                'help'                 => 'Must contain atleast 1 uppercase, 1 digit, and 1 special character.',
                'bootstrap_attributes' => [
                    'class' => 'col-md-6',
                ],
                'help_attributes'      => [
                    'class' => 'form-text text-muted',
                ],
            ],
        ]);
        $this->add([
            'name'       => 'address',
            'type'       => Element\Text::class,
            'attributes' => [
                'class'       => 'form-control address',
                'placeholder' => '1234 Main St',
            ],
            'options'    => [
                'label'                => 'Address',
                'bootstrap_attributes' => [
                    'class' => 'col-md-12',
                ],
            ],
        ]);
        $this->add([
            'name'       => 'address_two',
            'type'       => Element\Text::class,
            'attributes' => [
                'class'       => 'form-control-plaintext',
                'placeholder' => 'Apartment, studio, or floor',
                'readonly'    => true,
                'disabled'    => true,
            ],
            'options'    => [
                'label'                => 'Plain Text example',
                'bootstrap_attributes' => [
                    'class' => 'col-md-12',
                ],
            ],
        ]);
        $this->add([
            'name'       => 'city',
            'type'       => Element\Text::class,
            'attributes' => [
                'class'            => 'form-control address',
                'aria-describedby' => 'cityHelp',
            ],
            'options'    => [
                'label'                => 'City',
                'help'                 => 'Can not exceed 255 characters.',
                'bootstrap_attributes' => [
                    'class' => 'col-md-6',
                ],
                'help_attributes'      => [
                    'class' => 'form-text text-muted',
                ],
            ],
        ]);
        $this->add([
            'name'       => 'state',
            'type'       => Element\Text::class,
            'attributes' => [
                'class'            => 'form-control state',
                'aria-describedby' => 'stateHelp',
            ],
            'options' => [
                'label'                => 'State',
                'help'                 => 'Only valid U.S. state abbreviations.',
                'bootstrap_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help_attributes'      => [
                    'class' => 'form-text text-muted',
                ],
            ],
        ]);
        $this->add([
            'name'       => 'zip',
            'type'       => Element\Text::class,
            'attributes' => [
                'class'            => 'form-control zip',
                'aria-describedby' => 'zipHelp',
            ],
            'options'    => [
                'label'                => 'Zip',
                'help'                 => '5 digit U.S. zip code.',
                'bootstrap_attributes' => [
                    'class' => 'col-md-2',
                ],
                'help_attributes'      => [
                    'class' => 'form-text text-muted',
                ],
            ],
        ]);
        $this->add([
            'name'       => 'login_type',
            'type'       => Element\Checkbox::class,
            'attributes' => [
                'value' => '1',
                'class' => 'form-check-input example-checkbox',
            ],
            'options'    => [
                'label'              => 'Checkbox',
                'use_hidden_element' => false,
                'checked_value'      => '1',
                'unchecked_value'    => '0',
                'bootstrap_attributes' => [
                    'class' => 'col-md-12', // used in the outer most wrapper div for checkbox
                ],
                'label_attributes'     => [
                    'class' => 'form-check-label',
                ],
                'label_options'        => [
                    'label_position' => 'APPEND',
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
                ],
                'validators' => [
                    [
                        'name'    => Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100, // cant be longer than this
                        ],
                    ],
                ],
            ],
            'address'     => [
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
            ],
            'address_two' => [
                'required' => false,
                'filters'  => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                ],
            ],
            'city'        => [
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                    ['name' => Filter\StringTrim::class],
                    ['name' => Filter\UpperCaseWords::class],
                ],
                'validators' => [
                    [
                        'name'    => Validator\StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255, // cant be longer than this
                        ],
                    ],
                ],
            ],
            'state'       => [
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
                            'max'      => 2, // cant be longer than this
                        ],
                    ],
                ],
            ],
            'zip'         => [
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
                            'max'      => 5, // cant be longer than this
                        ],
                    ],
                    [
                        'name'    => Validator\Digits::class,
                    ],
                ],
            ],
        ];
    }
}
