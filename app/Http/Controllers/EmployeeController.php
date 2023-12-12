<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Services\EmployeeService;
use App\Http\Resources\EmployeeResource;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;
    
    /**
     * Create controller instance
     *
     * @param EmployeeService $employeeService
     */
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    /**
     * Store a department
     *
     * @param StoreDepartmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreEmployeeRequest $request)
    {
        $department = $this->employeeService->storeEmployee(
            $request->validated()
        );

        return response()->json([
            'message' => 'Employee created successfully',
            'data' => new EmployeeResource($department)
        ], Response::HTTP_CREATED);
    }

    /**
     * Update employee
     *
     * @param UpdateEmployeeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateEmployeeRequest $request)
    {
        $department = $this->employeeService->updateEmployee(
            $request->employee_id,
            $request->only([
                'name', 
                'email',
                'phone_number',
                'date_hired'
                ])
        );

        return response()->json([
            'message' => 'Employee updated successfully',
            'data' => new EmployeeResource($department)
        ]);
    }

    /**
     * Delete a employee
     *
     * @param integer $employee
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $employee)
    {
        return response()->json([
            'message' => 'Employee deleted',
            'data' => $this->employeeService->deleteEmployee($employee)
        ]);
    }
}
