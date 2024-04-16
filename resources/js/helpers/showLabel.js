import strings from '@/utils/strings'
const showLabel = (label)=>{
    if(strings[label]) return strings[label]
    return label;
}
export default showLabel;