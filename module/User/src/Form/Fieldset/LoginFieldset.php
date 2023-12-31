<?php

declare(strict_types=1);

namespace User\Form\Fieldset;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Validator\Authentication;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Text;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator\StringLength;

final class LoginFieldset extends Fieldset implements InputFilterProviderInterface
{
    /** @var AuthenticationService $authenticationService */
    protected $authenticationService;
    /**
     * @param array<mixed> $options
     * @return void
     * */
    public function __construct(
        AuthenticationService $authenticationService,
        ?string $name = 'login-data',
        array $options = []
    ) {
        $this->authenticationService = $authenticationService;
        parent::__construct($name, $options);
    }

    public function init(): void
    {
        $this
        ->add([
            'name'    => 'userName',
            'type'    => Text::class,
            'options' => [
                'label' => 'User Name',
            ],
        ])
        ->add([
            'name'    => 'password',
            'type'    => Password::class,
            'options' => [
                'label' => 'Password',
            ],
        ]);
    }

    public function getInputFilterSpecification(): array
    {
        return [
            'userName' => [
                'required' => true,
                'filters'  => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100, // it cant be longer than what was allowed during creation
                        ],
                    ],
                ],
            ],
            'password' => [
                'required'   => true,
                'filters'    => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100, // cant be longer than this
                        ],
                    ],
                    [
                        'name'    => Authentication::class,
                        'options' => [
                            'identity'   => 'userName',
                            'credential' => 'password',
                            'service'    => $this->authenticationService,
                            'messages'   => [
                                Authentication::IDENTITY_NOT_FOUND => 'Have you activated your account?',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}