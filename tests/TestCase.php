<?php

namespace Larapie\Actions\Tests;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Larapie\Actions\Tests\Stubs\User;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp() : void
    {
        parent::setUp();
        $this->loadLaravelMigrations();
        $this->actingAs($userA = $this->createUser());
    }
    protected function getPackageProviders($app)
    {
        return ['Larapie\Actions\LarapieActionServiceProvider'];
    }

    protected function getEnvironmentSetUp($app)
    {
        //
    }

    public function createUser($data = [])
    {
        return User::create(array_merge([
            'name'     => 'John Doe',
            'email'    => rand().'@gmail.com',
            'password' => bcrypt('secret'),
        ], $data));
    }

    public function createRequest($method, $route, $url, $data = [], $user = null)
    {
        $request = Request::createFromBase(
            SymfonyRequest::create($url, $method)
        );

        $request->setRouteResolver(function () use ($method, $route, $request) {
            return (new Route($method, $route, []))->bind($request);
        });

        return $request->merge($data);
    }
}
