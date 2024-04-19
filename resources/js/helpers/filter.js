const filter = (object, key, term) => {
    console.log(term);
    object = object.filter((item) => {
        return item[key].toLowerCase().includes(term.toLowerCase());
        
    });
    console.log(object);
    return object;
};

export default filter;