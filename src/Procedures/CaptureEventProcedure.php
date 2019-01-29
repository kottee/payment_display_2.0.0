<?php

namespace Novalnet\Procedures;

use Plenty\Modules\EventProcedures\Events\EventProceduresTriggered;
use Plenty\Modules\Order\Models\Order;
use Plenty\Plugin\Log\Loggable;

/**
 * Class CaptureEventProcedure
 */
class CaptureEventProcedure
{
	use Loggable;
	
    /**
     * @param EventProceduresTriggered $eventTriggered
     * @param Capture $captureService
     */
    public function run(
        EventProceduresTriggered $eventTriggered,
    ) {
        /* @var $order Order */
        $order = $eventTriggered->getOrder();
		$this->getLogger(__METHOD__)->info('EventProcedure.triggerFunction_TEST', 'TEST');
        $this->getLogger(__METHOD__)->info('EventProcedure.triggerFunction', ['order' => $order]);
        //$captureService->doCapture($order);
    }
}
