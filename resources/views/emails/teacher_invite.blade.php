<!DOCTYPE html>
<html>
<head>
    <title>Teacher Invitation</title>
</head>
<body>
    <h1>Welcome, {{ $name }}</h1>
    <p>You’ve been invited to join the platform. Click the link below to set your password and activate your account:</p>
    <p>
        <a href="{{ $url }}">{{ $url }}</a>
    </p>
    <p>This link will expire in 24 hours.</p>
</body>
</html>
