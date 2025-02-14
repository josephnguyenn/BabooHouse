<?php
require '../vendor/autoload.php'; // Load Google API Client Library
require '../config/database.php'; // Include database connection

class GoogleDriveService {
    private $client;
    private $service;
    private $main_folder_id = "1yYhDtG3BxelEnr7wPDQDc2pRvPjtTkNH"; // Main Google Drive Folder ID
    private $conn; // Database connection

    public function __construct($conn) {
        putenv('GOOGLE_APPLICATION_CREDENTIALS=../config/credentials.json');

        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        try {
            $this->client = new Google_Client();
            $this->client->useApplicationDefaultCredentials();
            $this->client->setScopes([Google_Service_Drive::DRIVE_FILE]);

            $this->service = new Google_Service_Drive($this->client);
            $this->conn = $conn; // Store database connection
        } catch (Exception $e) {
            die("❌ Google Drive API Initialization Failed: " . $e->getMessage());
        }
    }

    /**
     * Uploads a file to Google Drive and returns the file link.
     * @param array $file - The uploaded file from $_FILES
     * @param int $building_id - ID of the building
     * @param string|null $room_name - Optional room name for room images
     * @return string|false - Returns Google Drive file URL or false on failure
     */
    public function uploadFileAndSave($file, $building_id, $room_name = null) {
        if (!isset($file["tmp_name"]) || empty($file["tmp_name"]) || !file_exists($file["tmp_name"])) {
            die("❌ No file selected or file does not exist.");
        }

        $file_tmp = $file["tmp_name"];
        $file_ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        $file_mime = mime_content_type($file_tmp);

        // ✅ Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file_mime, $allowed_types)) {
            die("❌ Invalid file type: Only JPG and PNG are allowed.");
        }

        // Fetch building details
        $sql = "SELECT name FROM buildings WHERE building_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $building_id);
        $stmt->execute();
        $stmt->bind_result($building_name);
        $stmt->fetch();
        $stmt->close();

        if (!$building_name) {
            die("❌ Building not found.");
        }

        // Convert Vietnamese name to a safe format
        $building_name_sanitized = $this->sanitizeFileName($building_name);
        $room_name_sanitized = $room_name ? $this->sanitizeFileName($room_name) : '';

        // Generate folder structure
        $building_folder_id = $this->getOrCreateFolder($building_name_sanitized, $this->main_folder_id);
        $parent_folder_id = $building_folder_id;

        if ($room_name) {
            $parent_folder_id = $this->getOrCreateFolder($room_name_sanitized, $building_folder_id);
        }

        // Generate unique file name
        $file_name = $room_name ? "$room_name_sanitized.$file_ext" : "$building_name_sanitized.$file_ext";

        // Check if Google Drive service is available
        if (!$this->service) {
            die("❌ Google Drive service is not initialized.");
        }

        try {
            // Verify file upload
            if (!is_uploaded_file($file_tmp)) {
                die("❌ File was not uploaded properly.");
            }

            $file_metadata = new Google_Service_Drive_DriveFile([
                'name' => $file_name,
                'parents' => [$parent_folder_id]
            ]);

            $content = file_get_contents($file_tmp);
            $uploadedFile = $this->service->files->create($file_metadata, [
                'data' => $content,
                'mimeType' => $file_mime,
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            if (!$uploadedFile) {
                die("❌ File upload failed.");
            }

            $file_id = $uploadedFile->id;
            if (!$file_id) {
                die("❌ Failed to retrieve uploaded file ID.");
            }

            // Set file to public
            $this->service->permissions->create($file_id, new Google_Service_Drive_Permission([
                'type' => 'anyone',
                'role' => 'reader'
            ]));

            $file_url = "https://drive.google.com/uc?id=" . $file_id; // Public file URL

            // Save file URL to the database
            $update_sql = "UPDATE buildings SET photo_urls = ? WHERE building_id = ?";
            if ($room_name) {
                $update_sql = "UPDATE rooms SET photo_urls = ? WHERE room_name = ?";
            }

            $stmt = $this->conn->prepare($update_sql);
            if ($stmt) {
                if ($room_name) {
                    $stmt->bind_param("ss", $file_url, $room_name);
                } else {
                    $stmt->bind_param("si", $file_url, $building_id);
                }
                $stmt->execute();
                $stmt->close();
            } else {
                die("❌ Database error: " . $this->conn->error);
            }

            return $file_url; // Return the saved URL
        } catch (Exception $e) {
            die("❌ Google Drive Upload Error: " . $e->getMessage());
        }
    }

    /**
     * Creates a folder in Google Drive or retrieves an existing one
     */
    private function getOrCreateFolder($folder_name, $parent_folder_id) {
        // Check if folder already exists
        $query = "name = '$folder_name' and '$parent_folder_id' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed=false";
        $response = $this->service->files->listFiles(['q' => $query, 'fields' => 'files(id)']);

        if (count($response->files) > 0) {
            return $response->files[0]->id; // Return existing folder ID
        }

        // Create a new folder
        $folder_metadata = new Google_Service_Drive_DriveFile([
            'name' => $folder_name,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parent_folder_id]
        ]);

        $folder = $this->service->files->create($folder_metadata, ['fields' => 'id']);
        return $folder->id;
    }

    /**
     * Converts Google Drive file URLs to direct image links.
     */
    public function getDirectGoogleDriveImage($google_drive_link) {
        if (preg_match('/id=([a-zA-Z0-9_-]+)/', $google_drive_link, $matches) || 
            preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $google_drive_link, $matches)) {
            $file_id = $matches[1];
            return "https://lh3.googleusercontent.com/d/$file_id";
        }
        return false; 
    }

    /**
     * Converts Vietnamese characters to a URL-safe format
     */
    private function sanitizeFileName($string) {
        return preg_replace('/[^a-zA-Z0-9-_]/', '_', strtolower($string));
    }
}
?>
