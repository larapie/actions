<?php

namespace Larapie\Actions\Tests;

use http\Exception\RuntimeException;
use Illuminate\Validation\UnauthorizedException;
use Larapie\Actions\Tests\Actions\UpdateProfile;

class FailHookTest extends TestCase
{
    /** @test */
    public function it_fails_without_parameter()
    {
        $action = new class() extends UpdateProfile {
            public function handle()
            {
                throw new \RuntimeException();
            }

            protected function onFail()
            {
                throw new UnauthorizedException();
            }
        };
        $this->expectException(UnauthorizedException::class);
        $action->run();
    }

    /** @test */
    public function it_fails_with_parameter()
    {
        $action = new class() extends UpdateProfile {
            public function handle()
            {
                throw new \RuntimeException();
            }

            protected function onFail(\Throwable $exception)
            {
                throw new UnauthorizedException();
            }
        };
        $this->expectException(UnauthorizedException::class);
        $action->run();
    }

    /** @test */
    public function it_fails_without_additional_exception()
    {
        $action = new class() extends UpdateProfile {
            public function handle()
            {
                throw new \RuntimeException();
            }

            protected function onFail(\Throwable $exception)
            {

            }
        };
        $this->expectException(\RuntimeException::class);
        $action->run();
    }

}
