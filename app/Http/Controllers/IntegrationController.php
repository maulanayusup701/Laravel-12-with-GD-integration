<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;


class IntegrationController extends Controller
{
    public function googleDrive()
    {
        $path = public_path() . '/' . 'testing.png';
        $filename = 'testing.png';
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
