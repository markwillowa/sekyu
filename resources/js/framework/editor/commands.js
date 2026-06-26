import Registry from './registry';

import typography from './commands/typography';
import paragraph from './commands/paragraph';
import list from './commands/list';
import history from './commands/history';
import link from './commands/link';

const registry = new Registry();

// Register Typography
Object.entries(typography).forEach(([name, command]) => {
    registry.register(name, command);
});

// Register Paragraphs & Headings
Object.entries(paragraph).forEach(([name, command]) => {
    registry.register(name, command);
});

// Register Lists
Object.entries(list).forEach(([name, command]) => {
    registry.register(name, command);
});

// Register History
Object.entries(history).forEach(([name, command]) => {
    registry.register(name, command);
});

// Register Links
Object.entries(link).forEach(([name, command]) => {
    registry.register(name, command);
});

export default registry;
