<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserRegistration()
    {
        $user = ['name'=>'Mike','email'=>'baswetima@gmail.com','password'=>'my_password', 'role'=>'applicant'];
        $response = $this->post('/api/v1/users', $user);
        $response->assertResponseStatus(201);
        $response->seeJson(["User created"]);
    }

    public function testUserRegistrationWithoutFields()
    {
        $user = [];
        $response = $this->post('/api/v1/users', $user);
        $response->assertResponseStatus(422);
    }

    public function testLogin()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $credentials = [
            'email' => $user->email,
            'password' => $password
        ];
        $response = $this->post('/api/v1/login', $credentials);
        $response->assertResponseStatus(200);
    }

    public function testLoginWithInvalidCredentials()
    {

        $credentials = [
            'email' => 'basweti@gmail.com',
            'password' => 'password'
        ];
        $response = $this->post('/api/v1/login', $credentials);
        $response->assertResponseStatus(404);
    }
    public function testDeleteUser()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $response = $this->delete("/api/v1/users/{$user->id}");
        $response->assertResponseStatus(204);
    }

    public function testDeleteUserWithoutAuthorization()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $response = $this->delete("/api/v1/users/{$user->id}");
        $response->assertResponseStatus(401);
    }
    public function testDeleteUserWhoIsNotYou()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $user2 = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $response = $this->delete("/api/v1/users/{$user2->id}");
        $response->assertResponseStatus(403);
        $response->seeJson(["not allowed, you can only delete your own account."]);
    }

    public function testUpdateUser()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $response = $this->put("/api/v1/users/{$user->id}", ["name" => "Milkovich"]);
        $newUodatedUser = $this->get("/api/v1/users/{$user->id}");
        $response->assertResponseStatus(200);
        $newUodatedUser->seeJson(["name" => "Milkovich"]);
    }
    public function testUpdateUserWithoutAuthorization()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $response = $this->put("/api/v1/users/{$user->id}", ["name" => "Milkovich"]);
        $newUodatedUser = $this->get("/api/v1/users/{$user->id}");
        $response->assertResponseStatus(401);
    }
    public function testUpdateUserWhoIsNotYou()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $user2 = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $response = $this->put("/api/v1/users/{$user2->id}", ["name" => "Milkovich"]);
        $response->assertResponseStatus(403);
        $response->seeJson(["not allowed, you can only update your own account."]);
    }
    public function testGetAllUsers()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $user2 = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'me_and_myself'),
        ]);
        $this->be($user);
        $response = $this->get("/api/v1/users");
        $response->assertResponseStatus(200);
    }
    public function testGetAllUsersWithoutAuthorization()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $user2 = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'me_and_myself'),
        ]);
        $response = $this->get("/api/v1/users");
        $response->assertResponseStatus(401);
    }
    public function testGetOneUser()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $response = $this->get("/api/v1/users/{$user->id}");
        $response->assertResponseStatus(200);
    }
    public function testGetOneUserWithoutAuthorization()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $response = $this->get("/api/v1/users/{$user->id}");
        $response->assertResponseStatus(401);
    }
}

