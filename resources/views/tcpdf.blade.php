<!DOCTYPE html>
<html>
<head>
    <style>
      
        .title {
            background-color: #0074A2;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            font-size: 24px;
            padding: 10px;
        }

       
        .description-header {
            background-color: #F2F2F2;
            font-weight: bold;
        }

       
        .sub-task-header {
            background-color: #D9D9D9;
            font-weight: bold;
        }

        
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="title">Project Report</div>
    <table>
        <tr>
            <th class="description-header">Description</th>
            <td>Project A</td>
        </tr>
        <tr>
            <th class="description-header">Start Date</th>
            <td>2023-01-15</td>
        </tr>
        <tr>
            <th class="description-header">End Date</th>
            <td>2023-05-30</td>
        </tr>
        <tr>
            <th class="sub-task-header">Sub-Tasks</th>
            <td>Task 1</td>
        </tr>
        <tr>
            <th></th>
            <td>Task 2</td>
        </tr>
        <tr>
            <th></th>
            <td>Task 3</td>
        </tr>
        <tr>
            <th class="description-header">Team Members</th>
            <td>Vivek, Nishant, Omkar</td>
        </tr>
        <tr>
            <th class="sub-task-header">Progress</th>
            <td>80%</td>
        </tr>
    </table><br><br><br>
    
      <div  style="color:white; text-align:center;background-color:green"> <strong style="color:yellow">User details</strong></div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
              
            </tr>
        </thead>

   
        {{-- <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ ucwords($user->name) }}</td>
                <td>{{ $user->email }}</td>
                
            </tr>
            @endforeach
        </tbody> --}}
    </table>
</body>
</html>
