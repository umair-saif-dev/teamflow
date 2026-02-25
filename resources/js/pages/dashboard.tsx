import AppLayout from '@/layouts/app-layout';
import { Head, Link } from '@inertiajs/react';

type Analytics = {
    totalProjects: number;
    totalTasks: number;
    tasksByStatus: Record<string, number>;
    projectProgress: Array<{ id: number; name: string; progress: number }>;
};

export default function Dashboard({ analytics }: { analytics: Analytics }) {
    return (
        <AppLayout breadcrumbs={[{ title: 'Dashboard', href: '/dashboard' }]}>
            <Head title="Dashboard" />
            <div className="space-y-6 p-4">
                <div className="grid gap-4 md:grid-cols-4">
                    <div className="rounded-lg border p-4">
                        <p className="text-sm text-muted-foreground">Projects</p>
                        <p className="text-2xl font-semibold">{analytics.totalProjects}</p>
                    </div>
                    <div className="rounded-lg border p-4">
                        <p className="text-sm text-muted-foreground">Tasks</p>
                        <p className="text-2xl font-semibold">{analytics.totalTasks}</p>
                    </div>
                    {Object.entries(analytics.tasksByStatus).map(([status, total]) => (
                        <div key={status} className="rounded-lg border p-4">
                            <p className="text-sm capitalize text-muted-foreground">{status.replace('_', ' ')}</p>
                            <p className="text-2xl font-semibold">{total}</p>
                        </div>
                    ))}
                </div>

                <div className="rounded-lg border p-4">
                    <h2 className="mb-4 text-lg font-semibold">Project Progress</h2>
                    <div className="space-y-3">
                        {analytics.projectProgress.map((project) => (
                            <div key={project.id}>
                                <div className="mb-1 flex items-center justify-between text-sm">
                                    <Link href={`/projects/${project.id}`} className="font-medium hover:underline">
                                        {project.name}
                                    </Link>
                                    <span>{project.progress}%</span>
                                </div>
                                <div className="h-2 rounded bg-muted">
                                    <div className="h-full rounded bg-primary" style={{ width: `${project.progress}%` }} />
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
