<?php

use Faker\Factory as Faker;

class ApiTester extends TestCase 
{
	// Faker 
	protected $fake;

	// Times custom function
	protected $times = 1;

	// Constructing to make faker available from children class
	function __construct() {
		$this->fake = Faker::create();
	}

	// Way to update the times a thing is beeing repeated
	protected function times($count){
		$this->times = $count;
		return $this;
	}

	// Get JSON from a specific URI
	protected function getJson($uri){
        return json_decode($this->call('GET', $uri)->getContent());
    }

    // Test if a object contains certain fields
    protected function assertObjectHasAttributes(){
        $args = func_get_args();
        $object = array_shift($args); 

        foreach($args as $attribute)
        {
            $this->assertObjectHasAttribute($attribute, $object);
        }
    }
}