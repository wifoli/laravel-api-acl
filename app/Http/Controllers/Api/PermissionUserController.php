<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionUserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    }

    public function syncPermissionsUser(Request $request, string $id)
    {
        $result = $this->userRepository->syncPermissions($id, $request->get('permissions', []));

        if (!$result) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'Permissions sync successfully']);
    }

    public function getPermissionsOfUser(string $id)
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $permissions = $this->userRepository->getPermissionsByUserId($id);

        return PermissionResource::collection($permissions);
    }
}
