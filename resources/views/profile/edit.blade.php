<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    <input type="text" name="nickname" placeholder="Nickname" value="{{ old('nickname', auth()->user()->nickname) }}">
    <input type="text" name="phone" placeholder="Phone No" value="{{ old('phone', auth()->user()->phone) }}">
    <input type="text" name="city" placeholder="City" value="{{ old('city', auth()->user()->city) }}">
    <input type="file" name="avatar">
    <button type="submit">Update Profile</button>
</form>

<form method="POST" action="{{ route('profile.destroy') }}">
    @csrf
    @method('DELETE')
    <button type="submit">Delete Account</button>
</form>
