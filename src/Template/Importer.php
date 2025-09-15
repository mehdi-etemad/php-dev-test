<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class Importer extends Layout
{
    protected function renderPage(Context $context): string
    {
        return <<<HTML
            <p style="font-weight:bold;">Fetched posts summarized:</p>
            <p>{$context->content}</p>
            HTML;
    }
}
