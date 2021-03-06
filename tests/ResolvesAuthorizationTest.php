<?php

namespace Larapie\Actions\Tests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Larapie\Actions\Tests\Actions\SimpleCalculator;
use Larapie\Actions\Tests\Stubs\User;

class ResolvesAuthorizationTest extends TestCase
{
    /** @test */
    public function it_defines_authorization_logic_in_a_dedicated_method()
    {
        $attributes = [
            'operation' => 'addition',
            'left'      => 1,
            'right'     => 2,
        ];

        $action = new class($attributes) extends SimpleCalculator {
            public function authorize()
            {
                return $this->operation === 'addition';
            }
        };

        $this->assertTrue($action->passesAuthorization());
        $this->assertEquals(3, $action->run());
    }

    /** @test */
    public function it_throws_an_exception_when_user_is_not_authorized()
    {
        $this->expectException(AuthorizationException::class);

        $action = new class() extends SimpleCalculator {
            public function authorize()
            {
                return false;
            }
        };

        $this->assertFalse($action->passesAuthorization());
        $action->run();
    }

    /** @test */
    public function it_provides_a_shortcut_for_gate_checks()
    {
        Gate::define('perform-calculation', function (?User $user, $operation) {
            return $operation === 'addition';
        });

        $action = new class(['operation' => 'addition']) extends SimpleCalculator {
            public function authorize()
            {
                return $this->can('perform-calculation', $this->operation);
            }
        };

        $this->assertTrue($action->passesAuthorization());
    }

    /** @test */
    public function it_uses_the_acting_as_user_to_perform_gate_checks()
    {
        $alice = new User(['name' => 'Alice']);
        $bob = new User(['name' => 'Bob']);

        Gate::define('perform-calculation', function (User $user) {
            return $user->name === 'Alice';
        });

        $action = new class(['operation' => 'addition']) extends SimpleCalculator {
            public function authorize()
            {
                return $this->can('perform-calculation');
            }
        };

        $this->assertTrue($action->actingAs($alice)->passesAuthorization());
        $this->assertFalse($action->actingAs($bob)->passesAuthorization());
    }
}
