export default {

    link: {

        icon(editor) {
            return editor.isActive('link') ? 'link-2-off' : 'link';
        },

        title(editor) {
            return editor.isActive('link') ? 'Remove Link' : 'Add Link';
        },

        run(editor) {
            const previousUrl = editor.getAttributes('link').href;
            const url = window.prompt('URL', previousUrl);

            // cancelled
            if (url === null) {
                return;
            }

            // empty
            if (url === '') {
                editor.chain().focus(null, { scrollIntoView: false }).extendMarkRange('link').unsetLink().run();
                return;
            }

            // update link
            editor.chain().focus(null, { scrollIntoView: false }).extendMarkRange('link').setLink({ href: url }).run();
        },

        active(editor) {
            return editor.isActive('link');
        },

    },

};
