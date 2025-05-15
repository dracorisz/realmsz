<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with(['founder', 'users'])
            ->where('id', Auth::user()->organization_id)
            ->first();

        return response()->json([
            'success' => true,
            'organization' => $organizations
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'website' => 'nullable|url|max:255',
                'phone' => 'nullable|string|max:20',
                'logo' => 'nullable|image|max:2048',
                'description' => 'nullable|string',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'industry' => 'nullable|string|max:100',
                'size' => 'nullable|string|max:50',
                'founded_year' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
                'social_media' => 'nullable|array',
                'settings' => 'nullable|array'
            ]);

            $data = $request->except('logo');

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = 'organizations/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
                
                Storage::disk('s3')->put($filename, file_get_contents($file), 'public');
                $data['logo'] = $filename;
            }

            $organization = Organization::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Organization created successfully',
                'organization' => $organization
            ]);

        } catch (\Exception $e) {
            Log::error('Organization creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create organization: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Organization $organization)
    {
        try {
            if ($organization->id !== Auth::user()->organization_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'website' => 'nullable|url|max:255',
                'phone' => 'nullable|string|max:20',
                'logo' => 'nullable|image|max:2048',
                'description' => 'nullable|string',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'industry' => 'nullable|string|max:100',
                'size' => 'nullable|string|max:50',
                'founded_year' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
                'social_media' => 'nullable|array',
                'settings' => 'nullable|array'
            ]);

            $data = $request->except('logo');

            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($organization->logo && Storage::disk('s3')->exists($organization->logo)) {
                    Storage::disk('s3')->delete($organization->logo);
                }

                $file = $request->file('logo');
                $filename = 'organizations/' . Str::random(40) . '.' . $file->getClientOriginalExtension();
                
                Storage::disk('s3')->put($filename, file_get_contents($file), 'public');
                $data['logo'] = $filename;
            }

            $organization->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Organization updated successfully',
                'organization' => $organization
            ]);

        } catch (\Exception $e) {
            Log::error('Organization update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update organization: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Organization $organization)
    {
        try {
            if ($organization->id !== Auth::user()->organization_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Delete logo if exists
            if ($organization->logo && Storage::disk('s3')->exists($organization->logo)) {
                Storage::disk('s3')->delete($organization->logo);
            }

            $organization->delete();

            return response()->json([
                'success' => true,
                'message' => 'Organization deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Organization deletion failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete organization: ' . $e->getMessage()
            ], 500);
        }
	}

    public function getMembers(Request $request)
	{
        try {
            $users = User::where('organization_id', Auth::user()->organization_id)
                ->with(['roles', 'permissions'])
                ->get();

            return response()->json([
                'success' => true,
                'users' => $users
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch organization members', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch organization members: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateMember(Request $request, User $user)
    {
        try {
            if ($user->organization_id !== Auth::user()->organization_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $request->validate([
                'role' => ['required', Rule::in(['admin', 'member', 'viewer'])],
                'permissions' => 'nullable|array'
            ]);

            $user->syncRoles([$request->role]);
            
            if ($request->has('permissions')) {
                $user->syncPermissions($request->permissions);
            }

            return response()->json([
                'success' => true,
                'message' => 'Member updated successfully',
                'user' => $user->load(['roles', 'permissions'])
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update member', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update member: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeMember(User $user)
    {
        try {
            if ($user->organization_id !== Auth::user()->organization_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Prevent removing the last admin
            if ($user->hasRole('admin') && 
                User::role('admin')->where('organization_id', $user->organization_id)->count() <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove the last admin'
                ], 400);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Member removed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to remove member', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove member: ' . $e->getMessage()
            ], 500);
        }
	}
}
