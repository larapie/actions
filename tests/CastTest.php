<?php

namespace Larapie\Actions\Tests;

use Larapie\Actions\Action;
use Larapie\Actions\Attribute;
use Larapie\Actions\Attributes\BooleanAttribute;
use Larapie\Actions\Attributes\StringAttribute;

class CastTest extends TestCase
{

    /** @test */
    public function it_casts_to_bool()
    {
        $action = new class () extends Action
        {
            public function rules()
            {
                return [
                    "bool_1" => Attribute::required()->cast('bool'),
                    "bool_2" => StringAttribute::required()
                        ->cast(function ($value) {
                            return (bool)$value;
                        }),
                    "bool_3" => BooleanAttribute::required(),
                    "bool_4" => BooleanAttribute::default(true),
                ];
            }

            public function handle()
            {
                return $this->validated();
            }
        };

        $data = $action->run([
            "bool_1" => true,
            "bool_2" => "1",
            "bool_3" => true,
        ]);

        $this->assertTrue(true === $data['bool_1']);
        $this->assertTrue(true === $data['bool_2']);
        $this->assertTrue(true === $data['bool_3']);
        $this->assertTrue(true === $data['bool_4']);
    }

    /** @test */
    public function it_casts_to_string()
    {
        $action = new class () extends Action
        {
            public function rules()
            {
                return [
                    "string_1" => Attribute::required()->cast('string'),
                    "string_2" => Attribute::required()
                        ->cast(function ($value) {
                            return (string)$value;
                        }),
                    "string_3" => StringAttribute::required(),
                    "string_4" => StringAttribute::default("5"),
                ];
            }

            public function handle()
            {
                return $this->validated();
            }
        };

        $data = $action->run([
            "string_1" => 5,
            "string_2" => 1,
            "string_3" => "qsgdgqsd",
        ]);

        $this->assertTrue("5" === $data['string_1']);
        $this->assertTrue("1" === $data['string_2']);
        $this->assertTrue("qsgdgqsd" === $data['string_3']);
        $this->assertTrue("5" === $data['string_4']);
    }
}
