<?php

use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Route;

Route::get('/test-firebase', function () {
    try {
        // Initialize Firebase with service account credentials
        $firebase = (new Factory)
            ->withServiceAccount(storage_path('firebase/gestionscolaire-f3c19-firebase-adminsdk-icp8q-4283c4b8f7.json')) // Path to Firebase credentials
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL')); // Firebase Realtime Database URL
        
        // Access Firebase Realtime Database
        $database = $firebase->createDatabase();

        // Test connection by fetching data from a reference
        $reference = $database->getReference('test'); // Replace 'test' with a valid node in your Firebase DB
        $snapshot = $reference->getSnapshot();
        $data = $snapshot->getValue(); // Retrieve data from the database

        return response()->json([
            'message' => 'Firebase connection successful!',
            'data' => $data
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to connect to Firebase: ' . $e->getMessage(),
        ], 500);
    }
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
