<?php

namespace silverorange\DevTest\Controller;

use silverorange\DevTest\Context;
use silverorange\DevTest\Template;
use silverorange\DevTest\Service\ImporterService;

class Importer extends Controller
{
    public function getContext(): Context
    {
        $context = new Context();
        $context->title = 'Importer';
        $importer  = new ImporterService($this->db);
        $context->content = $importer->import();
        return $context;
    }

    public function getTemplate(): Template\Template
    {
        return new Template\Importer();
    }
}
