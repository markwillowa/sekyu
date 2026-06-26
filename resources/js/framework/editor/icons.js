import { createIcons, icons } from 'lucide';
import commands from './commands';

export default {
    create(editor, nextTick) {
        createIcons({ icons });
        if (nextTick) {
            nextTick(() => createIcons({ icons }));
        }
    },

    getIcon(name, editor) {
        const command = commands.get(name);
        if (command) {
            const iconName = typeof command.icon === 'function' ? command.icon(editor) : command.icon;
            return `<i data-lucide="${iconName}" class="w-4 h-4"></i>`;
        }
        return '';
    }
};
