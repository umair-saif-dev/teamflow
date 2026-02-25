import { useMemo } from 'react';

export type DocItem = {
    id: number;
    project_id: number;
    title: string;
    content: string;
};

export function useDocs(docs: DocItem[]) {
    return useMemo(
        () => ({
            all: docs,
            total: docs.length,
        }),
        [docs],
    );
}
