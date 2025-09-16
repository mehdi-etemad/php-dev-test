<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class PostIndex extends Layout
{
    protected function renderPage(Context $context): string
    {
        return <<<HTML
            <h1>ALL {$context->parameter['postsCount']} POSTS</h1>
            <div class="post-list">
                {$context->content}
            </div>
            HTML;
        return $mainContext;
    }
}
