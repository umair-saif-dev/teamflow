import { useMemo } from 'react';

export type TaskItem = {
    id: number;
    title: string;
    status: 'todo' | 'in_progress' | 'review' | 'done';
    priority: string;
    project_id: number;
    assignee?: { id: number; name: string } | null;
};

export function useTasks(tasks: TaskItem[]) {
    return useMemo(() => {
        const byStatus = {
            todo: tasks.filter((task) => task.status === 'todo'),
            in_progress: tasks.filter((task) => task.status === 'in_progress'),
            review: tasks.filter((task) => task.status === 'review'),
            done: tasks.filter((task) => task.status === 'done'),
        };

        return { tasks, byStatus, total: tasks.length };
    }, [tasks]);
}
