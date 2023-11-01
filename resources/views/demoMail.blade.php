<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome Email</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS for email */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="container" >
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
        <div class="header">
            <p>Dear Team,</p>
          
        </div>
         <p>{{ $mailData['body'] }}</p>
       
        <div class="closing">
            <p>Regards,<br>-xyz</p>
        </div>
    </div>
</body>
</html>
