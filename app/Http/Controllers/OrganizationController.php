<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of organizations with search and status filters.
     */
    public function index()
    {
        // withCount adds a 'members_count' attribute to your results
        $query = Organization::withCount('members');

        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $organizations = $query->latest()->paginate(10)->withQueryString();

        // Statistics for dashboard cards
        $summary = [
            'total'    => Organization::count(),
            'active'   => Organization::where('status', 'active')->count(),
            'inactive' => Organization::where('status', 'inactive')->count(),
            'members'  => OrganizationMember::count(),
        ];

        return view('organizations.index', compact('organizations', 'summary'));
    }

    /**
     * Display the specific organization's profile and its members.
     */
    public function show(Organization $organization)
    {
        $members = $organization->members();

        // Filter members by role (President, Member, etc.)
        if (request('role')) {
            $members->where('role', request('role'));
        }

        // Search members by name or ID
        if (request('search')) {
            $members->where(function($q) {
                $q->where('student_name', 'like', '%' . request('search') . '%')
                  ->orWhere('student_id', 'like', '%' . request('search') . '%');
            });
        }

        $members = $members->paginate(10)->withQueryString();

        return view('organizations.show', compact('organization', 'members'));
    }

    /**
     * Store a newly created organization.
     */
    public function store(StoreOrganizationRequest $request)
    {
        Organization::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    /**
     * Update the specified organization.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $organization->update($request->validated());

        return redirect()->route('organizations.index')
            ->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified organization.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect()->route('organizations.index')
            ->with('success', 'Organization deleted.');
    }

    /**
     * Member Management: Add a member to a specific organization.
     */
    public function addMember(Organization $organization)
    {
        $data = request()->validate([
            'student_name' => 'required|string|max:255',
            'student_id'   => 'required|string|max:50',
            'role'         => 'required|in:president,vice_president,secretary,treasurer,member',
            'course'       => 'nullable|string|max:255',
            'year_level'   => 'nullable|integer|between:1,4',
        ]);

        $organization->members()->create($data);

        return redirect()->route('organizations.show', $organization)
            ->with('success', 'Member added successfully.');
    }

    /**
     * Member Management: Remove a member from an organization.
     */
    public function removeMember(Organization $organization, OrganizationMember $member)
    {
        $member->delete();

        return redirect()->route('organizations.show', $organization)
            ->with('success', 'Member removed.');
    }

    // Modal-driven edit views typically don't use these methods
    public function create() { }
    public function edit(Organization $organization) { }
}