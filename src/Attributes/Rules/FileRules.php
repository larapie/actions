<?php


namespace Larapie\Actions\Attributes\Rules;


trait FileRules
{
    public function image()
    {
        return $this->rule('image');
    }

    /*
     * Define dimensions with the properties as specified in the laravel documentation but in a kay value array
     * e.g. ["max_height" => 50]
     */
    public function dimensions(array $dimensions)
    {
        $dimensions = collect($dimensions)
            ->map(function ($key, $value) {
                return "$key=$value";
            })
            ->implode(',', '');

        return $this->rule('mimes:' . $dimensions);
    }

    public function mimes(string ...$extensions)
    {
        return $this->rule('mimes:' . implode(',', $extensions));
    }

    public function mimeTypes(string ...$types)
    {
        return $this->rule('mimetypes:' . implode(',', $types));
    }

    public function size(int $size)
    {
        return $this->rule("size:$size");
    }
}