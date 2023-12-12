<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachEmployeeRequest;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Http\Services\DepartmentService;

class DepartmentController extends Controller
{
    protected DepartmentService $departmentService;

    /**
     * Create controller instance
     *
     * @param DepartmentService $departmentService
     */
   public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    /**
     * Store a department
     *
     * @param StoreDepartmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDepartmentRequest $request)
    {
        $department = $this->departmentService->storeDepartment(
            $request->validated(), 
            $request->user()->id
        );

        return response()->json([
            'message' => 'Department created successfully',
            'data' => new DepartmentResource($department)
        ]);
    }

    /**
     * Update department
     *
     * @param UpdateDepartmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateDepartmentRequest $request)
    {
        $department = $this->departmentService->updateDepartment(
            $request->department_id,
            $request->only(['name', 'desctiption'])
        );

        return response()->json([
            'message' => 'Department updated successfully',
            'data' => new DepartmentResource($department)
        ]);
    }

    /**
     * Delete a department
     *
     * @param integer $department
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $department)
    {
        return response()->json([
            'message' => 'Department deleted',
            'data' => $this->departmentService->deleteDepartment($department)
        ]);
    }

    /**
     * Attach employee to department
     *
     * @param AttachEmployeeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachEmployee(AttachEmployeeRequest $request)
    {
        $department = $this->departmentService->attachEmployee(
            $request->department_id,
            $request->employee_id
        );

        return response()->json([
            'message' => 'Employee attached to department successfully',
            'data' => new DepartmentResource($department)
        ]);
    }
}
