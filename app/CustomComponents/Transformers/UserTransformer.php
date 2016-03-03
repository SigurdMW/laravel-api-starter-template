<?php 

namespace CustomComponents\Transformers;


class UserTransformer extends Transformer {

	public function transform($user)
    {
        return [
        	'id'	=> $user['id'],
            'name' 	=> $user['name'],
            'email' => $user['email'],
        ];
    }
}