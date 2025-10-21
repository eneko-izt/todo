import $ from 'jquery';
import 'datatables.net-dt';
import 'datatables.net-dt/css/jquery.dataTables.css';

window.$ = window.jQuery = $;

$(document).ready(function () {
    $('.datatable').DataTable();
});

