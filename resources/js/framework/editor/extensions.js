import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Placeholder from '@tiptap/extension-placeholder';
import Link from '@tiptap/extension-link';

export default function (placeholder = 'Start writing...') {

    return [

        StarterKit.configure({
            heading: {
                levels: [1, 2, 3, 4, 5, 6],
            },
        }),

        Underline,

        Link.configure({
            openOnClick: false,
            HTMLAttributes: {
                class: 'text-blue-600 underline cursor-pointer',
            },
        }),

        Placeholder.configure({
            placeholder,
            emptyEditorClass: 'sekyu-is-editor-empty',
            emptyNodeClass: 'sekyu-is-empty',
        }),

    ];

}
