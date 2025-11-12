{{-- resources/views/admin/users/partials/form.blade.php --}}

<div class="form-group">
    <label for="name">Name</label>
    {{-- Use old() helper to repopulate on validation failure --}}
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $permission->name ?? '') }}" required>
</div>

<div class="form-group">
    <label for="Label">Label</label>
    <input type="label" name="label" id="label" class="form-control" value="{{ old('label', $permission->label ?? '') }}" required>
</div>
