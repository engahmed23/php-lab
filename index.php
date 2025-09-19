<?php
session_start();

// -----------------------------
// Part 1: Data Structure & Initialization
// -----------------------------
$products = [
    [
        'id' => 1,
        'name' => 'Laptop',
        'description' => 'High performance laptop',
        'price' => 1200,
        'category' => 'Electronics'
    ],
    [
        'id' => 2,
        'name' => 'Book',
        'description' => 'Programming in PHP',
        'price' => 35,
        'category' => 'Books'
    ]
];

$categories = ['Electronics', 'Books', 'Clothing', 'Food'];

$errors = [];
$submittedData = [];
$successMsg = "";

// Part 2: Form Handling & Validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedData = [
        'name' => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'price' => trim($_POST['price'] ?? ''),
        'category' => trim($_POST['category'] ?? '')
    ];

    // Validation
    if ($submittedData['name'] === '') {
        $errors['name'] = "Product name is required.";
    }
    if ($submittedData['description'] === '') {
        $errors['description'] = "Description is required.";
    }
    if ($submittedData['price'] === '' || !is_numeric($submittedData['price']) || $submittedData['price'] <= 0) {
        $errors['price'] = "Price must be a positive number.";
    }
    if ($submittedData['category'] === '' || !in_array($submittedData['category'], $categories)) {
        $errors['category'] = "Please select a valid category.";
    }

    if (empty($errors)) {
        $newId = count($products) + 1;
        $products[] = [
            'id' => $newId,
            'name' => htmlspecialchars($submittedData['name']),
            'description' => htmlspecialchars($submittedData['description']),
            'price' => (float)$submittedData['price'],
            'category' => htmlspecialchars($submittedData['category'])
        ];
        $successMsg = "Product added successfully!";
        $submittedData = []; // reset form
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Product Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Product Inventory</h1>

    <!-- Messages -->
    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success"><?= $successMsg ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">Please fix the errors below.</div>
    <?php endif; ?>

    <!-- Product Table -->
    <h3>Product List</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Description</th><th>Price ($)</th><th>Category</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= $p['name'] ?></td>
                <td><?= $p['description'] ?></td>
                <td><?= number_format((float)$p['price'], 2) ?></td>
                <td><?= $p['category'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add Product Form -->
    <h3 class="mt-4">Add New Product</h3>
    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name"
                   value="<?= htmlspecialchars($submittedData['name'] ?? '') ?>"
                   class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['name'])): ?>
                <div class="invalid-feedback"><?= $errors['name'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description"
                      class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>"><?= htmlspecialchars($submittedData['description'] ?? '') ?></textarea>
            <?php if (isset($errors['description'])): ?>
                <div class="invalid-feedback"><?= $errors['description'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="text" name="price"
                   value="<?= htmlspecialchars($submittedData['price'] ?? '') ?>"
                   class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>">
            <?php if (isset($errors['price'])): ?>
                <div class="invalid-feedback"><?= $errors['price'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category"
                    class="form-select <?= isset($errors['category']) ? 'is-invalid' : '' ?>">
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>"
                        <?= (isset($submittedData['category']) && $submittedData['category'] === $cat) ? 'selected' : '' ?>>
                        <?= $cat ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['category'])): ?>
                <div class="invalid-feedback"><?= $errors['category'] ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
