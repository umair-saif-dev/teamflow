import AppLayout from '@/layouts/app-layout';
import { Head, useForm } from '@inertiajs/react';

type Doc = {
    id: number;
    title: string;
    content: string;
};

export default function DocsShow({ doc }: { doc: Doc }) {
    const { data, setData, put, processing } = useForm({
        title: doc.title,
        content: doc.content,
    });

    return (
        <AppLayout breadcrumbs={[{ title: 'Docs', href: '/docs' }, { title: doc.title, href: `/docs/${doc.id}` }]}>
            <Head title={doc.title} />
            <form
                className="space-y-4 p-4"
                onSubmit={(event) => {
                    event.preventDefault();
                    put(`/docs/${doc.id}`);
                }}
            >
                <input className="w-full rounded-md border px-3 py-2 text-xl font-semibold" value={data.title} onChange={(event) => setData('title', event.target.value)} />
                <textarea className="min-h-[320px] w-full rounded-md border px-3 py-2" value={data.content} onChange={(event) => setData('content', event.target.value)} />
                <button disabled={processing} className="rounded-md bg-primary px-3 py-2 text-primary-foreground">
                    Save Changes
                </button>
            </form>
        </AppLayout>
    );
}
