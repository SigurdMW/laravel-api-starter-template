<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersTest extends ApiTester
{

    /** @test */

    public function it_fetches_users()
    {
        // Arrange
        $this->times(1)->makeUser(['email' => 'sally3@example.com']);

        Auth::loginUsingId(1);
        // Act
        $this->getJson('api/v1/users');

        // Assert
        $this->assertResponseOK();

        // See in database 
        $this->seeInDatabase('users', ['email' => 'sally3@example.com']);
    }

    private function makeUser($userFields = []){
        $user = array_merge([
                'name' => $this->fake->name,
                'email' => $this->fake->email,
                'password' => bcrypt($this->fake->word)
            ], $userFields);

        while($this->times--) User::create($user);
    }

    // Helpfull function:
    // $this->assertObjectHasAttribute('name', $user)
    
    /* Can make the following based on that knowledge:

    private function assertObjectHasAttributes($user, 'name', 'email'){
        $args = func_get_args();
        $object = array_shift($args); 

        foreach($args as $attribute)
        {
            $this->assertObjectHasAttribute($attribute, $object);
        }
    }

    Usage:
    $this->assertObjectHasAttributes($user, 'name', 'email');
    
    Solves:
    Checks if the object has the specified fields

    */

    // NB! In single test
    // Might have to use:

    // $user = $this->getJson('api/v1/users')->data;

    // Other helpful methods:
    // $this->assertResponseStatus(404);
 
}
