<?php

/*
 * This file is part of ibrand/coterie.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Repositories\Eloquent;

use iBrand\Coterie\Core\Models\Invite;
use iBrand\Coterie\Core\Repositories\InviteRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class Repository.
 */
class InviteRepositoryEloquent extends BaseRepository implements InviteRepository
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Invite::class;
    }


}
