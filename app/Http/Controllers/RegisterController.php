<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;

use App\Services\ReaderService;

use App\Http\Resources\ReaderResource;

class RegisterController extends Controller
{
    private $readerService;

    /**
     * Create a new ReaderController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->readerService = new ReaderService;
    }

    public function register(RegisterRequest $request)
    {
        try{
            $reader = $this->readerService->save($request->validated());
            return response()->json([
                'statusCode' => 200,
                'data' => new ReaderResource($reader)
            ], 200);
        }catch (\Throwable $th) {
            \Log::stack(['project'])->info($th->getMessage().' in '.$th->getFile().' at Line '.$th->getLine());
            return redirect('register')->with('error', $th->getMessage())->withInput();
        }
    }
}
