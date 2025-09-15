<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class PostDetails extends Layout
{
    protected function renderPage(Context $context): string
    {
        return <<<HTML
            <article class="post">
                <h1 class="post_title">{$context->title}</h1>
                
                <div class="post_meta">
                    By: <strong>{$context->parameter['author']}</strong>
                    <br />
                    Created at: {$context->parameter['created_at']} |
                    Modified at: {$context->parameter['modified_at']}
                </div>
                <div class="post_body">
                    <p>{$context->content}</p>
                </div>
                <div class="post_footer">
                    Post ID: {$context->parameter['id']}
                </div>
            </article>
            HTML;
    }
}
