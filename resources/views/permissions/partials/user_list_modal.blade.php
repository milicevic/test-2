{{-- resources/views/admin/permissions/partials/user_list_modal.blade.php --}}

<div class="table-responsive">
    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th style="width: 10px">ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">No users currently have this permission.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Add pagination links for navigating large result sets --}}
<div class="d-flex justify-content-center mt-3">
    {{ $users->links('pagination::bootstrap-4') }}
</div>
