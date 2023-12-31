<?php

declare(strict_types=1);

namespace User\Form;

use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Submit;
use Limatus\Form\Element;
use Limatus\Form\Form;
use User\Form\Fieldset\EditUserFieldset as Fieldset;

use function strtolower;

final class EditUserForm extends Form
{
    public function __construct($name = 'grid', $options = ['mode' => self::DEFAULT_MODE, 'fieldset' => true])
    {
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        $options = $this->getOptions();
        if ($options['fieldset']) {
            $manager = $this->getFormFactory()->getFormElementManager();
            $this->add(
                $manager->build(
                    Fieldset::class,
                    [
                        'name'       => 'user-data',
                    ]
                )
            );
        } else {
            // we are not in fieldset mode then set this here instead of above in the prop definition
            $this->setAttributes(['class' => 'row g-3 grid-login', 'method' => 'POST']);
            $this->add([
                'name' => 'id',
                'type' => Hidden::class,
            ]);
            $this->add([
                'name' => 'email',
                'type' => Element\Text::class,
                'attributes' => [
                    'class'            => 'form-control email',
                    'placeholder'      => 'Email',
                    'aria-describedby' => 'emailHelp',
                ],
                'options' => [
                    'label'                => 'Email',
                    'help'                 => 'Must be a valid email no more than 320 characters in length.',
                    'bootstrap_attributes' => [
                        'class' => 'col-md-6'
                    ],
                    'help_attributes' => [
                        'class' => 'form-text text-muted',
                    ],
                ],
            ]);
            $this->add([
                'name' => 'password',
                'type' => Element\Text::class,
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
                'options' => [
                    //'label'                => 'Address 2',
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
                'options' => [
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
                'name'    => 'login_type',
                'type'    => Element\Checkbox::class,
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
        ////////////////
        $this->add([
            'name'       => 'save',
            'type'       => Submit::class,
            'attributes' => [
                'class' => 'btn btn-sm btn-secondary',
                'value' => 'Save',
            ],
        ]);
    }
}
