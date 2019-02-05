<?php
/**
 * This module is used for real time processing of
 * Novalnet payment module of customers.
 * Released under the GNU General Public License.
 * This free contribution made by request.
 * If you have found this script useful a small
 * recommendation as well as a comment on merchant form
 * would be greatly appreciated.
 *
 * @author       Novalnet
 * @copyright(C) Novalnet. All rights reserved. <https://www.novalnet.de/>
 */
 
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
class RefundEventProcedure
{
	use Loggable;
	
	/**
	 *
	 * @var PaymentHelper
	 */
	private $paymentHelper;
	
	/**
	 *
	 * @var PaymentService
	 */
	private $paymentService;
	
	/**
	 * Constructor.
	 *
	 * @param PaymentHelper $paymentHelper
	 * @param PaymentService $paymentService
	 */
	 
    public function __construct(PaymentHelper $paymentHelper, BasketRepositoryContract $basketRepository,
								PaymentService $paymentService)
    {
        $this->paymentHelper   = $paymentHelper;
	    
	    $this->paymentService  = $paymentService;
	}	
	
    /**
     * @param EventProceduresTriggered $eventTriggered
     * 
     */
    public function run(
        EventProceduresTriggered $eventTriggered
    ) {
        /* @var $order Order */
	 
	   $order = $eventTriggered->getOrder(); 
	  
	   $payments = pluginApp(\Plenty\Modules\Payment\Contracts\PaymentRepositoryContract::class);  
       	   $paymentDetails = $payments->getPaymentsByOrderId($order->id);
	   $orderAmount = (float) $order->amounts[0]->invoiceTotal;
	    
	    
	    foreach ($paymentDetails as $paymentDetail)
		{
			$property = $paymentDetail->properties;
			foreach($property as $proper)
			{
				  if($proper->typeId == 1)
				  {
						$tid = $proper->value;
				  }
				 if($proper->typeId == 30)
				  {
						$status = $proper->value;
				  }
			}
		}
	   
	    $paymentKey = $paymentDetails[0]->method->paymentKey;
	    
	   $key = $this->paymentService->getkeyByPaymentKey($paymentKey);
	    
	    
        $this->getLogger(__METHOD__)->error('EventProcedure.triggerFunction', ['order' => $order]);
	  if ($status == '100')   {
        $this->paymentHelper->doRefund($order->id, $tid, $key, $orderAmount); }
	    $paymentData['currency']    = $paymentDetails[0]->currency;
		$paymentData['paid_amount'] = (float) $orderAmount;
		$paymentData['tid']         = $tid;
		$paymentData['order_no']    = $order->id;
	    	$paymentData['type']        = 'debit';
		$paymentData['mop']         = $paymentDetails[0]->mopId;
	    $this->paymentHelper->createPlentyPayment($paymentData);
    }
}
