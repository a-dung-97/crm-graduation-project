<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Note;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page');
        $search = $request->query('search');
        $type = $request->query('type');
        $user = $request->query('user');
        $query =  Note::latest();
        $startDate = $request->query('start');
        $endDate = $request->query('end');
        if ($user) $query = $query->where('user_id', $user);
        if ($type) $query = $query->where('noteable_type', $type);
        if ($startDate) $query = $query->whereDate('created_at', '>=', Carbon::parse($startDate)->toDateString());
        if ($endDate) $query = $query->whereDate('created_at', '<=', Carbon::parse($endDate)->toDateString());
        if ($search) $query = $query->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('content', 'like', '%' . $search . '%');
        });

        $query = $perPage ? $query->paginate($perPage) : $query->get();
        return NoteResource::collection($query);
    }
    public function update(NoteRequest $request, Note $note)
    {
        $note->update($request->all());
        return response(['message' => 'updated'], Response::HTTP_ACCEPTED);
    }
    public function destroy(Note $note)
    {
        $note->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
