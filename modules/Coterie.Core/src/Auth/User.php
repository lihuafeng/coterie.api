<?php

/*
 * This file is part of ibrand/EC-Open-Core.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Auth;

use iBrand\Component\User\Models\User as BaseUser;
use Laravel\Passport\HasApiTokens;


class User extends BaseUser
{
    use HasApiTokens;

    protected $hidden = ['client_id'];

    /**
     * @param $value
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|mixed|string
     */
    public function getAvatarAttribute($value)
    {
        if (!empty($value)) {
            if (str_contains($value, 'http://wx.qlogo.cn')) {
                return str_replace('http://wx.qlogo.cn', 'https://wx.qlogo.cn', $value);
            }

            return url($value);
        }

        return $value;
    }
}
