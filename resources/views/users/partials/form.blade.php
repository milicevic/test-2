{{-- resources/views/admin/users/partials/form.blade.php --}}

<div class="form-group">
    <label for="name">Name</label>
    {{-- Use old() helper to repopulate on validation failure --}}
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
</div>

<div class="form-group">
    <label for="password">Password (Leave blank to keep existing)</label>
    <input type="password" name="password" id="password" class="form-control">
</div>

<div class="form-group">
    <label for="password_confirmation">Confirm Password</label>
    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
</div>

{{-- Add fields for Roles/Permissions management here later --}}
