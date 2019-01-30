<?php

namespace Novalnet\Procedures;

use Plenty\Modules\EventProcedures\Events\EventProceduresTriggered;
use Plenty\Modules\Order\Models\Order;
use Plenty\Plugin\Log\Loggable;
use Novalnet\Helper\PaymentHelper;
use Plenty\Modules\Payment\Contracts\PaymentRepositoryContract;
use Novalnet\Services\PaymentService;

/**
 * Class CaptureEventProcedure
 */
class CaptureEventProcedure
{
	use Loggable;
	
	private $paymentHelper;
	private $paymentService;
	
    public function __construct(PaymentHelper $paymentHelper, PaymentService $paymentService)
    {
        $this->paymentHelper            = $paymentHelper;
	    $this->paymentService       = $paymentService;
	    
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
	    foreach($order as $orders)
	    {
		 $amount = $orders->amounts;  
		    $invoice = $amount[0]->invoiceTotal;
	    }
	    $this->getLogger(__METHOD__)->error('invoice',$invoice);
	    
	   $payments = pluginApp(\Plenty\Modules\Payment\Contracts\PaymentRepositoryContract::class);  
       	   $paymentDetails = $payments->getPaymentsByOrderId($order->id);
	    
	    $this->getLogger(__METHOD__)->error('details',$paymentDetails);
	    $paymentKey = $paymentDetails[0]->method->paymentKey;
	    
	   $key = $this->paymentService->getkeyByPaymentKey($paymentKey);
	    
	    foreach ($paymentDetails as $paymentDetail)
		{
		$property = $paymentDetail->properties;
		foreach($property as $proper)
		{
		  if ($proper->typeId == 1)
		  {
			$tid = $proper->value;
		  }
		 if ($proper->typeId == 30)
		  {
			$status = $proper->value;
		  }
		}
		}
	   
        $this->getLogger(__METHOD__)->error('EventProcedure.triggerFunction', ['order' => $order]);
	    if(in_array($status, ['85', '91', '98', '99'])) {
        $this->paymentHelper->doCapture($order->id, $tid, $key);
	    } 
    }
}
