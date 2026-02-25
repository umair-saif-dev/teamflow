import { useProjects } from '@/hooks/use-projects';
import AppLayout from '@/layouts/app-layout';
import { Head, Link } from '@inertiajs/react';

type Project = {
    id: number;
    name: string;
    description: string | null;
};

type PaginatedProjects = {
    data: Project[];
};

export default function ProjectsIndex({ projects }: { projects: PaginatedProjects }) {
    const { all, total } = useProjects(projects.data);

    return (
        <AppLayout breadcrumbs={[{ title: 'Projects', href: '/projects' }]}>
            <Head title="Projects" />
            <div className="space-y-4 p-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-semibold">Projects ({total})</h1>
                    <Link href="/projects/create" className="rounded-md bg-primary px-3 py-2 text-primary-foreground">
                        New Project
                    </Link>
                </div>

                <div className="grid gap-4 md:grid-cols-2">
                    {all.map((project) => (
                        <Link key={project.id} href={`/projects/${project.id}`} className="rounded-lg border p-4 hover:bg-accent">
                            <h2 className="text-lg font-medium">{project.name}</h2>
                            <p className="text-sm text-muted-foreground">{project.description ?? 'No description provided.'}</p>
                        </Link>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
