<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your MFA Code</title>
</head>
<body>
    <h1>Your MFA Code is: {{ $code }}</h1>
    
    @if (isset($expires_in))
        <p>This code will expire at: <strong>{{ $expires_in }}</strong></p>
    @else
        <p>Please use this code as soon as possible to ensure successful verification.</p>
    @endif
</body>
</html>
