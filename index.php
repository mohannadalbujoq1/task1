<!DOCTYPE html>
<html>
<head>
    <!-- Page Title -->
    <title>My First Task</title>

    <!-- Link to the Stylesheet -->
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <!-- Navbar Section -->
    <div class="navbar">
        <!-- Page Title Display -->
        <span class="title">My First Task</span>

        <!-- Including Search Component -->
        <?php include 'php_components/search.php'; ?>
    </div>

    <!-- Including Pagination Component -->
    <?php include 'php_components/pagination.php'; ?>
</body>
</html>