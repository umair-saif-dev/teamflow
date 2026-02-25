import InputError from '@/components/input-error';
import AppLayout from '@/layouts/app-layout';
import { Head, useForm } from '@inertiajs/react';

type Member = { id: number; name: string };

export default function CreateProject({ members }: { members: Member[] }) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        description: '',
        member_ids: [] as number[],
    });

    return (
        <AppLayout breadcrumbs={[{ title: 'Projects', href: '/projects' }, { title: 'Create', href: '/projects/create' }]}>
            <Head title="Create Project" />
            <form
                className="max-w-2xl space-y-4 p-4"
                onSubmit={(event) => {
                    event.preventDefault();
                    post('/projects');
                }}
            >
                <h1 className="text-2xl font-semibold">Create Project</h1>
                <div>
                    <label htmlFor="name" className="mb-1 block text-sm font-medium">
                        Name
                    </label>
                    <input id="name" className="w-full rounded-md border px-3 py-2" value={data.name} onChange={(event) => setData('name', event.target.value)} />
                    <InputError message={errors.name} />
                </div>

                <div>
                    <label htmlFor="description" className="mb-1 block text-sm font-medium">
                        Description
                    </label>
                    <textarea id="description" className="w-full rounded-md border px-3 py-2" value={data.description} onChange={(event) => setData('description', event.target.value)} />
                    <InputError message={errors.description} />
                </div>

                <div>
                    <label htmlFor="member_ids" className="mb-1 block text-sm font-medium">
                        Members
                    </label>
                    <select
                        id="member_ids"
                        multiple
                        className="h-36 w-full rounded-md border px-3 py-2"
                        value={data.member_ids.map(String)}
                        onChange={(event) => {
                            const selected = Array.from(event.target.selectedOptions, (option) => Number(option.value));
                            setData('member_ids', selected);
                        }}
                    >
                        {members.map((member) => (
                            <option key={member.id} value={member.id}>
                                {member.name}
                            </option>
                        ))}
                    </select>
                    <InputError message={errors.member_ids} />
                </div>

                <button disabled={processing} className="rounded-md bg-primary px-3 py-2 text-primary-foreground disabled:opacity-50">
                    Save Project
                </button>
            </form>
        </AppLayout>
    );
}
