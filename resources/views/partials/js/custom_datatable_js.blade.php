<script>
function filterTable(tableId,filterValue) {
    $(`#${tableId} tbody tr`).each(function () {
        // Check each row's content
        var rowText = $(this).text().toLowerCase();
        if (rowText.includes(filterValue)) {
            $(this).show(); // Show the row if it matches the filter
        } else {
            $(this).hide(); // Hide the row if it doesn't match
        }
    });
 }
$(document).ready(function () {
    $('.dtable').each((index, element)=>{
        var table = $(element);
        table.attr('id',`dtable-${index}`);

        var beforeElement = $('<div class="p-2 bg-slate-100"></div>');
        beforeElement.attr('id',`dtable-panel-${index}`);
        beforeElement.attr('class',`dtable-panel`);

        var filterInput = $('<input class="rounded-lg px-2 py-2 " type="search" placeholder="Search keywords."></input>');
        filterInput.attr('class','dtable-filter-field');
        filterInput.attr('id',`dtable-panel-filter-${index}`);

        filterInput.appendTo(beforeElement);
        beforeElement.insertBefore(table);
    })
    $('.dtable-filter-field').on('input',function(el){
        var filterValue = $(this).val().toLowerCase();
        var tableInputId = $(this).attr('id');
        var tableId = $(this).parent().attr('id').split('-')[2]
        tableId = `dtable-${tableId}`
        filterTable(tableId,filterValue);
    })
});
</script>
