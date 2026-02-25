import { useMemo } from 'react';

export type ProjectSummary = {
    id: number;
    name: string;
    description: string | null;
};

export function useProjects(projects: ProjectSummary[]) {
    return useMemo(
        () => ({
            all: projects,
            total: projects.length,
        }),
        [projects],
    );
}
