<?php

namespace Larapie\Actions\Tests;

use Larapie\Actions\Tests\Actions\UpdateProfile;
use Larapie\Actions\Tests\Actions\UpdateProfileDetails;
use Larapie\Actions\Tests\Actions\UpdateProfilePicture;
use Larapie\Actions\Tests\Stubs\User;

class SuccessHookTest extends TestCase
{
    /** @test */
    public function it_gets_marked_as_completed()
    {
        $action = new class() extends UpdateProfile {
            public $completed = false;

            public function handle()
            {
                if ($this->has('avatar')) {
                    return $this->delegateTo(UpdateProfilePicture::class);
                }

                return $this->delegateTo(UpdateProfileDetails::class);
            }

            protected function onSuccess()
            {
                $this->completed = true;
            }
        };

        $action->run();

        $this->assertTrue($action->completed);
    }

    /** @test */
    public function it_resolves_first_non_typed_parameter()
    {
        $action = new class() extends UpdateProfile {
            public $result;

            protected function onSuccess($result)
            {
                $this->result = $result;
            }
        };

        $result = $action->run();

        $this->assertEquals($result, $action->result);
    }

    /** @test */
    public function it_resolves_first_correctly_typed_parameter()
    {
        $action = new class() extends UpdateProfile {
            public $result;

            protected function onSuccess(string $result)
            {
                $this->result = $result;
            }
        };

        $result = $action->run();

        $this->assertEquals($result, $action->result);
    }

    /** @test */
    public function it_resolves_parameter_with_same_name_as_result_type()
    {
        $action = new class() extends UpdateProfile {
            public $result;

            public function handle()
            {
                return new User();
            }

            protected function onSuccess($result, $user)
            {
                $this->result = $user;
            }
        };

        $result = $action->run();

        $this->assertEquals($result, $action->result);
    }

    /** @test */
    public function it_resolves_first_parameter_of_correct_type()
    {
        $action = new class() extends UpdateProfile {
            public $result;

            public function handle()
            {
                return new User();
            }

            protected function onSuccess(?string $result, $someparameter, User $user)
            {
                $this->result = $user;
            }
        };

        $action->run();

        $this->assertInstanceOf(User::class, $action->result);
    }
}
