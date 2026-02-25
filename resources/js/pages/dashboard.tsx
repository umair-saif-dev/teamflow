import AppLayout from '@/layouts/app-layout';
import { Head, Link } from '@inertiajs/react';

type Analytics = {
    totalProjects: number;
    totalTasks: number;
    tasksByStatus: Record<string, number>;
    workloadByUser: Array<{ user: string; total: number }>;
    recentActivity: Array<{ id: number; user: string; action: string; description: string; at: string }>;
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
                    {Object.entries(analytics?.tasksByStatus ?? {}).map(([status, total]) => (
                        <div key={status} className="rounded-lg border p-4">
                            <p className="text-sm capitalize text-muted-foreground">{status.replace('_', ' ')}</p>
                            <p className="text-2xl font-semibold">{total}</p>
                        </div>
                    ))}
                </div>

                <div className="grid gap-4 lg:grid-cols-2">
                    <div className="rounded-lg border p-4">
                        <h2 className="mb-3 text-lg font-semibold">Project Progress</h2>
                        <div className="space-y-3">
                            {(analytics?.projectProgress ?? []).map((project) => (
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

                    <div className="rounded-lg border p-4">
                        <h2 className="mb-3 text-lg font-semibold">Workload Distribution</h2>
                        <div className="space-y-2 text-sm">
                            {(analytics?.workloadByUser ?? []).map((item, idx) => (
                                <div key={`${item.user}-${idx}`} className="flex items-center justify-between">
                                    <span>{item.user}</span>
                                    <span className="font-medium">{item.total} tasks</span>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                <div className="rounded-lg border p-4">
                    <h2 className="mb-3 text-lg font-semibold">Recent Activity</h2>
                    <div className="space-y-2 text-sm">
                        {(analytics?.recentActivity ?? []).map((activity) => (
                            <div key={activity.id} className="rounded-md bg-muted/40 p-2">
                                <p className="font-medium">{activity.user} · {activity.action}</p>
                                <p className="text-muted-foreground">{activity.description}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
