<?php

namespace Larapie\Actions\Tests;

use Larapie\Actions\Action;
use Larapie\Actions\Attribute;
use Larapie\Actions\Attributes\BooleanAttribute;

class CastTest extends TestCase
{

    /** @test */
    public function it_casts_value()
    {
        $action = new class () extends Action
        {
            public function rules()
            {
                return [
                    "name" => Attribute::required()->rule('min:1|string'),
                    "bool" => BooleanAttribute::default("1")
                ];
            }

            public function handle()
            {
                return $this->validated();
            }
        };

        $data = $action->run([
            "name" => $name ="aname"
        ]);

        $this->assertEquals(true, $data['bool']);
        $this->assertEquals($name, $data['name']);
    }
}
