<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/initialized_utils.php"); ?>
<?php
$_video = $_video_fetch_utils->fetch_video_rid($_GET['id']);

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['comment']) && isset($_POST['tags']) && isset($_POST['category'])) {
    if(!isset($_SESSION['siteusername'])) { header("Location: /login"); } 
    if($_video['author'] != $_SESSION['siteusername']) { header("Location: /video_manager"); } 
    $title = $_POST['title'];
    $description = $_POST['comment'];
    $tags = $_POST['tags'];
    $category = $_POST['category'];

    echo $title;
    echo $description;

    if(!empty($title)) { 
        $stmt = $conn->prepare("UPDATE videos SET title = ? WHERE `videos`.`rid` = ?;");
        $stmt->bind_param("ss", $title, $_GET['id']);
        $stmt->execute();
        $stmt->close();

        echo "test!";
    }

    if(!empty($description)) { 
        $stmt = $conn->prepare("UPDATE videos SET description = ? WHERE `videos`.`rid` = ?;");
        $stmt->bind_param("ss", $description, $_GET['id']);
        $stmt->execute();
        $stmt->close();

        echo "test1!";
    }

    if(!empty($tags)) { 
        $stmt = $conn->prepare("UPDATE videos SET tags = ? WHERE `videos`.`rid` = ?;");
        $stmt->bind_param("ss", $tags, $_GET['id']);
        $stmt->execute();
        $stmt->close();
        
        echo "test2!";
    }

    if(!empty($category)) { 
        $stmt = $conn->prepare("UPDATE videos SET category = ? WHERE `videos`.`rid` = ?;");
        $stmt->bind_param("ss", $category, $_GET['id']);
        $stmt->execute();
        $stmt->close();

        echo "test3!";
    }

    if(!empty($_FILES["fileToUpload"]["name"])) {
        $target_dir = "/dynamic/thumbs/";
        $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
        $target_name = md5_file($_FILES["fileToUpload"]["tmp_name"]) . "." . $imageFileType;

        $target_file = $target_dir . $target_name;

        $uploadOk = true;
        $movedFile = false;

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $fileerror = 'unsupported file type. must be jpg, png, or jpeg';
            $uploadOk = false;
        }

        if (file_exists($target_file)) {
            $movedFile = true;
        } else {
            $movedFile = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        }

        if ($uploadOk) {
            if ($movedFile) {
                $stmt = $conn->prepare("UPDATE videos SET thumbnail = ? WHERE `videos`.`rid` = ?;");
                $stmt->bind_param("ss", $target_name, $_GET['id']);
                $stmt->execute();
                $stmt->close();
            } else {
                $fileerror = 'fatal error';
            }
        }
    }
} 

die(header("Location: /edit_video?id=".$_video['rid']));
?>