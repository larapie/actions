<?php

namespace Larapie\Actions\Tests;

use Illuminate\Http\Request;
use Larapie\Actions\Tests\Actions\SimpleCalculator;

class RunningAsTest extends TestCase
{
    /** @test */
    public function it_keeps_track_of_how_actions_ran_as_objects()
    {
        $action = new SimpleCalculator();

        $this->assertTrue($action->runningAs('object'));
    }

    /** @test */
    public function it_keeps_track_of_how_actions_ran_as_controllers()
    {
        $action = new SimpleCalculator();
        $request = (new Request())->merge(['operation' => 'addition']);

        $action->runAsController($request);

        $this->assertTrue($action->runningAs('controller'));
    }
}
