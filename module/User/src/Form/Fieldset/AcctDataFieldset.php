<?php

declare(strict_types=1);

namespace User\Form\Fieldset;

use User\Form\FormInterface;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Text;
use Laminas\Form\Exception\InvalidArgumentException;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator\Db\NoRecordExists;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\StringLength;
use User\Db\TableGateway;

final class AcctDataFieldset extends Fieldset implements InputFilterProviderInterface
{
    /** @var UserGateway $userGateway */
    protected $userGateway;
    /**
     * @param array $options
     * @return void
     * @throws InvalidArgumentException
     */
    public function __construct(TableGateway $userGateway, $options = [])
    {
        parent::__construct('acct-data');
        $this->setAttribute('id', 'acct-data');
        $this->userGateway = $userGateway;
        $options['mode'] = FormInterface::CREATE_MODE;
        if ($options !== []) {
            $this->setOptions($options);
        }
    }

    public function init(): void
    {
        $this->add([
            'name' => 'id',
            'type' => Hidden::class,
        ])->add([
            'name' => 'regDate',
            'type' => Hidden::class,
        ])->add([
            'name'    => 'userName',
            'type'    => Text::class,
            'options' => [
                'label' => 'User Name',
            ],
        ])->add([
            'name'    => 'email',
            'type'    => Text::class,
            'options' => [
                'label' => 'Email',
            ],
        ]);
    }

    public function getInputFilterSpecification(): array
    {
        $filters = [
            'id'       => [
                'filters' => [
                    ['name' => ToInt::class],
                ],
            ],
            'email'    => [
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
                            'max'      => 320, // true, we may never see an email this length, but they are still valid
                        ],
                    ],
                    // @see EmailAddress for $options
                    ['name' => EmailAddress::class],
                ],
            ],
            'userName' => [
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
                            'min'      => 3,
                            'max'      => 100,
                        ],
                    ],
                    [
                        'name'    => NoRecordExists::class,
                        'options' => [
                            'table'     => $this->userGateway->getTable(),
                            'field'     => 'userName',
                            'dbAdapter' => $this->userGateway->getAdapter(),
                            'messages'  => [
                                NoRecordExists::ERROR_RECORD_FOUND => 'Username is already in use!!',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        if ($this->options['mode'] === FormInterface::EDIT_MODE) {
            $filters['userName']['validators'][1]['options']['exclude'] = [
                'field' => 'id',
                'value' => $this->options['userId'],
            ];
        }
        return $filters;
    }
}