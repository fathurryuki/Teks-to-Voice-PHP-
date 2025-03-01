<?php
// ElevenLabs Text-to-Speech (TTS) Script

$apiKey = "YOUR_ELEVENLABS_API_KEY"; // Ganti dengan API key Anda
$voiceId = "21m00Tcm4TlvDq8ikWAM"; // ID suara default dari ElevenLabs
$apiUrl = "https://api.elevenlabs.io/v1/text-to-speech/$voiceId";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
    $text = $_POST['text'];
    
    $data = json_encode([
        "text" => $text,
        "voice_settings" => [
            "stability" => 0.5,
            "similarity_boost" => 0.5
        ]
    ]);
    
    $options = [
        "http" => [
            "header" => "Content-Type: application/json\r\n" .
                        "xi-api-key: $apiKey\r\n",
            "method" => "POST",
            "content" => $data
        ]
    ];
    
    $context = stream_context_create($options);
    $response = file_get_contents($apiUrl, false, $context);
    
    if ($response) {
        $filePath = "output.mp3";
        file_put_contents($filePath, $response);
        echo json_encode(["audio" => $filePath]);
    } else {
        echo json_encode(["error" => "Failed to generate speech"]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text to Speech</title>
</head>
<body>
    <h2>Konversi Teks ke Suara</h2>
    <form method="POST">
        <textarea name="text" placeholder="Masukkan teks di sini" required></textarea>
        <button type="submit">Konversi</button>
    </form>
    <?php if (!empty($filePath)): ?>
        <p><audio controls><source src="<?php echo $filePath; ?>" type="audio/mpeg"></audio></p>
    <?php endif; ?>
</body>
</html>
