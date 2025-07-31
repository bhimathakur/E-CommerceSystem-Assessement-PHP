<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\User;
use App\Enum\OrderStatus;
use App\Enum\Status;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setFirstName('Bhima')
            ->setLastName('Thakur')
            ->setEmail('bhimathakur@gmail.com')
            ->setPassword('$2y$13$XG4gLUyysqFvt14dEiUu0OZh7bIhMzLHoacrRwEpzWuS6jEvMXq2.')
            ->setRoles(['ROLE_ADMIN'])
            ->setZipcode(12345)
            ->setStatus(Status::ACTIVE)
            ->setMobile(9876543210)
            ->setCreatedAt(new DateTime())

        ;

        $manager->persist($user);

        $manager->flush();
        $this->orderData($manager);
    }


    private function orderData($manager)
    {
        $orderStatus = ['ORDER_PLACED','PROCESSING','SHIPPED','OUT_FOR_DELIVERY', 'DELIVERED'];

        $statuses = [
            OrderStatus::ORDER_PLACED,
            OrderStatus::PROCESSING,
            OrderStatus::SHIPPED,
            OrderStatus::OUT_FOR_DELIVERY,
            OrderStatus::DELIVERED,
        ];
        
        $i = 1;
        foreach ($statuses as $index=>$status) {
            $order = new Order();
            $order
                ->setProductId($i)
                ->setPrice(rand(111,999))
                ->setQty(rand(1, 10))
                ->setOrderStatus($status)
                ->setDate(new DateTime())
            ;
        $manager->persist($order);
        $manager->flush();
        $i++;
        }
    }

}
