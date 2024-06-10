<!DOCTYPE html>
<html >
<head>
    <title>Admin Panel</title>
    <style>
        .subcategory-container {
            margin-top: 10px;
        }

        .remove-subcategory {
            cursor: pointer;
            color: red;
            margin-left: 10px;
        }
        .productSizes-container {
            margin-top: 10px;
        }
        .remove-size {
            cursor: pointer;
            color: red;
            margin-left: 10px;
        }

        .snackbar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Create Category</h1>

    <div class="snackbar" id="snackbar"></div>
    <form id="createCategoryForm" method="POST" action="{{ route('category.create') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name">Category Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="image">Image:</label>
            <input type="file" name="image" id="image" required>
        </div>

        <div id="subcategories-container" class="subcategory-container">
            <label for="subcategories">Subcategories:</label>
            <div>
                <input type="text" name="subcategories[]" required>
                <span class="remove-subcategory" onclick="removeSubcategory(this)">-</span>
            </div>
        </div>

        <button type="button" onclick="addSubcategory()">Add Subcategory</button>
        <button type="button" onclick="createCategory()">Create Category</button>
    </form>

    <script>
        function addSubcategory() {
            var container = document.getElementById('subcategories-container');
            var input = document.createElement('input');
            input.type = 'text';
            input.name = 'subcategories[]';
            input.required = true;

            var removeBtn = document.createElement('span');
            removeBtn.className = 'remove-subcategory';
            removeBtn.innerHTML = '-';
            removeBtn.onclick = function() { removeSubcategory(this); };

            var div = document.createElement('div');
            div.appendChild(input);
            div.appendChild(removeBtn);

            container.appendChild(div);
        }

        function removeSubcategory(btn) {
            var container = document.getElementById('subcategories-container');
            container.removeChild(btn.parentNode);
        }

        function createCategory() {
            var form = document.getElementById('createCategoryForm');
            var formData = new FormData(form);

            fetch(form.action, {
                method: form.method,
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                showSnackbar(data.success ? data.message : data.error);
            })
            .catch(error => {
                showSnackbar('An error occurred while creating size: ' + error.message);
            });
        }

        function showSnackbar(message) {
            var snackbar = document.getElementById('snackbar');
            snackbar.innerHTML = message;
            snackbar.style.display = 'block';
            setTimeout(function() {
                snackbar.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
<body>
    <h1>Create new Product Size</h1>

    <div class="snackbar" id="snackbar"></div>

    <form id="createSizeForm" method="POST" action="{{ route('size.create') }}" enctype="multipart/form-data">
        @csrf
        <div id="productSizes-container" class="productSizes-container">
            <label for="productSizes">Sizes:</label>
            <div>
                <input type="text" name="name[]" required>
                <span class="remove-size" onclick="removeSize(this)">-</span>
            </div>
        </div>

        <button type="button" onclick="addSize()">Add Size</button>
        <button type="button" onclick="createSizes()">Create Sizes</button>
    </form>

    <script>
        function addSize() {
            var container = document.getElementById('productSizes-container');
            var input = document.createElement('input');
            input.type = 'text';
            input.name = 'name[]';
            input.required = true;

            var removeBtn = document.createElement('span');
            removeBtn.className = 'remove-size';
            removeBtn.innerHTML = '-';
            removeBtn.onclick = function() { removeSize(this); };

            var div = document.createElement('div');
            div.appendChild(input);
            div.appendChild(removeBtn);

            container.appendChild(div);
        }

        function removeSize(btn) {
            var container = document.getElementById('productSizes-container');
            container.removeChild(btn.parentNode);
        }

        function createSizes() {
            var form = document.getElementById('createSizeForm');
            var formData = new FormData(form);

            fetch(form.action, {
                method: form.method,
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                showSnackbar(data.success ? data.message : data.error);
            })
            .catch(error => {
                showSnackbar('An error occurred while creating size: ' + error.message);
            });
        }

        function showSnackbar(message) {
            var snackbar = document.getElementById('snackbar');
            snackbar.innerHTML = message;
            snackbar.style.display = 'block';
            setTimeout(function() {
                snackbar.style.display = 'none';
            }, 3000);
        }
    </script>
</body>

</html>
