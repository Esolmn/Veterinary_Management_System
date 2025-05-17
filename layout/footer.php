<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.2.2/af-2.7.0/b-3.2.2/b-colvis-3.2.2/b-html5-3.2.2/b-print-3.2.2/cr-2.0.4/date-1.5.5/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.4/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.2/sp-2.3.3/sl-3.0.0/sr-1.4.1/datatables.min.js" integrity="sha384-10kTwhFyUU637a6/7q0kLBdo8jQWjxteg63DT/K8Sdq/nCDaDAkH+Nq/MIrsp8wc" crossorigin="anonymous"></script>
<script src="/Veterinary_Management_System/layout/appointment.js"></script>
<script src="/Veterinary_Management_System/layout/petstatus.js"></script>

<script>
$(document).ready( function () {
    $('#petOwnerTable').DataTable();
    $('#adminTable').DataTable();
    $('#exampleTable').DataTable();
    $('#petTable').DataTable();
    $('#table').DataTable();
    const table = $('#treatmentTable').DataTable({
        ordering: false, //inalis sorting
        dom: 't', //table lng papakita
        paging: false, //walang page selector 
        info: false 
    });
    $('#customSearch').on('keyup', function() {
      table.search(this.value).draw();
    });
    });
    $.extend(true, $.fn.dataTable.defaults, {
        ordering: true,
        searching:true,
        select: true,
    "order": [[ 0, 'desc' ], [ 1, 'desc' ], [2, 'desc'], [3, 'desc']]
    } 
);
</script>

</body>
</html>