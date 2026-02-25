<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    public function index(): Response
    {
        $user = request()->user();

        return Inertia::render('notifications/index', [
            'notifications' => $user->notifications()->latest()->paginate(20),
        ]);
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $notification = request()->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }
}
