import toolbar from './toolbar';
import renderer from './renderer';

export default (settings = {}, config) => ({
    editor: null,
    content: settings.content ?? '',
    placeholder: settings.placeholder ?? 'Start writing...',
    toolbar: config.toolbars[settings.toolbar ?? 'full'] ?? config.toolbars.full,
    isFocused: false,
    updatedAt: Date.now(),

    init() {
        if (this.editor) return;

        const editor = renderer.create(this.$refs.editor, this.content, this.placeholder, {
            onUpdate: ({ editor }) => {
                this.content = editor.getHTML();
                this.updatedAt = Date.now();
                this.$nextTick(() => renderer.renderIcons());
            },
            onSelectionUpdate: () => {
                this.updatedAt = Date.now();
                this.$nextTick(() => renderer.renderIcons());
            },
            onTransaction: () => {
                this.updatedAt = Date.now();
                this.$nextTick(() => renderer.renderIcons());
            },
            onFocus: () => {
                this.isFocused = true;
                this.updatedAt = Date.now();
                this.$nextTick(() => renderer.renderIcons());
            },
            onBlur: () => {
                this.isFocused = false;
                this.updatedAt = Date.now();
                this.$nextTick(() => renderer.renderIcons());
            },
        });

        this.editor = editor;

        this.$nextTick(() => renderer.renderIcons());
    },

    destroy() {
        this.editor?.destroy();
    },

    focus() {
        const editor = Alpine.raw(this.editor);
        if (editor) {
            // Only focus if not already focused to avoid jumps and mismatched transactions
            if (!editor.isFocused) {
                editor.chain().focus('start', { scrollIntoView: false }).run();
            }
        }
    },

    run(name) {
        if (! this.editor) return;

        toolbar.run(name, Alpine.raw(this.editor), () => {
            this.updatedAt = Date.now();
            this.$nextTick(() => renderer.renderIcons());
        });
    },

    active(name) {
        this.updatedAt; // Dependency for reactivity
        return toolbar.active(name, Alpine.raw(this.editor));
    },

    icon(name) {
        this.updatedAt; // Dependency for reactivity
        return toolbar.icon(name, Alpine.raw(this.editor));
    },

    title(name) {
        this.updatedAt; // Dependency for reactivity
        return toolbar.title(name, Alpine.raw(this.editor));
    }
});
