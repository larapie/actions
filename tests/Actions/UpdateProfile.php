<?php

namespace Larapie\Actions\Tests\Actions;

use Larapie\Actions\Action;

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
