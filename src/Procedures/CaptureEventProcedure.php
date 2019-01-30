<?php

namespace Novalnet\Procedures;

use Plenty\Modules\EventProcedures\Events\EventProceduresTriggered;
use Plenty\Modules\Order\Models\Order;
use Plenty\Plugin\Log\Loggable;
use Plenty\Modules\Payment\Contracts\PaymentRepositoryContract;
use Plenty\Modules\Payment\Models\Payment;
use Plenty\Modules\Payment\Models\PaymentProperty;
/**
 * Class CaptureEventProcedure
 */
class CaptureEventProcedure
{
	use Loggable;
	private $paymentRepository;
	public function __construct(PaymentRepositoryContract $paymentRepository)
	{
		$this->paymentRepository    = $paymentRepository;
	}

	
	
    /**
     * @param EventProceduresTriggered $eventTriggered
     * @param Capture $captureService
     */
    public function run(
        EventProceduresTriggered $eventTriggered
    ) {
        /* @var $order Order */
	   
        $order = $eventTriggered->getOrder();
	    $payment = pluginApp(\Plenty\Modules\Payment\Contracts\PaymentRepositoryContract::class);
	    $this->getLogger(__METHOD__)->error('payment1', $payment);
	$payments = $payment->getPaymentsByOrderId($order->id);	
	    $this->getLogger(__METHOD__)->error('payment123', $payments);
        $this->getLogger(__METHOD__)->error('EventProcedure.triggerFunction', ['order' => $order]);
        //$captureService->doCapture($order);
    }
}
