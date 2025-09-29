import $ from 'jquery';
import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/jquery.dataTables.css';

window.$ = window.jQuery = $;

DataTable(window, $);

new DataTable('#myTable', {
    paging: true,
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50],
    searching: true,
    ordering: true,
    language: {
        paginate: {
            previous: 'Previous',
            next: 'Next'
        },
        lengthMenu: "Show _MENU_ entries"
    },
    dom: '<"flex justify-between items-center mb-2"l<"ml-auto"f>>t<"flex justify-between items-center mt-2"ip>'
});
