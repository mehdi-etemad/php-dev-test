<?php

namespace silverorange\DevTest\Controller;

use silverorange\DevTest\Context;
use silverorange\DevTest\Model\Post;
use silverorange\DevTest\Template;

class PostIndex extends Controller
{
    /**
     * @var array<Post>
     */
    private array $posts = [];

    public function getContext(): Context
    {
        $context = new Context();
        $context->title = 'Posts';
        $context->parameter['postsCount'] = count($this->posts);
        $context->content = '';
        foreach($this->posts as $post) {
            $context->content .= '<div class="post-card">
                    <div class="post-title">'.$post->title.'</div>
                    <div class="post-author">By: '.$post->author.' ('.date_format(date_create($post->created_at), 'Y-m-d').')</div>
                    <a class="post-link" href="/posts/'.$post->id.'">Read more ...</a>
                </div>';
        }
        return $context;
    }

    public function getTemplate(): Template\Template
    {
        return new Template\PostIndex();
    }

    protected function loadData(): void
    {
        $stmt = $this->db->prepare('SELECT posts.*, authors.full_name FROM posts LEFT JOIN authors ON authors.id=posts.author ORDER BY posts.created_at DESC');
        $stmt->execute();
        $posts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach($posts as $postData) {
            $post = new Post();
            $post->id = $postData['id'];
            $post->title = $postData['title'];
            $post->body = $postData['body'];
            $post->created_at = $postData['created_at'];
            $post->modified_at = $postData['modified_at'];
            $post->author = $postData['full_name'];
            $this->posts[] = $post;
        }
    }
}
