<?php

declare(strict_types=1);

namespace User\Controller;

use User\Db\TableGateway;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Webinertia\Utils\Debug;
use Laminas\Form\FormElementManager;
use Laminas\View\Model\ModelInterface;
use User\Form\EditUserForm;
use User\Form\Grid;

class IndexController extends AbstractActionController
{
    public function __construct(
        private FormElementManager $formManager,
        private TableGateway $gateway
    ) {
    }

    public function indexAction(): ModelInterface
    {
        $title = $this->params('action', 'Test Title');
        Debug::dump($title, 'Dumping $title');
        $view = new ViewModel(['title' => $title]);
        return $view;
    }
    public function createAction(): ModelInterface
    {
        $title = 'New User';
        $view = new ViewModel();
        $form = $this->formManager->get(Grid::class);
        if (! $this->request->isPost()) {

            return $view->setVariables(['form' => $form, 'title' => $title]);
        }
        $form->setData($this->request->getPost());
        if (!$form->isValid()) {
            return $view->setVariables(['form' => $form, 'title' => $title]);
        }
        try {
            $result = $this->gateway->save($form->getData());
            if ($result) {
                $this->redirect()->toUrl('/user');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $view;
    }

    public function editAction(): ModelInterface
    {
        $view = new ViewModel();
        $title = 'New User';
        $view = new ViewModel();
        $form = $this->formManager->get(EditUserForm::class);
        $fieldSet = $form->get('user-data');
        $fieldSet->remove('password')->remove('conf_password');
        if (! $this->request->isPost()) {
            $userData['user-data'] = (new ReflectionHydrator())->extract(
                $this->gateway->fetchRow('id', $this->params('id'), ['id', 'userName', 'email', 'password'])
            );
            $form->setData($userData);
            return $view->setVariables(['form' => $form, 'title' => $title]);
        }
        $form->setData($this->request->getPost());
        if (!$form->isValid()) {
            return $view->setVariables(['form' => $form, 'title' => $title]);
        }
        try {
            $data = $form->getData();
            $result = $this->gateway->save($form->getData());
            if ($result) {
                $this->redirect()->toUrl('/user');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $view;
    }

}
