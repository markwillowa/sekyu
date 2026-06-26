export default {

    bulletList: {

        icon: 'list',

        title: 'Bullet List',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleBulletList().run();
        },

        active(editor) {
            return editor.isActive('bulletList');
        },

    },

    orderedList: {

        icon: 'list-ordered',

        title: 'Numbered List',

        run(editor) {
            editor.chain().focus(null, { scrollIntoView: false }).toggleOrderedList().run();
        },

        active(editor) {
            return editor.isActive('orderedList');
        },

    },

};
