<?php
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Job;
use App\Application;
use App\User;
class ApplicationsTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @test
     *
     * @return void
     */
    public function testCreateApplication()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class)->create();
        $this->be($user);
        $application = [
            'title'=>'Hey',
            'description'=>'hello world',
            'job_id'=>$job->id,
            'user_id'=>$user->id,
            'user_name'=>$user->name
        ];
        $response2 = $this->post('/api/v1/applications', $application);
        $response2->assertResponseStatus(201);
    }

    public function testCreateApplicationWithoutAuth()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class)->create();
        $application = [
            'title'=>'Hey',
            'description'=>'hello world',
            'job_id'=>$job->id,
            'user_id'=>$user->id,
            'user_name'=>$user->name
        ];
        $response = $this->post('/api/v1/applications', $application);
        $response->assertResponseStatus(401);
    }

    public function testCreateJobWithEmployerToken()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $job = factory(Job::class)->create();
        $application = [
            'title'=>'Hey',
            'description'=>'hello world',
            'job_id'=>$job->id,
            'user_id'=>$user->id,
            'user_name'=>$user->name
        ];
        $response2 = $this->post('/api/v1/applications', $application);
        $response2->assertResponseStatus(403);
        $response2->seeJson(["Not allowed, Only Applicants can apply for jobs"]);
    }

    public function testuserCanViewApplications()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $application = factory(Application::class)->create();
        $this->be($user);
        $response = $this->get("/api/v1/applications");
        $response->assertResponseStatus(200);
        $response->seeJson(['title' => $application->title]);
    }
    public function testUserCannotViewApplicationsWithoutAuth()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $application = factory(Application::class)->create();
        $response = $this->get("/api/v1/applications");
        $response->assertResponseStatus(401);
    }
    public function testUserCannotViewOneApplicationsWithoutAuth()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $application = factory(Application::class)->create();
        $response = $this->get("/api/v1/applications/{$application->id}");
        $response->assertResponseStatus(401);
    }
    public function testUserCanViewOneJob()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $application = factory(Application::class)->create();
        $response = $this->get("/api/v1/applications/{$application->id}");
        $response->assertResponseStatus(200);
    }

    public function testUserCannotViewNonExistingApplication()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $application = factory(Application::class)->create();
        $response = $this->get("/api/v1/applications/201");
        $response->assertResponseStatus(404);
        $response->seeJson(["application not available"]);
    }

    public function testUserCanUpdateApplicationWithAuthorization()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $application = factory(Application::class)->create();
        $this->be($user);
        $response = $this->put("/api/v1/applications/{$application->id}", ["title" => "New Title"]);
        $updatedBook = $this->get("/api/v1/applications/{$application->id}");
        $response->assertResponseStatus(200);
        $updatedBook->seeJson(["title" => "New Title"]);
    }
    public function testUserCannotUpdateApplicationIfTheyAreEmployers()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $application = factory(Application::class)->create();
        $this->be($user);
        $response = $this->put("/api/v1/applications/{$application->id}", ["title" => "New Title"]);
        $response->assertResponseStatus(403);
        $response->seeJson(["Only applicants can perform this action"]);
    }
    public function testUserCannotUpdateApplicationWithNoAuth()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $application = factory(Application::class)->create();
        $response = $this->put("/api/v1/applications/{$application->id}", ["title" => "New Title"]);
        $response->assertResponseStatus(401);
    }
    public function testUserCannotUpdateNonExistingJob()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $application = factory(Application::class)->create();
        $this->be($user);
        $response = $this->put("/api/v1/applications/304", ["name" => "New Name"]);
        $response->assertResponseStatus(404);
        $response->seeJson(["application not available"]);
    }

    public function testUserCanDeleteApplicationWithAuthorization()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $application = factory(Application::class)->create();
        $response = $this->delete("/api/v1/applications/{$application->id}");
        $response->assertResponseStatus(204);
    }
    public function testUserCannotDeleteAsEmployer()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $application = factory(Application::class)->create();
        $response = $this->delete("/api/v1/applications/{$application->id}");
        $response->assertResponseStatus(403);
        $response->seeJson(["Only applicants can perform this action"]);
    }
    public function testUserCanNotDeleteApplicationWithoutAuthorization()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $application= factory(Application::class)->create();
        $response = $this->delete("/api/v1/applications/{$application->id}");
        $response->assertResponseStatus(401);
    }

    public function testDeleteApplicationNotExist()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $application = factory(Application::class)->create();
        $response = $this->delete("/api/v1/applications/224");
        $response->assertResponseStatus(404);
        $response->seeJson(["application not available"]);
    }

}
