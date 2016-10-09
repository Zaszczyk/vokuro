<?php
namespace Vokuro\Controllers;
use Vokuro\Examples\ExampleService;

/**
 * Display the default index page.
 */
class IndexController extends ControllerBase
{

    /**
     * Default action. Set the public layout (layouts/public.volt)
     */
    public function indexAction()
    {
        /** @var ExampleService $e */
        $e = $this->getDI()->get('ExampleService');
        $e->get();
    }
}
