<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Services\RagService;

class RagController extends Controller
{
    public function index()
    {
        return view('rag.index');
    }

    public function ask(Request $request, RagService $ragService)
    {
        $request->validate(['query' => 'required|string']);
        $query = $request->input('query');
        $answer = $ragService->answer($query);
        return view('rag.index', compact('answer', 'query'));
    }
}
