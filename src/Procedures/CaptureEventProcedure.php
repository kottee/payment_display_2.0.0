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
	    $amount = $order->amounts[0]->invoiceTotal;
	    
	  
	    
	   $payments = pluginApp(\Plenty\Modules\Payment\Contracts\PaymentRepositoryContract::class);  
       	   $paymentDetails = $payments->getPaymentsByOrderId($order->id);
	    $paymentKey = $paymentDetails[0]->method->paymentKey;
	    
	   $key = $this->paymentService->getkeyByPaymentKey($paymentKey);	
	    $mop = $paymentDetails[0]->mopId;
	    $currency = $paymentDetails[0]->currency;
	    
	    
	    
	    
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
	    if(in_array($status, ['75', '85', '91', '98', '99'])) {
		   
        $this->paymentHelper->doCaptureVoid($order,$paymentDetails, $tid, $key, true);
	    } 
	    
	    
	    
	    
	    
    }
}
