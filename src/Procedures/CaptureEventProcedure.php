<?php

namespace Novalnet\Procedures;

use Plenty\Modules\EventProcedures\Events\EventProceduresTriggered;
use Plenty\Modules\Order\Models\Order;
use Plenty\Plugin\Log\Loggable;
use Novalnet\Helper\PaymentHelper;
use Plenty\Modules\Payment\Contracts\PaymentRepositoryContract;


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
	    
	   $payments = pluginApp(\Plenty\Modules\Payment\Contracts\PaymentRepositoryContract::class);  
       	   $paymentDetails = $payments->getPaymentsByOrderId($order->id);
	    
	    $this->getLogger(__METHOD__)->error('details',$paymentDetails);
	    $this->getLogger(__METHOD__)->error('key',$paymentKey);
	    
	    foreach ($paymentDetails as $paymentDetail)
		{
		$property = $paymentDetail->properties;
		foreach($property as $proper)
		{
		  if ($proper->typeId == 1)
		  {
			$tid = $proper->value;
		  }
		}
		}
	    $this->getLogger(__METHOD__)->error('tid',$tid);
	  $this->paymentHelper->payments($order->id);  
	
	    
	   
        $this->getLogger(__METHOD__)->error('EventProcedure.triggerFunction', ['order' => $order]);
        $this->paymentHelper->doCapture($order->id, $tid);
    }
}
