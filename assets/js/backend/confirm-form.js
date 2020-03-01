import $ from 'jquery';
import Swal from 'sweetalert2';


window.addEventListener('load', function () {
    $('[data-confirm-submit="true"]').on('submit', function (e) {
        e.preventDefault();
        let $this = $(this);

        Swal.fire({
            title: $this.data("confirm-message"),
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-check"></i> Sim',
            cancelButtonText: '<i class="fa fa-times"></i> Não',
        }).then(function (result) {
            if(result.value){
                e.target.submit();
            }
        });
    })
});