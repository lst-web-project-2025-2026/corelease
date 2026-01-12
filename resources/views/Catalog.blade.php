<!DOCTYPE html>
<html>
<head>
    <title>Catalog</title>
</head>
<body>

<h1>Catalog List</h1>
@foreach ($Catalog as $resource)
    <p>

        <strong>Name:</strong> {{ $resource->name }} <br>
        <strong>Category:</strong> {{ $resource->category }}
         <strong>specs:</strong> {{ $resource->specs }} <br>
        <strong>status:</strong> {{ $resource->status }} <br>
        <strong>created_at:</strong> {{ $resource->created_at }}
        <strong>updated_at:</strong> {{ $resource->updated_at }}

    </p>
    <hr>
@endforeach

</body>
</html>
