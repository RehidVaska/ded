<?php
header('Content-Type: application/json');
$uniqueId = isset($_GET['uniqueId']) ? $_GET['uniqueId'] : null;

if (!$uniqueId) {
    echo json_encode(['status' => 'error', 'message' => 'Nedostaje uniqueId.']);
    exit;
}

try {
    $pdo = new PDO('sqlite:dental.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT response FROM messages WHERE unique_id = ?");
    $stmt->execute([$uniqueId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result && !empty($result['response'])) {
        echo json_encode(['status' => 'received', 'response' => $result['response']]);
    } else {
        echo json_encode(['status' => 'waiting']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
