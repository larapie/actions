<?php

namespace Larapie\Actions\Tests;

use Illuminate\Validation\Rules\Dimensions;
use Larapie\Actions\Action;
use Larapie\Actions\Attribute;
use Larapie\Actions\Tests\Attributes\RequiredAttribute;

class DefaultTest extends TestCase
{
    /** @test */
    public function it_does_not_get_overriden_by_default_value()
    {
        $action = new class (["name" => "nondefault"]) extends Action
        {

            public function rules()
            {
                return [
                    "name" => Attribute::required()->rule('string|max:255')
                ];
            }

            public function default()
            {
                return [
                    "name" => "default"
                ];
            }

            public function handle()
            {
                return $this->validated()['name'];
            }
        };

        $this->assertEquals('nondefault', $action->run());
    }

    /** @test */
    public function it_inserts_default_from_attribute()
    {
        $action = new class () extends Action
        {
            public function rules()
            {
                return [
                    "name" => Attribute::default('default')->rule('string|max:255')
                ];
            }

            public function handle()
            {
                return $this->validated()['name'];
            }
        };

        $this->assertEquals('default', $action->run());
    }

    /** @test */
    public function it_inserts_default_from_method()
    {
        $action = new class () extends Action
        {

            public function rules()
            {
                return [
                    "name" => Attribute::required()->rule('string|max:255')
                ];
            }

            public function default()
            {
                return [
                    "name" => "default"
                ];
            }

            public function handle()
            {
                return $this->validated()['name'];
            }
        };

        $this->assertEquals('default', $action->run());
    }
}
