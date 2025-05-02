<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f9;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }
        button:hover {
            background-color: #45a049;
        }
        .expiry-info {
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Two-Factor Authentication</h1>

        <p>Please enter the code sent to your email to verify your identity.</p>

        <form action="{{ route('two-factor.verify') }}" method="POST">
            @csrf
            <label for="code">Authentication Code:</label>
            <input type="text" id="code" name="code" required autofocus>

            <button type="submit">Verify</button>
        </form>

        @if (isset($expires_in))
        <div class="expiry-info">
            <p>Your code is valid until <strong>{{ $expires_in }}</strong></p>
        </div>
        @endif
    </div>

</body>
</html>
