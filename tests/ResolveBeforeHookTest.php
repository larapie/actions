<?php

namespace Larapie\Actions\Tests;

use Larapie\Actions\Tests\Actions\UpdateProfile;

class ResolveBeforeHookTest extends TestCase
{
    /** @test */
    public function it_triggers_resolve_before_hook()
    {

        $action = new class() extends UpdateProfile
        {
            public $triggered = false;

            public function handle()
            {

            }

            protected function asObject()
            {
                $this->triggered= true;
            }
        };
        $action->run();
        $this->assertTrue($action->triggered);
    }
}
