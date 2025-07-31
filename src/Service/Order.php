<?php

namespace App\Service;

use App\Entity\Order as EntityOrder;
use App\Enum\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\VarDumper\VarDumper;

class Order
{

    const COMPLETE = 'is-complete';
    const ACTIVE = 'is-active';

    public function __construct(private EntityManagerInterface $em) {}

    /**
     * This funciton return order status with order progress steps
     */
    public function getOrderStatus(int $orderId)
    {
        if ($orderId ===  '') {
            $orderTrack = ['message' =>  'Invalid order Id'];
        }

        $order = $this->em->getRepository(EntityOrder::class)->findOneBy(['id' => substr($orderId, 3)]);
        if ($order ===  null) {
            $orderTrack = ['message' =>  'Invalid order Id'];
        } else {

            $expectedDeliveryDate = $order->getDate()->modify('4 day')->format('d-m-Y');
            $status = $order->getOrderStatus()->value;
            if ($status == OrderStatus::DELIVERED->value) {
                $expectedDeliveryDate = OrderStatus::DELIVERED->value;
            }
            $step = $this->orderStatusProgressSteps($status);
            $orderTrack = [
                'orderId' =>  $order->getId(),
                'status' => $status,
                'expectedDeliveryDate' => $expectedDeliveryDate,
                'step' => $step
            ];
        }
        return $orderTrack;
    }



    /**
     * This function return order progress steps
     */
    private function orderStatusProgressSteps($status): array
    {
        return  match ($status) {
            OrderStatus::ORDER_PLACED->value => [
                'step_1' => SELF::COMPLETE,
                'step_2' => SELF::ACTIVE,
                'step_3' => '',
                'step_4' => '',
                'step_5' => ''
            ],
            OrderStatus::PROCESSING->value =>
            [
                'step_1' => SELF::COMPLETE,
                'step_2' => SELF::COMPLETE,
                'step_3' => SELF::ACTIVE,
                'step_4' => '',
                'step_5' => ''
            ],
            OrderStatus::SHIPPED->value =>
            [
                'step_1' => SELF::COMPLETE,
                'step_2' => SELF::COMPLETE,
                'step_3' => SELF::COMPLETE,
                'step_4' => SELF::ACTIVE,
                'step_5' => ''
            ],
            OrderStatus::OUT_FOR_DELIVERY->value =>
            [
                'step_1' => SELF::COMPLETE,
                'step_2' => SELF::COMPLETE,
                'step_3' => SELF::COMPLETE,
                'step_4' => SELF::COMPLETE,
                'step_5' => SELF::ACTIVE
            ],
            OrderStatus::DELIVERED->value =>
            [
                'step_1' => SELF::COMPLETE,
                'step_2' => SELF::COMPLETE,
                'step_3' => SELF::COMPLETE,
                'step_4' => SELF::COMPLETE,
                'step_5' => SELF::COMPLETE
            ],
        };
    }
}
