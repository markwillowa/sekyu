export default {

    bold: {

        icon: 'bold',

        title: 'Bold',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleBold().run();
        },

        active(editor) {
            return editor.isActive('bold');
        },

    },

    italic: {

        icon: 'italic',

        title: 'Italic',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleItalic().run();
        },

        active(editor) {
            return editor.isActive('italic');
        },

    },

    underline: {

        icon: 'underline',

        title: 'Underline',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleUnderline().run();
        },

        active(editor) {
            return editor.isActive('underline');
        },

    },

    strike: {

        icon: 'strikethrough',

        title: 'Strikethrough',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleStrike().run();
        },

        active(editor) {
            return editor.isActive('strike');
        },

    },

    code: {

        icon: 'code',

        title: 'Code',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleCode().run();
        },

        active(editor) {
            return editor.isActive('code');
        },

    },

};
