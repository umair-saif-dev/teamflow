import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';

type User = {
    id: number;
    name: string;
    email: string;
};

export default function UsersIndex({ users }: { users: { data: User[] } }) {
    return (
        <AppLayout breadcrumbs={[{ title: 'Users', href: '/users' }]}>
            <Head title="Users" />
            <div className="space-y-4 p-4">
                <h1 className="text-2xl font-semibold">Team Members</h1>
                <div className="rounded-lg border">
                    {users.data.map((user) => (
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
