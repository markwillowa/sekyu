import commands from './commands';
import icons from './icons';

export default {
    run(name, editor, callback) {
        const command = commands.get(name);
        if (command && editor) {
            command.run(editor);
            if (callback) callback();
        }
    },

    active(name, editor) {
        const command = commands.get(name);
        return command && editor ? command.active(editor) : false;
    },

    title(name, editor) {
        const command = commands.get(name);
        if (command) {
            return typeof command.title === 'function' ? command.title(editor) : command.title;
        }
        return '';
    },

    icon(name, editor) {
        return icons.getIcon(name, editor);
    }
};
