<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applications</title>
</head>
<body>
    <h1>Apply for access</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    
    @if ($errors->any())
        <div style="color: red;">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="content">
         <form action="{{ route('applications.store') }}" method="POST">
            @csrf 
           
            <label>Full Name:</label><br>
            <input type="text" name="name" value="{{ old('name') }}"><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email') }}"><br>

            <label>Password:</label><br>
            <input type="password" name="password"><br>

            <label>Profession:</label><br>
            <input type="text" name="profession" value="{{ old('profession') }}"><br>

            <label>Reason for access:</label><br>
            <textarea name="reason" rows="4" cols="50">{{ old('reason') }}</textarea><br>

            <button type="submit">Submit Application</button>
         </form>
    </div>
</body>
</html>