<?php

namespace App\Http\Controllers\Api;

use App\DTO\Permissions\CreatePermissionDTO;
use App\DTO\Permissions\EditPermissionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePermissionRequest;
use App\Http\Requests\Api\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionRepository $permissionRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $permissions = $this->permissionRepository->getPaginate(
            totalPerPage: $request->get('totalPerPage', 15),
            page: $request->get('page', 1),
            filter: $request->get('filter', '')
        );

        return PermissionResource::collection($permissions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $permission = $this->permissionRepository->createNew(new CreatePermissionDTO(...$request->validated()));

        return new PermissionResource($permission);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$permission = $this->permissionRepository->findById($id)) {
            return response()->json(['message' => 'Permission not found'], Response::HTTP_NOT_FOUND);
        }

        return new PermissionResource($permission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, string $id)
    {
        $result = !$this->permissionRepository->update(new EditPermissionDTO(...[$id, ...$request->validated()]));

        if ($result) {
            return response()->json(['message' => 'Permission not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'Permission updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = !$this->permissionRepository->delete($id);

        if ($result) {
            return response()->json(['message' => 'Permission not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->noContent();
    }
}
