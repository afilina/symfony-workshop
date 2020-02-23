<?php

namespace Tests\Acceptance\Context;

use Behat\Behat\Context\Context;
use Tests\Acceptance\ApplicationState\ApplicationState;

class ResetApplicationState implements Context
{
    private ApplicationState $applicationState;

    public function __construct(ApplicationState $applicationState)
    {
        $this->applicationState = $applicationState;
    }

    /**
     * @beforeScenario
     */
    public function resetDatabase()
    {
        $this->applicationState->reset();
    }
}
