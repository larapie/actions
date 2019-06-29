<?php

namespace Larapie\Actions\Tests\Actions;

use Larapie\Actions\Action;
use Larapie\Actions\Tests\Stubs\User;

class UpdateProfile extends Action
{

    public function handle()
    {
        if ($this->has('avatar')) {
            return $this->delegateTo(UpdateProfilePicture::class);
        }

        return $this->delegateTo(UpdateProfileDetails::class);
    }
}