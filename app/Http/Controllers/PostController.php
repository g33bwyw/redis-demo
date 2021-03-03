<?php


namespace App\Http\Controllers;


use App\Model\Post;
use App\Repo\PostRepo;
use Illuminate\Support\Facades\Redis;

class PostController
{
    protected $postRepo;
    public function __construct(PostRepo $postRepo)
    {
        $this->postRepo = $postRepo;
    }
    //文章排行榜展示
    public function popular()
    {
//        $ids = Redis::ZREVRANGE('post_popular', 0, 5);
//        $posts = (new Post())->whereIn('id', $ids)->get()->toArray();
        $posts = $this->postRepo->getPopular(10);
        echo "<pre>";print_r($posts);
    }

    /**
     * 文章展示
     * @param Post $post
     */
    public function show(Post $post)
    {
        $post->increment('views');
        if ($post->save()) {
            Redis::zincrBy('post_popular', 1, $post->id);
        }
        return sprintf('文章id：%d访问数:%d', $post->id, $post->views);
    }
}