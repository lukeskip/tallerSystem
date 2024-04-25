import strings from '@/utils/strings'


const showLabel = (label)=>{
    console.log(label,typeof label);
    if (label && label.startsWith("https")) {
        
        return `<a href="${label}" target="_blank"><i class="fa-solid fa-image"></i></a>`;
    } else {
        if(strings[label]) return strings[label]
    }
    
    return label;
}
export default showLabel;