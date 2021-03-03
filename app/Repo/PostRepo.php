<?php


namespace App\Repo;


use App\Model\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class PostRepo
{
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getById(int $id, $column=['*'])
    {
        $key = 'post_'. $id;
        if ($post = Cache::get($key)) {
            return unserialize($post);
        }
        $post = $this->post->select($column)->find($id);
        Cache::put($key, serialize($post), 3600);
        return $post;
    }

    public function getManyIds(array $ids, $column=['*'], $callback = null)
    {
        $query = $this->post->whereIn('id', $ids)->select($column);
        if ($callback) {
            $query = $callback($query);
        }
        return $query->get();
    }
    public function getPopular(int $number = 10)
    {
        $key = 'post_popular';
        $ids = Redis::ZREVRANGE($key, 0, $number - 1);
        $postKeys = 'post_popular_'.$number;
        if ($post = Cache::get($postKeys)) {
            return unserialize($post);
        }
        $post = $this->getManyIds($ids)->toArray();
        Cache::put($postKeys, serialize($post), 3600);
        return $post;

    }
}