<?php
namespace Import\Controller;

use Import\Traits\ServiceLocatorAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;
use Zend\Log\Logger;

class ImportsController extends AbstractActionController
{
    use ServiceLocatorAwareTrait;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
    }

    public function indexAction()
    {
        $page = $this->params()->fromQuery('page', 1);
        $perPage = 20;
        $query = $this->params()->fromQuery() + [
            'page' => $page,
            'per_page' => $perPage,
            'sort_by' => $this->params()->fromQuery('sort_by', 'id'),
            'sort_order' => $this->params()->fromQuery('sort_order', 'desc'),
        ];
        $response = $this->api()->search('import_imports', $query);

        $this->paginator($response->getTotalResults(), $page);

        $view = new ViewModel;
        $view->setVariable('imports', $response->getContent());
        return $view;
    }

    public function logsAction()
    {
        $severity = (int) $this->params()->fromQuery('severity', Logger::NOTICE);

        $page = $this->params()->fromQuery('page', 1);
        $perPage = 20;
        $query = $this->params()->fromQuery() + [
            'page' => $page,
            'per_page' => $perPage,
            'sort_by' => $this->params()->fromQuery('sort_by', 'added'),
            'sort_order' => $this->params()->fromQuery('sort_order', 'desc'),

            'severity' => $severity,
            'import' => (int) $this->params()->fromRoute('id')
        ];

        $response = $this->api()->search('import_logs', $query);
        $this->paginator($response->getTotalResults(), $page);

        $view = new ViewModel;
        $view->setVariable('severity', $severity);
        $view->setVariable('logs', $response->getContent());
        return $view;

    }
}
