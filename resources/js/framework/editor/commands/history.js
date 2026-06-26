export default {

    undo: {

        icon: 'undo-2',

        title: 'Undo',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).undo().run();
        },

        active() {
            return false;
        },

    },

    redo: {

        icon: 'redo-2',

        title: 'Redo',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).redo().run();
        },

        active() {
            return false;
        },

    },

};
