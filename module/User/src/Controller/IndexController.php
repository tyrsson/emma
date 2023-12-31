<?php

declare(strict_types=1);

namespace User\Controller;

use User\Db\TableGateway;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Webinertia\Utils\Debug;
use Laminas\Form\FormElementManager;
use User\Form\Grid;

class IndexController extends AbstractActionController
{
    public function __construct(
        private FormElementManager $formManager,
        private TableGateway $gateway
    ) {
    }

    public function indexAction()
    {
        $title = $this->params('action', 'Test Title');
        Debug::dump($title, 'Dumping $title');
        $view = new ViewModel(['title' => $title]);
        return $view;
    }
    public function createAction()
    {
        $title = 'New User';
        $view = new ViewModel();
        $form = $this->formManager->get(Grid::class);
        if (! $this->request->isPost()) {
            $default['acct-data']['regDate'] = (new \DateTime('now', new \DateTimeZone('America/Chicago')))->format(\DateTimeInterface::RFC3339);
            $form->setData($default);
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

}
