@extends('adminlte::page')

@section('title', 'Permission Management')

@section('content_header')
    <h1>Permission Management</h1>
@stop

@section('content')
    {{-- AdminLTE Card for the Table --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Permissions</h3>
            <div class="card-tools">
                <a href="{{ route('permissions.create') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Add New Permission
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Name</th>
                        <th>Label</th>
                        <th style="width: 250px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->label }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Permission Actions">
                                <a href="{{ route('permissions.assign', $permission) }}" class="btn btn-sm btn-info mr-1">
                                    <i class="fas fa-user-plus"></i> Assign
                                </a>
                                <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-primary mr-1">Edit</a>
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $permissions->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="usersModalLabel">Users with Permission: <span id="modal-permission-name"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modal-user-list">
            <p>Loading user list...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@stop


