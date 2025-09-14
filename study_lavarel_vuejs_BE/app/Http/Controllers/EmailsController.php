<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emails;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;

class EmailsController extends Controller
{
    protected $emailService;

    // Laravel tự inject service từ container
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Emails::all();
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'email' => 'required|email:rfc'
            ]);
            $this->emailService->sendEmail(
                $validate['email'],
                'Welcome to Our Service',
                '<h1>Thank you for registering!</h1><p>We are excited to have you on board.</p>',
                public_path('index.php')
            );
            Emails::create([
                'email' => $validate['email'],
                'status' => 'sent'
            ]);
            return response()->json(['message' => 'create successfully'], 201);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json(['message' => 'Validation Error', 'errors' => $ve->errors()], 422);
        } catch (\Throwable $th) {
            try {
                Emails::create([
                    'email' => $validate['email'],
                    'status' => 'failed'
                ]);
            } catch (\Throwable $e) {
                return response()->json(['message' => 'Server Error', 'dummy' => $e->getMessage()], 500);

            }
            return response()->json(['message' => 'Server Error', 'dummy' => $th->getMessage()], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $email = Emails::findOrFail($id);
            return response()->json(['message' => 'success', 'data' => $email], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Not Found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validate = $request->validate([
                'email' => 'required|email:rfc'
            ]);
            $email = Emails::findOrFail($id);
            $email->email = $validate['email'];
            $email->save();
            return response()->json(['message' => 'update successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Server Error'], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $email = Emails::findOrFail($id);
            $email->delete();
            return response()->json(['message' => 'delete successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Not Found'], 404);
        }
    }

    public function resendEmail(string $id)
    {
        try {
            $emailRecord = Emails::findOrFail($id);
            if ($emailRecord->status === 'sent') {
                return response()->json(['message' => 'Email has already been sent'], 400);
            } elseif ($emailRecord->status === 'pending') {
                return response()->json(['message' => 'Email is still pending, please wait'], 400);
            }
            $this->emailService->sendEmail(
                $emailRecord->email,
                'Resend: Welcome to Our Service',
                '<h1>Thank you for registering!</h1><p>We are excited to have you on board.</p>',
                public_path('index.php')
            );
            $emailRecord->status = 'sent';
            $emailRecord->save();
            return response()->json(['message' => 'Email resent successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Not Found or Server Error'], 404);
        }
    }
}