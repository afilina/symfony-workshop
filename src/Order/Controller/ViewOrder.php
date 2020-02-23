<?php
declare(strict_types=1);

namespace App\Order\Controller;

use App\Order\Repository\OrderRepository;
use App\Order\Repository\PaymentGateway;
use App\Order\Value\Money;
use App\Order\Value\OrderNumber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\Exception\TransitionException;
use Symfony\Component\Workflow\Registry;
use Twig\Environment as Twig;

final class ViewOrder
{
    private OrderRepository $orderRepository;
    private Twig $templating;

    public function __construct(
        OrderRepository $orderRepository,
        Twig $templating
    )
    {
        $this->orderRepository = $orderRepository;
        $this->templating = $templating;
    }

    public function handle(Request $request): Response
    {
        $order = $this->orderRepository->getByNumber(
            OrderNumber::fromString($request->attributes->get('order_number'))
        );

        return new Response($this->templating->render('order/view.html.twig', ['order' => $order]));
    }
}
