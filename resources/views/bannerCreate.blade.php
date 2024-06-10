<!DOCTYPE html>
<html>
<head>
    <title>Create Banner</title>
</head>
<body>
    <h1>Create Banner</h1>

    @if(session('success'))
        <div class="alert alert-success">
            Banner Creating Successfully.
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('banner.create') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="images">Images (Many):</label>
            <input type="file" name="images[]" id="images" multiple required>
        </div>

        <button type="submit">Create Banner</button>
    </form>
</body>
</html>
