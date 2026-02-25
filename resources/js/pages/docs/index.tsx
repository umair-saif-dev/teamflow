import AppLayout from '@/layouts/app-layout';
import { useDocs } from '@/hooks/use-docs';
import { Head, Link, router, useForm } from '@inertiajs/react';

type Doc = {
    id: number;
    title: string;
    content: string;
    project_id: number;
};

type Project = { id: number; name: string };

export default function DocsIndex({ docs, projects, filters }: { docs: { data: Doc[] }; projects: Project[]; filters?: { search?: string } }) {
    const { all } = useDocs(docs?.data ?? []);

    const { data, setData, post, processing, reset } = useForm({
        project_id: projects?.[0]?.id ?? 0,
        title: '',
        content: '',
    });

    return (
        <AppLayout breadcrumbs={[{ title: 'Docs', href: '/docs' }]}>
            <Head title="Docs" />
            <div className="space-y-6 p-4">
                <div className="flex items-center justify-between gap-2">
                    <h1 className="text-2xl font-semibold">Project Docs</h1>
                    <input
                        className="rounded-md border px-3 py-2"
                        defaultValue={filters?.search ?? ''}
                        placeholder="Search docs"
                        onChange={(event) => router.get('/docs', { search: event.target.value }, { preserveState: true, replace: true })}
                    />
                </div>

                <form
                    className="grid gap-3 rounded-lg border p-4 md:grid-cols-4"
                    onSubmit={(event) => {
                        event.preventDefault();
                        post('/docs', { onSuccess: () => reset() });
                    }}
                >
                    <select className="rounded-md border px-3 py-2" value={data.project_id} onChange={(event) => setData('project_id', Number(event.target.value))}>
                        {(projects ?? []).map((project) => (
                            <option key={project.id} value={project.id}>
                                {project.name}
                            </option>
                        ))}
                    </select>
                    <input className="rounded-md border px-3 py-2" placeholder="Document title" value={data.title} onChange={(event) => setData('title', event.target.value)} />
                    <textarea className="rounded-md border px-3 py-2" placeholder="Document content" value={data.content} onChange={(event) => setData('content', event.target.value)} />
                    <button disabled={processing} className="rounded-md bg-primary px-3 py-2 text-primary-foreground">
                        Add Doc
                    </button>
                </form>

                <div className="grid gap-4 md:grid-cols-2">
                    {all.map((doc) => (
                        <Link key={doc.id} href={`/docs/${doc.id}`} className="rounded-lg border p-4 hover:bg-accent">
                            <h2 className="font-semibold">{doc.title}</h2>
                            <p className="line-clamp-2 text-sm text-muted-foreground">{doc.content}</p>
                        </Link>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
