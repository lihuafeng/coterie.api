<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/29
 * Time: 16:31
 */

namespace iBrand\Coterie\Backend\Repositories;


use iBrand\Coterie\Backend\Models\Comment;
use Prettus\Repository\Eloquent\BaseRepository;

class CommentsRepository extends BaseRepository
{
    public function model()
    {
        return Comment::class;
    }

    public function getCommentByID($id)
    {
        return $this->scopeQuery(function ($query) {
            return $query->withTrashed();
        })->find($id);
    }

    public function getCommentsPaginate($where, $limit = 15)
    {
        return $this->scopeQuery(function ($query) use ($where) {
            if (key_exists('forbidden', $where)) {
                unset($where['forbidden']);
                $query = $query->onlyTrashed();
            }

            if (count($where) AND is_array($where)) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            $query = $query->with('CoterieContent');
            return $query->orderBy('created_at', 'desc');
        })->paginate($limit);
    }
}