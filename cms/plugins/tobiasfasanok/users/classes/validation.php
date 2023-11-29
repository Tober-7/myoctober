<?php namespace Tobiasfasanok\Users\Classes;

class Validation {
    const messages = [
        'required' => ':attribute is required.',
        'email' => ':attribute must be a valid email.',
        'min' => ':attribute must be at least :min characters long.',

        'same' => ':attribute must match the :other',
        
        'password.exists' => 'invalid :attribute.',

        'unique' => ':attribute has already been taken.',
        'newPassword.unique' => ':attribute cannot match the old password.',
    ];
}

?>