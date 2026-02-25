import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';

type User = {
    id: number;
    name: string;
    email: string;
};

type ProfileSummary = {
    projects: number;
    assignedTasks: number;
    activities: number;
};

export default function UsersIndex({ users, profileSummary }: { users: { data: User[] }; profileSummary: ProfileSummary }) {
    return (
        <AppLayout breadcrumbs={[{ title: 'Users', href: '/users' }]}> 
            <Head title="Users" />
            <div className="space-y-4 p-4">
                <h1 className="text-2xl font-semibold">Team Members</h1>

                <div className="grid gap-3 md:grid-cols-3">
                    <div className="rounded-lg border p-3 text-sm">Owned Projects: <span className="font-semibold">{profileSummary?.projects ?? 0}</span></div>
                    <div className="rounded-lg border p-3 text-sm">Assigned Tasks: <span className="font-semibold">{profileSummary?.assignedTasks ?? 0}</span></div>
                    <div className="rounded-lg border p-3 text-sm">Activity Logs: <span className="font-semibold">{profileSummary?.activities ?? 0}</span></div>
                </div>

                <div className="rounded-lg border">
                    {(users?.data ?? []).map((user) => (
                        <div key={user.id} className="flex items-center justify-between border-b p-4 last:border-b-0">
                            <div>
                                <p className="font-medium">{user.name}</p>
                                <p className="text-sm text-muted-foreground">{user.email}</p>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
