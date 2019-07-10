<?php

namespace Larapie\Actions\Tests;

use Illuminate\Validation\Rules\Dimensions;
use Larapie\Actions\Action;
use Larapie\Actions\Attribute;
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
            ->rule(['min:2', 'bool']);

        $rules = $attribute->getRules();

        $this->assertContains('integer', $rules);
        $this->assertContains('string', $rules);
        $this->assertContains('max:255', $rules);
        $this->assertContains('min:2', $rules);
        $this->assertContains('bool', $rules);
        $this->assertContains($dimensions, $rules);
    }

    /** @test */
    public function it_becomes_optional()
    {
        $attribute = RequiredAttribute::optional();

        $this->assertNotContains('required', $attribute->getRules());
    }

    /** @test */
    public function it_becomes_required()
    {
        $attribute = Attribute::required();

        $this->assertContains('required', $attribute->getRules());
    }

    /** @test */
    public function it_accepts_attributes_as_rule()
    {
        $action = new class () extends Action
        {

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

        $this->assertContains('required', $action->getRules()['name']);
        $this->assertContains('string', $action->getRules()['name']);
        $this->assertContains('max:255', $action->getRules()['name']);
    }
}
