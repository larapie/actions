<?php

namespace Larapie\Actions\Tests;

use Illuminate\Validation\Rules\Dimensions;
use Illuminate\Validation\ValidationException;
use Larapie\Actions\Action;
use Larapie\Actions\Attribute;
use Larapie\Actions\Attributes\ArrayAttribute;
use Larapie\Actions\Attributes\IntegerAttribute;
use Larapie\Actions\Attributes\StringAttribute;
use Larapie\Actions\Tests\Attributes\NullableAttribute;
use Larapie\Actions\Tests\Attributes\RequiredAttribute;

class AttributeTest extends TestCase
{
    /** @test */
    public function it_adds_rules()
    {
        $attribute = new Attribute();
        $attribute
            ->rule('integer')
            ->rule('string', 'max:255')
            ->rule($dimensions = new Dimensions())
            ->rule(['bool'], ['min:2']);

        $rules = $attribute->getRules();

        $this->assertContains('integer', $rules);
        $this->assertContains('string', $rules);
        $this->assertContains('max:255', $rules);
        $this->assertContains('min:2', $rules);
        $this->assertContains('bool', $rules);
        $this->assertContains($dimensions, $rules);
    }

    /** @test */
    public function it_accepts_null_as_value_in_nullable_attribute()
    {
        $action = new class () extends Action {

            public function rules()
            {
                return [
                    "nullable" => IntegerAttribute::default(5)->nullable()
                ];
            }

            public function handle()
            {
                return $this->validated();
            }
        };
        $data = $action->run(['nullable' => null]);
        $this->assertArrayHasKey('nullable', $data);
        $this->assertEquals(null, $data['nullable']);
    }


    /** @test */
    public function it_becomes_optional()
    {
        $attribute = RequiredAttribute::optional();

        $this->assertNotContains('required', $attribute->getRules());
    }

    /** @test */
    public function it_accepts_arrays_of_1_size()
    {
        $action = new class () extends Action {

            public function rules()
            {
                return [
                    "array" => ArrayAttribute::required()->size(1)
                ];
            }

            public function handle()
            {
                return $this->validated();
            }

            public function getRules()
            {
                return $this->buildRules();
            }
        };

        $rules = $action->getRules();

        $this->assertContains('size:1', $rules['array']);

        $data = $action->run(['array' => [1]]);

        $this->expectException(ValidationException::class);
        $data = $action->run(['array' => []]);
    }

    /** @test */
    public function it_becomes_required()
    {
        $attribute = Attribute::required();

        $this->assertContains('required', $attribute->getRules());
    }

    /** @test */
    public function string_attribute_has_rules_()
    {
        $attribute = StringAttribute::required()
            ->email()
            ->min(5)
            ->max(100)
            ->endsWith('.com', '.org');


        $rules = $attribute->getRules();
        $this->assertContains('required', $rules);
        $this->assertContains('ends_with:.com,.org', $rules);
        $this->assertContains('min:5', $rules);
        $this->assertContains('max:100', $rules);
        $this->assertContains('required', $rules);
    }

    /** @test */
    public function it_accepts_attributes_as_rule()
    {
        $action = new class () extends Action {

            public function rules()
            {
                return [
                    "name" => Attribute::required()->rule('string|max:255')
                ];
            }

            public function getRules()
            {
                return $this->buildRules();
            }
        };

        $rules = $action->getRules();

        $this->assertContains('required', $rules['name']);
        $this->assertContains('string', $rules['name']);
        $this->assertContains('max:255', $rules['name']);
    }
}
