<?php
/**
 * @usage In most cases this class should be built by the FormElementManager instead
 * of an instance being retrieved via get
 * Developers should pass $options['mode'] key so as to hint to the
 * form which context to build the fieldsets in
 */
declare(strict_types=1);

namespace User\Form;

use Laminas\Form\Exception\InvalidArgumentException;
use Limatus\Form\Form;
use User\Form\Fieldset\AcctDataFieldset;
use User\Form\Fieldset\PasswordFieldset;
use Laminas\Form\Element\Submit;

class UserForm extends Form

{
    /**
     * @param array $options
     * @return void
     * @throws InvalidArgumentException
     */
    public function __construct(
        $name = null,
        $options = []
    ) {
        parent::__construct('user-form');
        parent::setOptions($options);
    }
    public function init(): void
    {
        // get the options, notice that we set a default in the __construct
        $options = $this->getOptions();
        if (! isset($options['userId'])) {
            $options['userId'] = null;
        }
        $factory = $this->getFormFactory();
        $manager = $factory->getFormElementManager();
        $acctData = $manager->build(
            AcctDataFieldset::class,
            ['userId' => $options['userId'] ?? $options['userId']]
        );
        $this->add($acctData, ['priority' => 1]);
        $this->add([
            'type' => PasswordFieldset::class,
            ['priority' => 2],
        ]);
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