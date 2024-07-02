<?php
// Include config file (for database connection)
require_once "config.php";

// Initialize variables
$title = $description = $image = "";
$title_err = $description_err = $image_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter the title.";
    } else {
        $title = trim($_POST["title"]);
    }
    
    // Validate description
    if (empty(trim($_POST["description"]))) {
        $description_err = "Please enter a description.";
    } else {
        $description = trim($_POST["description"]);
    }

    // Validate image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $allowed = array("jpg", "jpeg", "png");
        $filename = $_FILES["image"]["name"];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);

        if (!in_array($filetype, $allowed)) {
            $image_err = "Only JPG, JPEG, and PNG files are allowed.";
        } else {
            $image = "uploads/" . basename($filename);
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image)) {
                $image_err = "Failed to upload image.";
            }
        }
    } else {
        $image_err = "Please upload an image.";
    }

    // Check input errors before inserting into database
    if (empty($title_err) && empty($description_err) && empty($image_err)) {
        $sql = "INSERT INTO portfolio (title, description, image) VALUES (?, ?, ?)";
        
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sss", $param_title, $param_description, $param_image);
            
            $param_title = $title;
            $param_description = $description;
            $param_image = $image;

            if ($stmt->execute()) {
                echo "Portfolio item added successfully.";
            } else {
                echo "Something went wrong. Please try again.";
            }
            
            $stmt->close();
        }
    }
}

// Close database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <form action="admin.php" method="post" enctype="multipart/form-data">
        <div>
            <label>Title</label>
            <input type="text" name="title" value="<?php echo $title; ?>">
            <span><?php echo $title_err; ?></span>
        </div>
        <div>
            <label>Description</label>
            <textarea name="description"><?php echo $description; ?></textarea>
            <span><?php echo $description_err; ?></span>
        </div>
        <div>
            <label>Image</label>
            <input type="file" name="image">
            <span><?php echo $image_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Add Item">
        </div>
    </form>
</body>
</html>
