export default class Registry {
    constructor() {
        this.items = new Map();
    }

    register(name, item) {
        this.items.set(name, item);
        return this;
    }

    get(name) {
        return this.items.get(name);
    }

    all() {
        return Array.from(this.items.entries()).reduce((acc, [name, item]) => {
            acc[name] = item;
            return acc;
        }, {});
    }

    keys() {
        return Array.from(this.items.keys());
    }

    values() {
        return Array.from(this.items.values());
    }
}
