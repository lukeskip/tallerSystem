const filter = (object, key, term) => {
    const keys = Array.isArray(key) ? key : [key];
    const searchTerm = term.toLowerCase();

    return object.filter((item) => {
        return keys.some((k) => {
            const value = item[k];
            if (!value) return false;
            
            if (Array.isArray(value)) {
                return value.some(subItem => {
                    if (typeof subItem === 'object' && subItem.name) {
                        return String(subItem.name).toLowerCase().includes(searchTerm);
                    }
                    return String(subItem).toLowerCase().includes(searchTerm);
                });
            }
            
            return String(value).toLowerCase().includes(searchTerm);
        });
    });
};

export default filter;