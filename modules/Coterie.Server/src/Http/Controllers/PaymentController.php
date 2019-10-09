<?php

/*
 * This file is part of ibrand/coterie-server.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Server\Http\Controllers;

use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\OrderRepository;
use iBrand\Component\Pay\Facades\Charge;
use iBrand\Component\Pay\Facades\PayNotify;

    class PaymentController extends Controller

    {

        protected $memberRepository;

        protected $coterieRepository;

        protected $orderRepository;

        public function __construct(

            MemberRepository $memberRepository,

            CoterieRepository $coterieRepository,

            OrderRepository $orderRepository


        )
        {
            $this->memberRepository = $memberRepository;

            $this->coterieRepository = $coterieRepository;

            $this->orderRepository = $orderRepository;


        }

        /**
         * 圈子支付
         * @return \Dingo\Api\Http\Response|mixed
         * @throws \Illuminate\Auth\Access\AuthorizationException
         */
        public function coterie()
        {

            $order_no = request('order_no');

            $order = $this->isPaymentOrderUser($order_no);

            $coterie = $this->coterieRepository->getCoterieMemberByUserID($order->user_id, $order->coterie_id);

            if ($coterie AND $coterie->cost_type == 'charge' AND empty($coterie->memberWithTrashed)) {

                $data = ['channel' => 'wx_lite'
                    , 'order_no' => $order_no
                    , 'amount' => $order->price
                    , 'client_ip' => \request()->getClientIp()
                    , 'subject' => '加入付费圈子:' . $coterie->name
                    , 'body' => '加入付费圈子:' . $coterie->name
                    , 'extra' => ['openid' => \request('openid'),'invite_user_code'=>request('invite_user_code'),'uuid'=>client_id()]];

                $uuid=client_id();

                $app='default';

                if($uuid){

                    $app=$uuid;

                }

                $charge = Charge::create($data,'coterie',$app);

                return $this->success(compact('charge'));

            }

            return $this->failed('');

        }

        /**
         * @return \Dingo\Api\Http\Response|mixed
         */
        public function coterieSuccess()
        {

            $charge = Charge::find(request('charge_id'));

            if (!$charge) return $this->failed('支付失败');

            $order = PayNotify::success($charge->type, $charge);

            if ($order AND !empty($order->paid_at)) {

                return $this->success($order);

            }

            return $this->failed('支付失败');
        }


        /**
         * 验证是否是自己未付款的订单
         * @param $id
         * @throws \Illuminate\Auth\Access\AuthorizationException
         */
        protected function isPaymentOrderUser($order_no)

        {
            $user = request()->user();

            $order = $this->orderRepository->findWhere(['user_id' => $user->id, 'order_no' => $order_no])->first();

            if ($user->cant('isPaymentOrderUser', $order)) {

                throw new \Exception('无权限');
            }

            return $order;
        }


    }
