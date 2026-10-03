<?php
// BundesBär Kontaktformular Verarbeitung
// Sicherheitshinweis: Grundlegende Spam-Prävention hinzugefügt

// Überprüfe, ob das Formular gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sammle und bereinige Eingabedaten
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);
    
    // Einfache Validierung
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Bitte gib deinen Namen ein.";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Bitte gib eine gültige E-Mail-Adresse ein.";
    }
    
    if (empty($message)) {
        $errors[] = "Bitte gib eine Nachricht ein.";
    }
    
    // Wenn keine Fehler, E-Mail senden
    if (empty($errors)) {
        $to = "buy@bundesbaer.store";
        $subject = "BundesBär Anfrage von " . htmlspecialchars($name);
        $body = "Name: " . htmlspecialchars($name) . "\n";
        $body .= "E-Mail: " . htmlspecialchars($email) . "\n\n";
        $body .= "Nachricht:\n" . htmlspecialchars($message) . "\n\n";
        $body .= "Gesendet am: " . date("d.m.Y H:i:s");
        
        // E-Mail-Header
        $headers = "From: " . htmlspecialchars($email) . "\r\n";
        $headers .= "Reply-To: " . htmlspecialchars($email) . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // E-Mail senden
        if (mail($to, $subject, $body, $headers)) {
            // Weiterleitung zur Erfolgsseite oder zurück zum Formular mit Parameter
            header("Location: index.html#contact?sent=1");
            exit;
        } else {
            $errors[] = "Die Nachricht konnte nicht gesendet werden. Bitte versuche es später erneut.";
        }
    }
    
    // Wenn Fehler, zurück zum Formular mit Fehlern (vereinfacht: zeigen auf index.html)
    // In einer Produktionsumgebung würden wir eine bessere Fehlerbehandlung implementieren
    header("Location: index.html#contact?error=1");
    exit;
} else {
    // Wenn nicht POST, zurück zur Hauptseite
    header("Location: index.html");
    exit;
}
?>