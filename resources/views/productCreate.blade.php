<!DOCTYPE html>
<html>
<head>
    <title>Create Product</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        #addRowBtn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Create Product</h1>

    @if(session('success'))
        <div class="alert alert-success">
            Product Creating Successfully.
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

    <form method="POST" action="{{ route('product.create') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name">Product Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required>{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="unisex">Unisex</option>
                <option value="child">Child</option>
            </select>
        </div>

        <div>
            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="">Select a category</option>
            </select>
        </div>

        <div>
            <label for="subcategory">Subcategory:</label>
            <select name="subcategory" id="subcategory" required>
                <option value="" disabled selected>Select Subcategory</option>
            </select>
        </div>

        <div>
            <label for="images">Images (Many):</label>
            <input type="file" name="images[]" id="images" multiple required>
        </div>

        <input type="hidden" name="product_categories_id" id="product_categories_id" value="">
        <input type="hidden" name="product_subcategories_id" id="product_subcategories_id" value="">

        <table id="detailsTable">
            <thead>
                <tr>
                    <th>Size</th>
                    <th>Stock</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="size_id[]" id="size" required>
                            <option value="">Select a Size</option>
                        </select>
                    </td>
                    <td><input type="number" name="stock[]"></td>
                    <td><input type="number" name="price[]"></td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="size_id[]" id="size_id" value="">

        <button type="button" id="addRowBtn" onclick="addRow()">Add Details</button>

        <button type="submit">Create Product</button>
    </form>

    <script>
        function loadCategories() {
            fetch('/get-categories')
                .then(response => response.json())
                .then(data => {
                    var select = document.getElementById('category');

                    data.forEach(category => {
                        var option = document.createElement('option');
                        option.value = category.id;
                        option.text = category.name;
                        select.add(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
        function loadSizesForRow(row) {
            fetch('/get-sizes')
                .then(response => response.json())
                .then(data => {
                    var select = row.querySelector('select[name="size_id[]"]');
                    select.innerHTML = '<option value="" disabled selected>Select a Size</option>';

                    data.forEach(size => {
                        var option = document.createElement('option');
                        option.value = size.id;
                        option.text = size.name;
                        select.add(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
        function loadSizes() {
            fetch('/get-sizes')
                .then(response => response.json())
                .then(data => {
                    var select = document.getElementById('size');

                    data.forEach(size => {
                        var option = document.createElement('option');
                        option.value = size.id;
                        option.text = size.name;
                        select.add(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }
        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            loadSizes();
        });
        document.getElementById('category').addEventListener('change', function () {
            var categoryId = this.value;
            var subcategorySelect = document.getElementById('subcategory');
            subcategorySelect.innerHTML = '<option value="" disabled selected>Alt Kategori Seç</option>';
            fetch('/get-subcategories/' + categoryId)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    data.forEach(function (subcategory) {
                        var option = document.createElement('option');
                        option.value = subcategory.id;
                        option.text = subcategory.name;
                        subcategorySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching subcategories:', error));

            document.getElementById('product_categories_id').value = categoryId;
        });
        document.getElementById('subcategory').addEventListener('change', function () {
            document.getElementById('product_subcategories_id').value = this.value;
        });
        document.getElementById('size').addEventListener('change', function (){
            document.getElementById('size_id[]').value = this.value;
        });
        function addRow() {
            var table = document.getElementById('detailsTable').getElementsByTagName('tbody')[0];
            var newRow = table.insertRow(table.rows.length);

            // Cell 1 (Size)
            var cell1 = newRow.insertCell(0);
            var sizeSelect = document.createElement('select');
            sizeSelect.name = 'size_id[]';
            sizeSelect.innerHTML = '<option value="" disabled selected>Select a Size</option>';
            cell1.appendChild(sizeSelect);

            // Cell 2 (Stock)
            var cell2 = newRow.insertCell(1);
            var stockInput = document.createElement('input');
            stockInput.type = 'number';
            stockInput.name = 'stock[]';
            cell2.appendChild(stockInput);

            // Cell 3 (Price)
            var cell3 = newRow.insertCell(2);
            var priceInput = document.createElement('input');
            priceInput.type = 'number';
            priceInput.name = 'price[]';
            cell3.appendChild(priceInput);

            loadSizesForRow(newRow);
        }
    </script>
</body>
</html>
