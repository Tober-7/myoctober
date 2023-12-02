<?php namespace Tobiasfasanok\Users\Classes;

class Validation {
    const messages = [
        'required' => ':attribute is required.',
        'email' => ':attribute must be a valid email.',
        'min' => ':attribute must be at least :min characters long.',
        'same' => ':attribute must match the :other',
        'unique' => ':attribute has already been taken.',
        'different' => ':attribute must be different from :other.',
    ];
}

?>