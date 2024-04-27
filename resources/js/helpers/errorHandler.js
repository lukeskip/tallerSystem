import Swal from 'sweetalert2'
const errorHandler = (error)=>{

    if(error.response.status !== 422){  
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.message,
            footer: 'Por favor inténtalo de nuevo'
        }).then(()=>{
            if(error.response.status === 419){
                window.location.reload();
            }
        });
    }
}
export default errorHandler;