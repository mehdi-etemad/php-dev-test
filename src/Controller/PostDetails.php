<?php

namespace silverorange\DevTest\Controller;

use silverorange\DevTest\Context;
use silverorange\DevTest\Template;
use silverorange\DevTest\Model;
use League\CommonMark\CommonMarkConverter;

class PostDetails extends Controller
{
    /**
     * TODO: When this property is assigned in loadData this PHPStan override
     * can be removed.
     *
     * @phpstan-ignore property.unusedType
     */
    private ?Model\Post $post = null;

    public function getContext(): Context
    {
        $context = new Context();
        $this->loadData();
        if ($this->post === null) {
            $context->title = 'Not Found';
            $context->content = "A post with id {$this->params[0]} was not found.";
        } else {
            $context->title = $this->post->title;
            $converter = new CommonMarkConverter();
            $context->content = $converter->convert($this->post->body)->getContent();
            $context->parameter['id'] = $this->post->id;
            $context->parameter['created_at'] = date_format(date_create($this->post->created_at), 'Y-m-d');
            $context->parameter['modified_at'] = date_format(date_create($this->post->modified_at), 'Y-m-d');
            $context->parameter['author'] = $this->post->author;
        }

        return $context;
    }

    public function getTemplate(): Template\Template
    {
        if ($this->post === null) {
            return new Template\NotFound();
        }

        return new Template\PostDetails();
    }

    public function getStatus(): string
    {
        if ($this->post === null) {
            return $this->getProtocol() . ' 404 Not Found';
        }

        return $this->getProtocol() . ' 200 OK';
    }

    protected function loadData(): void
    {
        $stmt = $this->db->prepare('SELECT posts.*, authors.full_name FROM posts LEFT JOIN authors ON authors.id=posts.author WHERE posts.id=?');
        $stmt->execute([$this->params[0]]);
        $post = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if($post != null) {
            $this->post = new Model\Post();
            $this->post->id = $post[0]['id'];
            $this->post->title = $post[0]['title'];
            $this->post->body = $post[0]['body'];
            $this->post->created_at = $post[0]['created_at'];
            $this->post->modified_at = $post[0]['modified_at'];
            $this->post->author = $post[0]['full_name'];
        }
    }
}
