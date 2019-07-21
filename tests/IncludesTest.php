<?php

namespace Larapie\Actions\Tests;

use Illuminate\Validation\Rules\Dimensions;
use Larapie\Actions\Action;
use Larapie\Actions\Attribute;
use Larapie\Actions\Attributes\StringAttribute;
use Larapie\Actions\Tests\Attributes\RequiredAttribute;

class IncludesTest extends TestCase
{
    /** @test */
    public function it_gets_overriden_by_include_value()
    {
        $action = new class (["name" => "nondefault"]) extends Action
        {
            public function rules()
            {
                return [
                    "name" => Attribute::required()->rule('string|max:255')
                ];
            }

            public function includes()
            {
                return [
                    "name" => "included"
                ];
            }

            public function handle()
            {
                return $this->validated()['name'];
            }
        };

        $this->assertEquals('included', $action->run());
    }

    /** @test */
    public function it_includes_value()
    {
        $action = new class () extends Action
        {
            public function handle()
            {
                return $this->validated()['name'];
            }

            public function includes()
            {
                return [
                    "name" => "included"
                ];
            }
        };

        $this->assertEquals('included', $action->run(["name" => 'notincluded']));
    }
}
