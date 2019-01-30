<?php

namespace Novalnet\Procedures;

use Plenty\Modules\EventProcedures\Events\EventProceduresTriggered;
use Plenty\Modules\Order\Models\Order;
use Plenty\Plugin\Log\Loggable;
use Novalnet\Helper\PaymentHelper;
use Plenty\Modules\Payment\Contracts\PaymentRepositoryContract;
use Plenty\Modules\Payment\Models\Payment;
use Plenty\Modules\Payment\Models\PaymentProperty;
/**
 * Class CaptureEventProcedure
 */
class CaptureEventProcedure
{
	use Loggable;
	
	private $paymentHelper;
    public function __construct(PaymentHelper $paymentHelper)
    {
        $this->paymentHelper            = $paymentHelper;
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
	    
	   /* $payment = pluginApp(\Plenty\Modules\Payment\Contracts\PaymentRepositoryContract::class);  
       	    $details = $payment->getPaymentsByOrderId( $order->id);
	    $this->getLogger(__METHOD__)->error('45678',$payment );
	    $this->getLogger(__METHOD__)->error('789',$details );*/
	$this->paymentHelper->payments($order->id);  
	    
        $this->getLogger(__METHOD__)->error('EventProcedure.triggerFunction', ['order' => $order]);
        $this->paymentHelper->doCapture($order->id);
    }
}
