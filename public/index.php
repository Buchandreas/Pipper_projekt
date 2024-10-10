<?php

require "../.env";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$servername = "localhost:3306";
$dbname = "pipper";
$username = "root";
$password = getenv('PASSWORD');


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["message" => "Connection failed: " . $e->getMessage()]);
    exit();
}

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod == 'GET') {
    // Handle GET request
    $statement = $conn->query("SELECT * FROM tweets ORDER BY created_at DESC");
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} elseif ($requestMethod == 'POST') {
    // Handle POST request
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['tweet_content']) && isset($input['username'])) {
        $tweetContent = $input['tweet_content'];
        $username = $input['username'];
        $avatar = $input['avatar'];
        
        $stmt = $conn->prepare("INSERT INTO tweets (tweet_content, username, avatar) VALUES (:tweetContent, :username, :avatar)");
        $stmt->bindParam(':tweetContent', $tweetContent);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':avatar', $avatar);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Record inserted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to insert record"]);
        }
    } else {
        echo json_encode(["message" => "Invalid input"]);
    }
} else {
    echo json_encode(["message" => "Request method not supported"]);
}
?>