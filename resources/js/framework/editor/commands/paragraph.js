export default {

    paragraph: {

        icon: 'pilcrow',

        title: 'Paragraph',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).setParagraph().run();
        },

        active(editor) {
            return editor.isActive('paragraph');
        },

    },

    h1: {

        icon: 'heading-1',

        title: 'Heading 1',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleHeading({
                level: 1,
            }).run();
        },

        active(editor) {
            return editor.isActive('heading', {
                level: 1,
            });
        },

    },

    h2: {

        icon: 'heading-2',

        title: 'Heading 2',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleHeading({
                level: 2,
            }).run();
        },

        active(editor) {
            return editor.isActive('heading', {
                level: 2,
            });
        },

    },

    h3: {

        icon: 'heading-3',

        title: 'Heading 3',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleHeading({
                level: 3,
            }).run();
        },

        active(editor) {
            return editor.isActive('heading', {
                level: 3,
            });
        },

    },

    h4: {

        icon: 'heading-4',

        title: 'Heading 4',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleHeading({
                level: 4,
            }).run();
        },

        active(editor) {
            return editor.isActive('heading', {
                level: 4,
            });
        },

    },

    h5: {

        icon: 'heading-5',

        title: 'Heading 5',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleHeading({
                level: 5,
            }).run();
        },

        active(editor) {
            return editor.isActive('heading', {
                level: 5,
            });
        },

    },

    h6: {

        icon: 'heading-6',

        title: 'Heading 6',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleHeading({
                level: 6,
            }).run();
        },

        active(editor) {
            return editor.isActive('heading', {
                level: 6,
            });
        },

    },

    blockquote: {

        icon: 'quote',

        title: 'Quote',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleBlockquote().run();
        },

        active(editor) {
            return editor.isActive('blockquote');
        },

    },

    horizontalRule: {

        icon: 'minus',

        title: 'Divider',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).setHorizontalRule().run();
        },

        active() {
            return false;
        },

    },

};
