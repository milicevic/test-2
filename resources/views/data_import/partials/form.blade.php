@foreach($setup->files as $file)
<div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
</div>
@endforeach

