import InputError from '@/components/input-error';
import AppLayout from '@/layouts/app-layout';
import { Head, useForm } from '@inertiajs/react';

type Project = {
    id: number;
    name: string;
    description: string | null;
};

export default function EditProject({ project }: { project: Project }) {
    const { data, setData, put, processing, errors } = useForm({
        name: project.name,
        description: project.description ?? '',
    });

    return (
        <AppLayout breadcrumbs={[{ title: 'Projects', href: '/projects' }, { title: project.name, href: `/projects/${project.id}` }, { title: 'Edit', href: `/projects/${project.id}/edit` }]}>
            <Head title={`Edit ${project.name}`} />
            <form
                className="max-w-2xl space-y-4 p-4"
                onSubmit={(event) => {
                    event.preventDefault();
                    put(`/projects/${project.id}`);
                }}
            >
                <h1 className="text-2xl font-semibold">Edit Project</h1>
                <div>
                    <label htmlFor="name" className="mb-1 block text-sm font-medium">
                        Name
                    </label>
                    <input
                        id="name"
                        className="w-full rounded-md border px-3 py-2"
                        value={data.name}
                        onChange={(event) => setData('name', event.target.value)}
                    />
                    <InputError message={errors.name} />
                </div>

                <div>
                    <label htmlFor="description" className="mb-1 block text-sm font-medium">
                        Description
                    </label>
                    <textarea
                        id="description"
                        className="w-full rounded-md border px-3 py-2"
                        value={data.description}
                        onChange={(event) => setData('description', event.target.value)}
                    />
                    <InputError message={errors.description} />
                </div>

                <button disabled={processing} className="rounded-md bg-primary px-3 py-2 text-primary-foreground disabled:opacity-50">
                    Update Project
                </button>
            </form>
        </AppLayout>
    );
}
