<!-- profile/edit.blade.php -->
<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="nickname">Nickname</label>
        <input type="text" name="nickname" id="nickname" value="{{ auth()->user()->nickname }}" required class="form-control">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" required class="form-control">
    </div>

    <div class="form-group">
        <label for="phone_number">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" value="{{ auth()->user()->phone_number }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="city">City</label>
        <input type="text" name="city" id="city" value="{{ auth()->user()->city }}" class="form-control">
    </div>

    <div class="form-group">
        <label for="avatar">Avatar</label>
        <input type="file" name="avatar" id="avatar" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Update Profile</button>
</form>

<form method="POST" action="{{ route('profile.destroy') }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Delete Account</button>
</form>
