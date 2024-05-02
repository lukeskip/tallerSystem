import Swal from 'sweetalert2'
import { router } from '@inertiajs/vue3';
const errorHandler = (error,emit = ()=>{} )=>{

    if(error.response.status !== 422){  
        emit('close');
        console.log(emit);
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.message,
            footer: 'Por favor inténtalo de nuevo'
        }).then(()=>{
            router.reload();
            if(error.response.status === 419 || error.response.status === 401){
                window.location.reload();
            }
        });
    }
}
export default errorHandler;