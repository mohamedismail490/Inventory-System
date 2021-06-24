class Notification {

    success(){
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: 'Successfully Done!',
            timeout: 1500
        }).show();
    }
    customSuccess(message){
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: ((typeof (message) != 'undefined') && (message !== '')) ? message : 'Successfully Done!',
            timeout: 1500
        }).show();
    }

    alert(){
        new Noty({
            type: 'alert',
            layout: 'topRight',
            text: 'Are You Sure ?',
            timeout: 1500
        }).show();
    }

    error(){
        new Noty({
            type: 'error',
            layout: 'topRight',
            text: 'Something went Wrong !',
            timeout: 1500
        }).show();
    }

    customError(message){
        new Noty({
            type: 'error',
            layout: 'topRight',
            text: ((typeof (message) != 'undefined') && (message !== '')) ? message : 'Something went Wrong !',
            timeout: 1500
        }).show();
    }

    warning(){
        new Noty({
            type: 'warning',
            layout: 'topRight',
            text: 'Opps Wrong!',
            timeout: 1500
        }).show();
    }

    image_validation(){
        new Noty({
            type: 'error',
            layout: 'topRight',
            text: 'Upload Image less then 1MB ',
            timeout: 1500,
        }).show();
    }
}
export default Notification = new Notification();
