import { Editor } from '@tiptap/core';
import extensions from './extensions';
import icons from './icons';

export default {
    create(element, content, placeholder, options = {}) {
        return new Editor({
            element,
            content,
            extensions: extensions(placeholder),
            ...options
        });
    },

    renderIcons() {
        icons.create();
    }
};
