<?php

namespace Larapie\Actions\Tests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Larapie\Actions\Action;
use Larapie\Actions\Tests\Actions\SimpleCalculator;
use Larapie\Actions\Tests\Actions\UpdateProfile;

class ResolvesValidationTest extends TestCase
{
    /** @test */
    public function it_uses_validation_rules_to_validate_attributes()
    {
        $attributes = [
            'operation' => 'substraction',
            'left' => 5,
            'right' => 2,
        ];

        $action = new class($attributes) extends SimpleCalculator
        {
            public function rules()
            {
                return [
                    'operation' => 'required|in:addition,substraction',
                    'left' => 'required|integer',
                    'right' => 'required|integer',
                ];
            }
        };

        $this->assertTrue($action->passesValidation());
        $this->assertEquals(3, $action->run());
    }

    /** @test */
    public function it_can_access_to_the_validated_data_after_validation()
    {
        $attributes = [
            'operation' => 'addition',
            'left' => 5,
            'right' => 2,
        ];

        $action = new class($attributes) extends SimpleCalculator
        {
            public function rules()
            {
                return [
                    'left' => 'required|integer',
                    'right' => 'required|integer',
                ];
            }
        };

        $this->assertTrue($action->passesValidation());
        $this->assertCount(2, $action->validated());
        $this->assertEquals(5, $action->validated()['left']);
        $this->assertEquals(2, $action->validated()['right']);
    }

    /** @test */
    public function it_filters_data_from_rules_recursively()
    {
        $attributes = [
            'array' => [
                [
                    'field_1' => 5,
                    'field_2' => 2,
                    'field_3' => 1
                ]
            ]
        ];

        $action = new class($attributes) extends Action
        {
            public function rules()
            {
                return [
                    'array' => 'required|array',
                    'array.*.field_1' => 'required|integer',
                    'array.*.field_2' => 'required|integer',
                ];
            }

            public function handle()
            {
                return $this->validated(true);
            }
        };
        $data = $array = $action->run();
        $this->assertArrayHasKey('array', $data);
        $this->assertArrayNotHasKey('field_3', $data['array'][0]);
    }

    /** @test */
    public function it_does_not_filters_data_from_rules_recursively()
    {
        $attributes = [
            'array' => [
                [
                    'field_1' => 5,
                    'field_2' => 2,
                    'field_3' => 1
                ]
            ]
        ];

        $action = new class($attributes) extends Action
        {
            public function rules()
            {
                return [
                    'array' => 'required|array',
                    'array.*.field_1' => 'required|integer',
                    'array.*.field_2' => 'required|integer',
                ];
            }

            public function handle()
            {
                return $this->validated(false);
            }
        };
        $data = $array = $action->run();
        $this->assertArrayHasKey('array', $data);
        $this->assertArrayHasKey('field_3', $data['array'][0]);
    }

    /** @test */
    public function it_throws_a_validation_exception_when_validator_fails()
    {
        $attributes = [
            'operation' => 'multiplication',
            'left' => 'five',
        ];

        $action = new class($attributes) extends SimpleCalculator
        {
            public function rules()
            {
                return [
                    'operation' => 'required|in:addition,substraction',
                    'left' => 'required|integer',
                    'right' => 'required|integer',
                ];
            }
        };

        try {
            $this->assertFalse($action->passesValidation());
            $action->run();
            $this->fail('Expected a ValidationException');
        } catch (ValidationException $e) {
            $this->assertEquals([
                'operation' => ['The selected operation is invalid.'],
                'left' => ['The left must be an integer.'],
                'right' => ['The right field is required.'],
            ], $e->errors());
        }
    }

    /** @test */
    public function it_can_define_complex_validation_logic()
    {
        $attributes = [
            'operation' => 'substraction',
            'left' => 5,
            'right' => 10,
        ];

        $action = new class($attributes) extends SimpleCalculator
        {
            public function withValidator($validator)
            {
                $validator->after(function ($validator) {
                    if ($this->operation === 'substraction' && $this->left <= $this->right) {
                        $validator->errors()->add('left', 'Left must be greater than right when substracting.');
                    }
                });
            }
        };

        try {
            $this->assertFalse($action->passesValidation());
            $action->run();
            $this->fail('Expected a ValidationException');
        } catch (ValidationException $e) {
            $this->assertEquals([
                'left' => ['Left must be greater than right when substracting.'],
            ], $e->errors());
        }
    }

    /** @test */
    public function it_can_create_its_own_validator_instance()
    {
        $action = new class(['operation' => 'valid']) extends Action
        {
            public function validator($factory)
            {
                return $factory->make($this->all(), ['operation' => 'in:valid']);
            }
        };

        $this->assertTrue($action->passesValidation());
    }

    /** @test */
    public function it_can_validate_data_directly_in_the_handle_method()
    {
        $action = new class(['operation' => 'valid']) extends Action
        {
            public function handle()
            {
                $first = $this->validate(['operation' => 'in:valid']);

                try {
                    $second = $this->validate(['operation' => 'not_in:valid']);
                } catch (\Throwable $th) {
                    $second = null;
                }

                return compact('first', 'second');
            }
        };

        $result = $action->run();
        $this->assertEquals(['operation' => 'valid'], $result['first']);
        $this->assertNull($result['second']);
    }

    /** @test */
    public function it_triggers_after_validation_method()
    {

        $action = new class() extends UpdateProfile
        {
            public $triggered = false;

            public function handle()
            {

            }

            protected function afterValidator(Validator $validator)
            {
                $this->triggered = true;
            }
        };
        $action->run();
        $this->assertTrue($action->triggered);
    }
}
