<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStatusFormRequest;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(15)->withQueryString();
        $unreadCount = ContactMessage::where('status', 'unread')->count();

        return view('admin.messages.index', compact('messages', 'unreadCount'));
    }

    public function updateStatus(AdminStatusFormRequest $request, int $id)
    {
        $message = ContactMessage::findOrFail($id);
        $validated = $request->validated();
        $message->update(['status' => $validated['status']]);

        return back()->with('status', 'Estado del mensaje actualizado.');
    }

    public function destroy(int $id)
    {
        ContactMessage::findOrFail($id)->delete();

        return back()->with('status', 'Mensaje eliminado.');
    }
}
