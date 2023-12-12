<?php
declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Employee;
use Illuminate\Http\Response;

class EmployeeService
{
    protected Employee $employee;

     /**
     * Create service instance
     *
     * @param Employee $employee
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Create a employee
     *
     * @param array $data
     * @return \App\Models\Employee
     */
    public function storeEmployee(array $data): \App\Models\Employee
    {
        $employee = $this->employee->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'date_hired' => $data['date_hired']
        ]);
        
        abort_if(
            ! $employee, 
            Response::HTTP_INTERNAL_SERVER_ERROR,
            'An error occured, please try again later'
        );

        return $employee;
    }

    /**
     * Update an employee
     *
     * @param integer $employeeId
     * @param array $data
     * @return \App\Models\Employee
     */
    public function updateEmployee(
        int $employeeId, 
        array $data
    ): \App\Models\Employee {

        $employee = $this->findEmployee($employeeId);
        $employee->updateOrFail($data);

        return $employee;
    }

    /**
     * Get a employee
     *
     * @param integer $employeeId
     * @return \App\Models\Employee
     */
    private function findEmployee(int $employeeId): \App\Models\Employee
    {
        return $this->employee
            ->query()
            ->findOrFail($employeeId);
    }

    /**
     * Delete employee
     *
     * @param integer $employeeId
     * @return void
     */
    public function deleteEmployee(int $employeeId)
    {
        return $this->findEmployee($employeeId)->delete();
    }
}
