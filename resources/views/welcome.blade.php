<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App Webpage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Nunito', sans-serif;
        }
        .welcome-box {
            background-color: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            text-align: center;
        }
        .welcome-box h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #007bff;
        }
        .welcome-box p {
            font-size: 1.25rem;
            color: #333;
            margin-bottom: 2rem;
        }
        .welcome-box a {
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="welcome-box">
        <h1>Todo App</h1>
        <p>Where you can sort out your mind on what you wanna do ✨</p>
        @auth
            <a href="{{ route('todos.index') }}" class="btn btn-success">Go to Your To-Do List</a>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Your Profile</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
        @endauth
    </div>
</body>
</html>
