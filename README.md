# Laravel 12 Google Drive Integration API

[![Laravel](https://img.shields.io/badge/Laravel-12-red)](https://laravel.com/)
[![Google Drive API](https://img.shields.io/badge/Google%20Drive-API-blue)](https://developers.google.com/drive)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Integrasi Laravel 12 dengan Google API Google Drive dan Google Calendar.
Dengan integrasi ini, anda dapat melakukan upload file ke Google Drive dan membuat event di Google Calendar.

## 📦 Library yang Digunakan

-   [yaza/laravel-google-drive-storage](https://github.com/yaza-putu/laravel-google-drive-storage)
-   [google/apiclient](https://github.com/googleapis/google-api-php-client)
-   [spatie/laravel-google-calendar](https://github.com/spatie/laravel-google-calendar)

## ⚙️ Konfigurasi

### 1. Install Library menggunakan Composer

Install library dengan menjalankan perintah berikut di terminal:

```bash
composer i
```

#### 2. Setting File .env:

Tambahkan variabel berikut di file .env:

```bash
FILESYSTEM_CLOUD=google
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REFRESH_TOKEN=your_refresh_token
GOOGLE_ACCESS_TOKEN=your_access_token
GOOGLE_FOLDER=your_folder_id // silahkan tentukan nama folder di google drive
GOOGLE_CALENDER=primary // if not primary, you can use GOOGLE_CALENDER_ID
```

Gantilah your_client_id, your_client_secret, your_refresh_token, your_access_token, dan your_folder_id dengan nilai yang sesuai dari Google API.

#### 3. Setting di config/filesystems.php

Tambahkan konfigurasi untuk driver google di dalam array disks pada file config/filesystems.php:

```bash
'google' => [
    'driver' => 'google',
    'clientId' => env('GOOGLE_CLIENT_ID'),
    'clientSecret' => env('GOOGLE_CLIENT_SECRET'),
    'accessToken' => env('GOOGLE_ACCESS_TOKEN'),
    'refreshToken' => env('GOOGLE_REFRESH_TOKEN'),
    'folder' => env('GOOGLE_FOLDER'),
],
```

#### 4. Setting di config/filesystems.php

Setelah konfigurasi selesai, Anda dapat membuat controller untuk menangani proses upload, menampilkan daftar, dan menghapus file di Google Drive. Berikut adalah contoh implementasi di controller:

```bash
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;

class GDIntegrationController extends Controller
{
    public function index()
    {
        $path = public_path() . '/' . 'filename.png'; // pastikan path file sudah benar
        $filename = 'filename.png';                   // pastikan file ada di public
        Storage::disk('google')->put($filename, File::get($path));

        return response()->json('success');
    }

     public function googleCalendar()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->fetchAccessTokenWithRefreshToken(env('GOOGLE_REFRESH_TOKEN'));

        $service = new Calendar($client);

        // Create an event in the primary calendar
        $event = new Event([
            'summary' => 'Meeting with Team',
            'start' => [
                'dateTime' => '2025-05-13T10:00:00+07:00',
                'timeZone' => 'Asia/Jakarta',
            ],
            'end' => [
                'dateTime' => '2025-05-13T11:00:00+07:00',
                'timeZone' => 'Asia/Jakarta',
            ],
        ]);

        $calendarId = env('GOOGLE_CALENDAR_ID', 'primary');
        $service->events->insert($calendarId, $event);
    }
}

```

#### 5. Tambahkan Route

Untuk mengakses fungsionalitas yang telah dibuat di controller, tambahkan route di routes/web.php:

```bash
Route::controller(IntegrationController::class)->group(function () {
    Route::get('/upload', 'googleDrive');
    Route::get('/event', 'googleCalendar');
});
```

#### 6. Akses dan Uji

Setelah semuanya terkonfigurasi dengan benar, Anda dapat mengakses API yang telah dibuat:

Untuk meng-upload file: http://localhost:8000/upload
Untuk membuat event di Google Calendar: http://localhost:8000/event
