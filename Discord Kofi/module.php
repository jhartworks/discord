<?
// Klassendefinition
class DiscordKofi extends IPSModule {
    // Überschreibt die interne IPS_Create($id) Funktion
    public function Create() {
        // Diese Zeile nicht löschen.
        parent::Create();
        
        $this->RegisterPropertyString("DiscordWsUrl","");
        $this->RegisterPropertyString("Url","");
        $this->RegisterPropertyString("Titel","Eine Nachricht von Kofi");
    }

    // Überschreibt die intere IPS_ApplyChanges($id) Funktion
    public function ApplyChanges() {
        // Diese Zeile nicht löschen
        parent::ApplyChanges();
    }
    /**
    * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
    * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
    *
    * DWM_SendMessage($id);
    *
    */
    public function SendMessage($from,$money,$timestamp,$message,$mail) {
   
            // Discord webhook URL
            $webhookUrl = $this->ReadPropertyString("DiscordWsUrl");
            $Url = $this->ReadPropertyString("Url");
            $topic= $this->ReadPropertyString("Titel");
            // Data to send as JSON
            $data = array(
                "username" => $topic,
                "avatar_url" => "https://storage.ko-fi.com/cdn/logomarkLogo.png",
                "content" => "Du hast eine Spende erhalten",
                "embeds" => array(
                    array(
                        "author" => array(
                            "name" => "Kofi",
                            "url" => $Url,
                            "icon_url" => "https://storage.ko-fi.com/cdn/logomarkLogo.png"
                        ),
                        "color" => 15258703,
                        "fields" => array(
                            array(
                                "name" => "Von:",
                                "value" => $from,
                                "inline" => true
                            ),
                            array(
                                "name" => "Betrag:",
                                "value" => $money,
                                "inline" => true
                            ),
                            array(
                                "name" => "Wann:",
                                "value" => $timestamp
                            ),
                            array(
                                "name" => "Nachricht:",
                                "value" => $message
                            ),
                            array(
                                "name" => "Mail:",
                                "value" => $mail
                            )
                        )
                    )
                )
            );
            // Encode the data as JSON
            $jsonData = json_encode($data);

            // Initialize cURL session
            $ch = curl_init($webhookUrl);

            // Set cURL options
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Content-Length: " . strlen($jsonData)
            ));

            // Execute the cURL session and capture the response
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch);
            }

            // Close cURL session
            curl_close($ch);

            // Display the response from the webhook
            ///echo "Webhook Response: " . $response;


    }
}
?>