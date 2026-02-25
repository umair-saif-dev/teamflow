import AppLayout from '@/layouts/app-layout';
import { Head, Link } from '@inertiajs/react';

type Project = {
    id: number;
    name: string;
    description: string | null;
    members: Array<{ id: number; name: string }>;
    tasks: Array<{ id: number; title: string; status: string }>;
    docs: Array<{ id: number; title: string }>;
};

export default function ProjectShow({ project }: { project: { data: Project } }) {
    return (
        <AppLayout breadcrumbs={[{ title: 'Projects', href: '/projects' }, { title: project.data.name, href: `/projects/${project.data.id}` }]}>
            <Head title={project.data.name} />
            <div className="space-y-6 p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">{project.data.name}</h1>
                        <p className="text-muted-foreground">{project.data.description ?? 'No description provided.'}</p>
                    </div>
                    <Link href={`/projects/${project.data.id}/edit`} className="rounded-md border px-3 py-2">
                        Edit
                    </Link>
                </div>

                <section>
                    <h2 className="mb-2 text-lg font-medium">Members</h2>
                    <div className="flex flex-wrap gap-2">
                        {project.data.members.map((member) => (
                            <span key={member.id} className="rounded-full bg-accent px-3 py-1 text-sm">
                                {member.name}
                            </span>
                        ))}
                    </div>
                </section>

                <section>
                    <h2 className="mb-2 text-lg font-medium">Tasks</h2>
                    <ul className="space-y-1 text-sm text-muted-foreground">
                        {project.data.tasks.map((task) => (
                            <li key={task.id}>
                                {task.title} · {task.status}
                            </li>
                        ))}
                    </ul>
                </section>

                <section>
                    <h2 className="mb-2 text-lg font-medium">Docs</h2>
                    <ul className="space-y-1 text-sm text-muted-foreground">
                        {project.data.docs.map((doc) => (
                            <li key={doc.id}>{doc.title}</li>
                        ))}
                    </ul>
                </section>
            </div>
        </AppLayout>
    );
}
