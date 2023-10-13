<!DOCTYPE html>
<html>
<head>
    <title>Generate PDF Table</title>
    <style>
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .header {
            color: red;
            text-align: center; 
            font-size: 24px;
            margin-bottom: 20px; 
        }

      
    </style>
</head>
<body>
    <h1 class="header">Sample Table</h1>
   
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>password</th>
               
            </tr>
        </thead>
       <tbody>
    @foreach ($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ ucwords($user->name) }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->password }}</td>
         
    </tr>
    @endforeach
</tbody>

    </table>
</body>
</html>
