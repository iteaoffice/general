<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category  General
 * @package   Factory
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\Listener;

use SlmQueue\Worker\WorkerEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

/**
 * Class SendEmailListener
 * @package General\Listener
 */
class SendEmailListener implements ListenerAggregateInterface
{
    /**
     * @var array
     */
    protected $listeners = [];
    protected $runCount = 0;
    protected $maxRuns = 5;
    protected $state = '';

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            WorkerEvent::EVENT_FINISH,
            [$this, 'onSomeListener']
        );
        $this->listeners[] = $events->attach(
            WorkerEvent::EVENT_BOOTSTRAP,
            [$this, 'onBootstrap']
        );
        $this->listeners[] = $events->attach(
            WorkerEvent::EVENT_FINISH,
            [$this, 'onFinish']
        );

    }

    /**
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * @param WorkerEvent $event
     */
    public function onSomeListener($event)
    {
        $this->runCount++;

        if ($this->maxRuns && $this->runCount >= $this->maxRuns) {
            $event->exitWorkerLoop();

            $this->state = sprintf('maximum of %s jobs processed', $this->runCount);
        } else {
            $this->state = sprintf('%s jobs processed', $this->runCount);
        }

    }

    /**
     * @param WorkerEvent $e
     */
    public function onPreProcess($e)
    {
        // pre job execution code
    }

    /**
     * @param WorkerEvent $e
     */
    public function onBootstrap($e)
    {
        // setup code
    }

    /**
     * @param WorkerEvent $e
     */
    public function onFinish($e)
    {
        // teardown code
    }
}
