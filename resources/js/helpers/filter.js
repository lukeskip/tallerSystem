const filter = (object, key, term) => {
    console.log(object);
    console.log(key);
    object = object.filter((item) => {
        return item[key].toLowerCase().includes(term.toLowerCase());
        
    });
    console.log(object);
    return object;
};

export default filter;