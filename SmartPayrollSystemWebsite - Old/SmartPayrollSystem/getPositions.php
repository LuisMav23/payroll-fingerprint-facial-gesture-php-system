<?php


include 'db.php';


$sql = "SELECT PositionID, PositionName FROM Position";
$result = $conn->query($sql);

$positions = [];
if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $positions[] = $row;
    }
}


header('Content-Type: application/json');
echo json_encode($positions);


$conn->close();
?>