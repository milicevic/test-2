@extends('adminlte::page')

@section('title', 'Assign Users to ' . ($permission->label ?? $permission->name))

@section('content_header')
    <h1>Assign Users: **{{ $permission->label ?? $permission->name }}**</h1>
@stop

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Select Users to Attach</h3>
        </div>

        <form action="{{ route('permissions.save.assign', $permission) }}" method="POST">
            @csrf
            <div class="card-body p-0">
                <table class="table table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Assign</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Current Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                @php
                                    $isAssigned = $permission->users->contains($user->id);
                                @endphp

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="users[]"
                                           value="{{ $user->id }}" id="user_{{ $user->id }}"
                                           {{ $isAssigned ? 'checked' : '' }}>
                                    <label class="form-check-label" for="user_{{ $user->id }}"></label>
                                </div>
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($isAssigned)
                                    <span class="badge badge-success">Assigned</span>
                                @else
                                    <span class="badge badge-secondary">Not Assigned</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-warning">No users found to assign.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                @if($users->currentPage() > 1)
                    @foreach($users->previousPageUrl() as $previousUser)
                        @if($permission->users->contains($previousUser->id))
                            <input type="hidden" name="users[]" value="{{ $previousUser->id }}">
                        @endif
                    @endforeach
                @endif

            </div>

            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success"><i class="fas fa-save mr-2"></i> Save Assignments</button>
                <a href="{{ route('permissions.index') }}" class="btn btn-default ml-2">Cancel</a>
            </div>
        </form>
    </div>
@stop
