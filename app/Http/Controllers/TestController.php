<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $databaseConnection = env('DB_CONNECTION');
        $databaseName = env('DB_DATABASE');
        $databaseHost = env('DB_HOST');
        $databasePassword = env('DB_PASSWORD');
        $databasePort = env('DB_PORT');
        $databaseUsername = env('DB_USERNAME');

        $result = [
            'connection' => $databaseConnection,
            'database' => $databaseName,
            'host' => $databaseHost,
            'username' => $databaseUsername,
            'password' => $databasePassword,
            'port' => $databasePort,
        ];

        try {
            DB::connection()->getPdo();

            return response()->json([
                "success" => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
