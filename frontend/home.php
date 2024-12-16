<?php
include_once "../backend/db.php";  // เชื่อมต่อฐานข้อมูล

if (isset($_POST['logout'])) {
    session_destroy();
    unset($_SESSION['id']);
    header("Location: ../index.php");
    exit;
}

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    // ดึงข้อมูลจากฐานข้อมูลโดยใช้ query
    $sql = $conn->query("SELECT * FROM tb_user WHERE id = '$user_id'");
    $rw = $sql->fetch(PDO::FETCH_ASSOC);

    if ($rw) {
        // ดึงข้อมูลจากฐานข้อมูล
        $folderPath = $rw['folder_path']; // path ของโฟลเดอร์ที่เก็บภาพ
        $known_face_name = $rw['name'];   // ชื่อบุคคล
        
        // ส่งข้อมูลไปยัง JavaScript
        echo "<script>
                var folderPath = '$folderPath';
                var knownFaceName = '$known_face_name';
                console.log('Folder Path:', folderPath);
                console.log('Known Face Name:', knownFaceName);
              </script>";
    } else {
        echo "User not found!";
        exit;
    }
} else {
    echo "You need to log in first.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Comparison</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        #result {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($rw['name']); ?></h1>
    <p>User ID: <?php echo htmlspecialchars($_SESSION['id']); ?></p>
    <p>Folder Path: <?php echo htmlspecialchars($rw['folder_path']); ?></p>
    <h2>Upload Image to Compare Faces</h2>
    <input type="file" id="imageInput" accept="image/*">
    <button onclick="uploadImage()">Upload and Compare</button>
    
    <div id="result">Result will be displayed here...</div>

    <form action="home.php" method="POST">
        <input type="submit" name="logout" value="Logout">
    </form>

    <script>
        function uploadImage() {
            const fileInput = document.getElementById('imageInput');
            const file = fileInput.files[0];
            if (!file) {
                alert('Please select an image!');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);
            formData.append('folder_path', folderPath); // ส่ง folder path 
            formData.append('name', knownFaceName); // ส่งชื่อที่คาดหวัง

            fetch('https://71a9-223-207-239-159.ngrok-free.app/compare-face', {  // หรือ URL ngrok
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const resultDiv = document.getElementById('result');
                if (data.name) {
                    resultDiv.innerHTML = `
                        <p>${data.message}</p>
                        <p>Confidence: ${data.confidence}</p>
                    `;
                } else {
                    resultDiv.innerText = data.message;
                }
            })
            .catch(error => {
                console.error('Error uploading image:', error);
                document.getElementById('result').innerText = 'Error uploading image: ' + error.message;
            });
        }

    </script>
</body>
</html>
