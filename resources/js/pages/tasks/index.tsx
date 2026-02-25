import AppLayout from '@/layouts/app-layout';
import { useTasks } from '@/hooks/use-tasks';
import { Head, router, useForm } from '@inertiajs/react';
import { useCallback, useMemo, useState } from 'react';

type Task = {
    id: number;
    project_id: number;
    title: string;
    description: string | null;
    status: 'todo' | 'in_progress' | 'review' | 'done';
    priority: 'low' | 'medium' | 'high' | 'urgent';
    due_date: string | null;
};

type Project = { id: number; name: string };

const columns: Array<Task['status']> = ['todo', 'in_progress', 'review', 'done'];

export default function TasksIndex({ tasks, projects }: { tasks: { data: Task[] }; projects: Project[] }) {
    const { byStatus } = useTasks(tasks.data);
    const [search, setSearch] = useState('');

    const { data, setData, post, processing, reset } = useForm({
        project_id: projects[0]?.id ?? 0,
        title: '',
        description: '',
        status: 'todo' as Task['status'],
        priority: 'medium' as Task['priority'],
        due_date: '',
    });

    const filtered = useMemo(
        () =>
            Object.fromEntries(
                columns.map((status) => [
                    status,
                    byStatus[status].filter((task) => task.title.toLowerCase().includes(search.toLowerCase())),
                ]),
            ) as Record<Task['status'], Task[]>,
        [byStatus, search],
    );

    const handleDrop = useCallback((taskId: number, status: Task['status']) => {
        router.patch(`/tasks/${taskId}/status`, { status }, { preserveScroll: true });
    }, []);

    return (
        <AppLayout breadcrumbs={[{ title: 'Tasks', href: '/tasks' }]}>
            <Head title="Tasks" />
            <div className="space-y-6 p-4">
                <div className="flex flex-wrap items-center justify-between gap-3">
                    <h1 className="text-2xl font-semibold">Task Board</h1>
                    <input
                        className="rounded-md border px-3 py-2"
                        placeholder="Search tasks"
                        value={search}
                        onChange={(event) => setSearch(event.target.value)}
                    />
                </div>

                <form
                    className="grid gap-3 rounded-lg border p-4 md:grid-cols-6"
                    onSubmit={(event) => {
                        event.preventDefault();
                        post('/tasks', {
                            preserveScroll: true,
                            onSuccess: () => reset('title', 'description', 'due_date'),
                        });
                    }}
                >
                    <select className="rounded-md border px-3 py-2" value={data.project_id} onChange={(event) => setData('project_id', Number(event.target.value))}>
                        {projects.map((project) => (
                            <option key={project.id} value={project.id}>
                                {project.name}
                            </option>
                        ))}
                    </select>
                    <input className="rounded-md border px-3 py-2 md:col-span-2" placeholder="Task title" value={data.title} onChange={(event) => setData('title', event.target.value)} />
                    <select className="rounded-md border px-3 py-2" value={data.priority} onChange={(event) => setData('priority', event.target.value as Task['priority'])}>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                    <input className="rounded-md border px-3 py-2" type="date" value={data.due_date} onChange={(event) => setData('due_date', event.target.value)} />
                    <button disabled={processing} className="rounded-md bg-primary px-3 py-2 text-primary-foreground">
                        Add Task
                    </button>
                </form>

                <div className="grid gap-4 md:grid-cols-4">
                    {columns.map((status) => (
                        <div
                            key={status}
                            className="rounded-lg border p-3"
                            onDragOver={(event) => event.preventDefault()}
                            onDrop={(event) => {
                                event.preventDefault();
                                const taskId = Number(event.dataTransfer.getData('task-id'));
                                handleDrop(taskId, status);
                            }}
                        >
                            <h2 className="mb-3 text-sm font-semibold uppercase tracking-wide text-muted-foreground">{status.replace('_', ' ')}</h2>
                            <div className="space-y-2">
                                {filtered[status].map((task) => (
                                    <div
                                        key={task.id}
                                        draggable
                                        onDragStart={(event) => event.dataTransfer.setData('task-id', String(task.id))}
                                        className="cursor-grab rounded-md border bg-background p-3"
                                    >
                                        <p className="font-medium">{task.title}</p>
                                        <p className="text-xs text-muted-foreground">Priority: {task.priority}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
