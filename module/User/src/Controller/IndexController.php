<?php

declare(strict_types=1);

namespace User\Controller;

use User\Db\TableGateway;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
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
        $title = 'User Listing';
        $view = new ViewModel([
            'title' => $title,
            'users' => $this->gateway->fetchAll(),
        ]);
        return $view;
    }
    public function createAction(): ModelInterface
    {
        $title = 'Create User';
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
            // add logging
            throw $th;
        }
        return $view;
    }

    public function editAction(): ModelInterface
    {
        $title = 'Edit User';
        $view = new ViewModel();
        $form = $this->formManager->get(EditUserForm::class);
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
            $result = $this->gateway->save($form->getData());
            if ($result) {
                $this->redirect()->toUrl('/user');
            }
        } catch (\Throwable $th) {
            // add logging
            throw $th;
        }
        return $view;
    }

    public function deleteAction()
    {
        $id = $this->params('id');
        if ($id !== null) {
            if ($this->gateway->delete(['id' => $id])) {
                $this->redirect()->toUrl('/user');
            } else {
                throw new \Exception('The target user could not be deleted!!');
            }
        }
    }
}
