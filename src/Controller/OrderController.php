<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\Order as ServiceOrder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\VarDumper\VarDumper;

#[Route('/admin/order', name: 'order_')]
class OrderController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private ServiceOrder $order
    ) {}

    #[Route('/list', name: 'list')]
    public function list()
    {
        $orders = $this->em->getRepository(Order::class)->findAll();
        return $this->render('order/list.html.twig', [
            'orders' => $orders

        ]);
    }


    #[Route('/track', name: 'track', methods: ['GET', 'POST'])]
    public function trackOrder(Request $request)
    {
        $orderTrack = [];
        if ($request->isMethod('POST')) {
            $orderId = (int)$request->request->get('order_id');
           // var_dump((int)$orderId);exit;
            if ($orderId ==  '' || $orderId == 0) {
                $orderTrack = ['message' =>  'Invalid order Id'];
            } else {
                $orderTrack = $this->order->getOrderStatus($orderId);
            }
        }
        return $this->render('order/track.html.twig', ['orderTrack' => $orderTrack]);
    }
}
