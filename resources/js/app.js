import './bootstrap';

import $ from 'jquery';
import 'datatables.net-dt/css/jquery.dataTables.min.css';
import DataTable from 'datatables.net-dt';

window.$ = window.jQuery = $;

DataTable(window, $);

$(document).ready(function () {
    $('#myTable').DataTable();
});