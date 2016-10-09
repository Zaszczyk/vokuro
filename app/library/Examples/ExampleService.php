<?php
namespace Vokuro\Examples;

use Phalcon\Di\Injectable;

class ExampleService extends Injectable
{
    public function __construct(
        AService $AService,
        BService $BService,
        CService $CService,
        DService $DService,
        EService $EService,
        FService $FService,
        GService $GService,
        HService $HService,
        IService $IService,
        JService $JService,
        KService $KService
    )
    {
    }

    public function get(){
        var_dump('test');
    }
}
