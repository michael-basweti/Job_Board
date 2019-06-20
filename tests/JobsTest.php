<?php
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Job;
use App\Application;
use App\User;
class JobTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @test
     *
     * @return void
     */
    public function testCreateJob()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $job = [
            'title'=>'Hey',
            'description'=>'hello world',
            'expected_income'=>'2006',
            'delivery_date'=>'02/02/1876',
            'start_date'=>'02/02/1876',
            'user_id'=>$user->id
        ];
        $response2 = $this->post('/api/v1/jobs', $job);
        $response2->assertResponseStatus(201);
    }

    public function testCreateJobWithoutAuth()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);

        $job = [
            'title'=>'Hey',
            'description'=>'hello world',
            'expected_income'=>'2006',
            'delivery_date'=>'02/02/1876',
            'start_date'=>'02/02/1876',
            'user_id'=>$user->id
        ];
        $response = $this->post('/api/v1/jobs', $job);
        $response->assertResponseStatus(401);
    }

    public function testCreateJobWithApplicantToken()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $job = [
            'title'=>'Hey',
            'description'=>'hello world',
            'expected_income'=>'2006',
            'delivery_date'=>'02/02/1876',
            'start_date'=>'02/02/1876',
            'user_id'=>$user->id
        ];
        $response2 = $this->post('/api/v1/jobs', $job);
        $response2->assertResponseStatus(403);
        $response2->seeJson(["Not allowed, Only Employers can create jobs"]);
    }

    public function testuserCanViewJobs()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class)->create();
        $this->be($user);
        $response = $this->get("/api/v1/jobs");
        $response->assertResponseStatus(200);
        $response->seeJson(['title' => $job->title]);
    }
    public function testuserCanSearchJobs()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class)->create();
        $this->be($user);
        $response = $this->get("/api/v1/jobs?name={$job->description}");
        $response->assertResponseStatus(200);
        $response->seeJson(['description' => $job->description]);
    }
    public function testuserCanLimitAndOffsetJobs()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class, 5)->create();
        $this->be($user);
        $response = $this->get("/api/v1/jobs?limit=1&offset=2");
        $response->assertResponseStatus(200);
    }
    public function testUserCannotViewJobsWithoutAuth()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class)->create();
        $response = $this->get("/api/v1/jobs");
        $response->assertResponseStatus(401);
    }
    public function testUserCannotViewOneJobWithoutAuth()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class)->create();
        $response = $this->get("/api/v1/jobs/{$job->id}");
        $response->assertResponseStatus(401);
    }
    public function testUserCanViewOneJob()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $job = factory(Job::class)->create();
        $response = $this->get("/api/v1/jobs/{$job->id}");
        $response->assertResponseStatus(200);
    }
    public function testUserCanViewOneJobWithoutAuth()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class)->create();
        $response = $this->get("/api/v1/jobs/{$job->id}/applications");
        $response->assertResponseStatus(401);
    }
    public function testUserCanViewOneJobWithApplications()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $job = factory(Job::class)->create();
        $response = $this->get("/api/v1/jobs/{$job->id}/applications");
        $response->assertResponseStatus(200);
    }
    public function testUserCanViewOneJobWithApplicationsNonExisting()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $job = factory(Job::class)->create();
        $response = $this->get("/api/v1/jobs/441/applications");
        $response->assertResponseStatus(404);
        $response->seeJson(["job not available"]);
    }

    public function testUserCannotViewNonExistingJob()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $job = factory(Job::class)->create();
        $response = $this->get("/api/v1/jobs/201");
        $response->assertResponseStatus(404);
        $response->seeJson(["job not available"]);
    }

    public function testUserCanUpdateJobWithAuthorization()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class)->create();
        $this->be($user);
        $response = $this->put("/api/v1/jobs/{$job->id}", ["title" => "New Title"]);
        $updatedBook = $this->get("/api/v1/jobs/{$job->id}");
        $response->assertResponseStatus(200);
        $updatedBook->seeJson(["title" => "New Title"]);
    }
    public function testUserCannotUpdateJobIfTheyAreApplicants()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class)->create();
        $this->be($user);
        $response = $this->put("/api/v1/jobs/{$job->id}", ["title" => "New Title"]);
        $response->assertResponseStatus(403);
        $response->seeJson(["Only employers can perform this action"]);
    }
    public function testUserCannotUpdateJobWithNoAuth()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class)->create();
        $response = $this->put("/api/v1/jobs/{$job->id}", ["title" => "New Title"]);
        $response->assertResponseStatus(401);
    }
    public function testUserCannotUpdateNonExistingJob()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job = factory(Job::class)->create();
        $this->be($user);
        $response = $this->put("/api/v1/jobs/304", ["name" => "New Name"]);
        $response->assertResponseStatus(404);
        $response->seeJson(["Job Not Found"]);
    }

    public function testUserCanDeleteJobWithAuthorization()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $job = factory(Job::class)->create();
        $response = $this->delete("/api/v1/jobs/{$job->id}");
        $response->assertResponseStatus(204);
    }
    public function testUserCannotDeleteAsApplicant()
    {
        $user = factory(User::class)->states('applicant')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $job = factory(Job::class)->create();
        $response = $this->delete("/api/v1/jobs/{$job->id}");
        $response->assertResponseStatus(403);
        $response->seeJson(["Only employers can perform this action"]);
    }
    public function testUserCanNotDeleteJobWithoutAuthorization()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $job= factory(Job::class)->create();
        $response = $this->delete("/api/v1/jobs/{$job->id}");
        $response->assertResponseStatus(401);
    }

    public function testDeleteJobNotExist()
    {
        $user = factory(User::class)->states('employer')->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $job = factory(Job::class)->create();
        $response = $this->delete("/api/v1/jobs/224");
        $response->assertResponseStatus(404);
        $response->seeJson(["job not available"]);
    }

}
