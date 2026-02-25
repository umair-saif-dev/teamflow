import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';

type NotificationItem = {
    id: string;
    data: {
        task_title?: string;
        event?: string;
    };
    read_at: string | null;
    created_at: string;
};

export default function NotificationsIndex({ notifications }: { notifications: { data: NotificationItem[] } }) {
    return (
        <AppLayout breadcrumbs={[{ title: 'Notifications', href: '/notifications' }]}> 
            <Head title="Notifications" />
            <div className="space-y-4 p-4">
                <h1 className="text-2xl font-semibold">Notifications</h1>
                <div className="space-y-2">
                    {(notifications?.data ?? []).map((notification) => (
                        <div key={notification.id} className="rounded-lg border p-3">
                            <p className="font-medium">{notification.data.task_title ?? 'Task update'}</p>
                            <p className="text-sm text-muted-foreground">Event: {notification.data.event ?? 'updated'}</p>
                            <div className="mt-2 flex justify-end">
                                {notification.read_at ? (
                                    <span className="text-xs text-muted-foreground">Read</span>
                                ) : (
                                    <button
                                        className="rounded-md border px-2 py-1 text-xs"
                                        onClick={() => router.patch(`/notifications/${notification.id}/read`) }
                                    >
                                        Mark as read
                                    </button>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
