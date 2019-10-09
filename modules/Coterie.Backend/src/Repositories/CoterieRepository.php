<?php
namespace iBrand\Coterie\Backend\Repositories;

use iBrand\Coterie\Backend\Models\Coterie;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/26
 * Time: 14:30
 */
class CoterieRepository extends BaseRepository
{
    public function model()
    {
        return Coterie::class;
    }

    public function getCoterieByID($id)
    {
        return $this->scopeQuery(function ($query) {
            return $query->withTrashed();
        })->find($id);
    }

    public function getCoteriesPaginated($where, $limit = 15)
    {
        $list = $this->scopeQuery(function ($query) use ($where) {
            if (key_exists('forbidden', $where)) {
                unset($where['forbidden']);
                $query = $query->onlyTrashed();
            }

            if (count($where) AND is_array($where)) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } elseif ($key == 'recommend') {
                        $query = $query->whereNotNull('recommend_at');
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            return $query->orderBy('updated_at', 'desc');
        });


        if ($limit == 0) {
            return $list->all();
        } else {
            return $list->paginate($limit);
        }

    }


}