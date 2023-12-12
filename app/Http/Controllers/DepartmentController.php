<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Response;
use App\Http\Services\DepartmentService;
use App\Http\Resources\DepartmentResource;
use App\Http\Requests\AttachEmployeeRequest;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;

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
        ], Response::HTTP_CREATED);
    }

    /**
     * Update department
     *
     * @param UpdateDepartmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Department $department, UpdateDepartmentRequest $request)
    {
        $this->authorize('update', $department);

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
    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        return response()->json([
            'message' => 'Department deleted',
            'data' => $department->delete()
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
