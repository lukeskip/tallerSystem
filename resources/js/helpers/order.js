const filter = (object,key,term)=>{
    object = object.filter((item)=>{
        return item[key].includes(term)
    })
    return object;
}

export default filter;