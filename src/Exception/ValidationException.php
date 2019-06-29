<?php

namespace Larapie\Actions\Exception;

use Illuminate\Contracts\Validation\Validator;

class ValidationException extends \Exception
{
    protected $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
        parent::__construct($this->buildMessage($validator));
    }

    protected function buildMessage(Validator $validator)
    {
        $baseMessage = 'The given data was invalid. ';
        foreach ($validator->getMessageBag()->getMessages() as $attribute => $messages) {
            foreach ($messages as $message) {
                $baseMessage = $baseMessage.' - '.$message;
            }
        }

        return $baseMessage;
    }
}
