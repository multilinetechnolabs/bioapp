<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends BaseController
{
    public function index()
    {
        $unread = ContactMessage::where('is_read', false)->count();
        return view('admin.pages.contact.index', compact('unread'));
    }

    public function markRead(Request $request, $id)
    {
        ContactMessage::findOrFail($id)->update(['is_read' => true]);
        return response()->json(['ok' => true]);
    }

    public function markAllRead()
    {
        ContactMessage::where('is_read', false)->update(['is_read' => true]);
        return redirect()->back()->with('message.success', 'All messages marked as read.');
    }

    public function destroy($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }

    public function datatables()
    {
        $messages = ContactMessage::orderByDesc('created_at')->get()->map(function ($m) {
            return [
                'id'         => $m->id,
                'name'       => e($m->name),
                'email'      => e($m->email),
                'subject'    => e($m->subject ?? '—'),
                'message'    => e($m->message),
                'is_read'    => $m->is_read,
                'email_sent' => $m->email_sent,
                'created_at' => $m->created_at->format('Y-m-d H:i'),
            ];
        });

        return response()->json(['data' => $messages]);
    }
}
