<?php
namespace App\Http\Services;

use App\Models\Department;
use Illuminate\Http\Response;

class DepartmentService
{
    protected Department $department;
    
    /**
     * Create service instance
     *
     * @param Department $department
     */
    public function __construct(Department $department)
    {
        $this->department = $department;
    }

    /**
     * Create a department
     *
     * @param array $data
     * @return \App\Models\Department
     */
    public function storeDepartment(array $data, int $userId): \App\Models\Department
    {
        $department = $this->department->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'user_id' => $userId
        ]);
        
        abort_if(
            ! $department, 
            Response::HTTP_INTERNAL_SERVER_ERROR,
            'An error occured, please try again later'
        );

        return $department;
    }

    /**
     * Update a department
     *
     * @param integer $departmentId
     * @param array $data
     * @return \App\Models\Department
     */
    public function updateDepartment(
        int $departmentId, 
        array $data
    ): \App\Models\Department {
        
        $department = $this->findDepartment($departmentId);
        $department->updateOrFail($data);

        return $department;
    }

    /**
     * Get a department
     *
     * @param integer $departmentId
     * @return \App\Models\Department
     */
    private function findDepartment(int $departmentId): \App\Models\Department
    {
        return $this->department
            ->query()
            ->findOrFail($departmentId);
    }

    /**
     * Delete department
     *
     * @param integer $departmentId
     * @return void
     */
    public function deleteDepartment(int $departmentId)
    {
        return $this->findDepartment($departmentId)->delete();
    }

}
