<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>
    
    <h1>User Dashboard</h1>


    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
