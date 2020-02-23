<?php
declare(strict_types=1);

namespace App\Order\Controller;

use App\Order\Repository\OrderRepository;
use App\Order\Repository\PaymentGateway;
use App\Order\Value\Money;
use App\Order\Value\OrderNumber;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Workflow\Exception\TransitionException;
use Symfony\Component\Workflow\Registry;
use Twig\Environment as Twig;

final class PayOrder
{
    private OrderRepository $orderRepository;
    private PaymentGateway $paymentGateway;
    private Registry $workflowRegistry;
    private Twig $templating;

    public function __construct(
        OrderRepository $orderRepository,
        PaymentGateway $paymentGateway,
        Registry $workflowRegistry,
        Twig $templating
    )
    {
        $this->orderRepository = $orderRepository;
        $this->paymentGateway = $paymentGateway;
        $this->workflowRegistry = $workflowRegistry;
        $this->templating = $templating;
    }

    public function handle(Request $request): Response
    {
        $orderNumber = OrderNumber::fromString($request->attributes->get('order_number'));
        $order = $this->orderRepository->getByNumber($orderNumber);

        $workflow = $this->workflowRegistry->get($order);
        if (!$workflow->can($order, 'pay')) {
            return new Response('Cannot pay for this order');
        }

        $money = Money::fromAmountAndCurrency(
            $order->getTotal(),
            'CAD'
        );
        $order->authorizationNumber = $this->paymentGateway->charge($money);
        $this->orderRepository->update($order);

        try {
            $workflow->apply($order, 'pay');
        } catch (TransitionException $exception) {
            return new Response('Cannot mark order as paid');
        }

        return new RedirectResponse('/en/orders/' . $orderNumber);
    }
}
