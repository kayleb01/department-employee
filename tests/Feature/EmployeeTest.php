<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

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
    public function should_store_an_employee() 
    {
        ['user' => $user, 'header' => $header] = $this->headerDetails();
        $this->actingAs($user);
    
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone_number' => '09088939903',
            'date_hired' => '2023-10-26',
        ];
    
        $response = $this->postJson('/api/v1/employees/store', $data, $header);
    
        $response->assertStatus(201);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertEquals('Employee created successfully', $response->json()['message']);
    }
    
    /** @test */
    public function should_not_store_employee_with_missing_required_fields()
    {
        ['user' => $user, 'header' => $header] = $this->headerDetails();
        $this->actingAs($user);
    
        $data = [
            'email' => 'johndoe@example.com',
            'phone_number' => '+1234567890',
            'date_hired' => '2023-10-26',
        ];
    
        $response = $this->postJson('/api/v1/employees/store', $data, $header);
    
        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('errors', $response->json());
        $this->assertStringContainsString('The name field is required', $response->json()['message']);
    }
    
    /** @test */
    public function should_update_employee() 
    {
        ['user' => $user, 'header' => $header] = $this->headerDetails();
        $this->actingAs($user);

        $employee = Employee::factory()->create();
    
        $data = [
            'name' => 'Updated Employee Name',
            'employee_id' => $employee->id
        ];
    
        $response = $this->putJson('/api/v1/employees/' . $employee->id . '/update', $data, $header);
    
        $response->assertStatus(200);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertEquals('Employee updated successfully', $response->json()['message']);
    }
    
    /** @test */
    public function should_destroy_an_employee()
    {
        ['user' => $user, 'header' => $header] = $this->headerDetails();
        $this->actingAs($user);

        $employee = Employee::factory()->create();

        $response = $this->deleteJson('/api/v1/employees/' . $employee->id . '/delete', [], $header);
    
        $response->assertStatus(200);
        $this->assertArrayHasKey('message', $response->json());
        $this->assertEquals('Employee deleted', $response->json()['message']);
    }
}
