<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\User;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class DepartmentTest extends TestCase
{
    public function headerDetails()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        
        $header = ['Authorization' => 'Bearer ' . (string)$token];

        return [
            'user' => $user,
            'header' => $header
        ];
    }

    /** @test */
    public function should_store_department() 
    {
        ['user' => $user, 'header' => $header] = $this->headerDetails();
        
        $this->actingAs($user);

        $data = [
            'name' => 'Marketing',
            'description' => 'Marketing department for the company.',
        ];

        $response = $this->postJson('/api/v1/departments/store', $data, $header);
        
        $response->assertStatus(201);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertEquals('Department created successfully', $response->json()['message']);
    }

    /** @test ** */
    public function should_store_department_with_missing_required_fields() {
        ['user' => $user, 'header' => $header] = $this->headerDetails();
        
        $this->actingAs($user);

        $data = [
            'description' => 'Marketing department for the company.',
        ];

        $response = $this->postJson('/api/v1/departments/store', $data, $header);

        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('errors', $response->json());
        $this->assertStringContainsString('The name field is required', $response->json()['message']);
    }


    /** @test */
    public function should_update_department() 
    {
        ['user' => $user, 'header' => $header] = $this->headerDetails();
        
        $this->actingAs($user);

        $department = Department::factory()->create([
            'user_id' => $user->id
        ]);

        $data = [
            'name' => fake()->text(10),
            'department_id' => $department->id
        ];

        $response = $this->putJson('/api/v1/departments/' . $department->id . '/update' , $data, $header);

        $response->assertStatus(200);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertEquals('Department updated successfully', $response->json()['message']);
    }

    /** @test */
    public function should_not_update_department_with_unauthorized_access() 
    {
        ['header' => $header] = $this->headerDetails();
        
        $user = User::factory()->create();

        $this->actingAs($user);

        $department = Department::factory()->create([
            'user_id' => $user->id
        ]);

        $data = [
            'name' => fake()->text(10),
            'department_id' => $department->id
        ];

        $response = $this->putJson('/api/v1/departments/'  . $department->id . '/update', $data, $header);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertEquals('You cannot update this department.', $response->json()['message']);
    }

    /** @test */
    public function should_destroy_department() 
    {
        ['user' => $user, 'header' => $header] = $this->headerDetails();
        
        $this->actingAs($user);

        $department = Department::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->deleteJson('/api/v1/departments/' . $department->id . '/delete', [], $header);

        $response->assertStatus(200);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertEquals('Department deleted', $response->json()['message']);
    }

    /** @test */
    public function attach_employee_to_department() 
    {
        ['user' => $user, 'header' => $header] = $this->headerDetails();
        $this->actingAs($user);

        $department = Department::factory()->create([
            'user_id' => $user->id
        ]);
        $employee = Employee::factory()->create();

        $data = [
            'employee_id' => $employee->id,
            'department_id' => $department->id,
        ];

        $response = $this->postJson('/api/v1/departments/' . $department->id . '/attach/' . $employee->id . '/employee',
            $data, 
            $header
        );

        $response->assertStatus(200);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('data', $response->json());

    }
}