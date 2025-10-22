// Call the dataTables jQuery plugin
$(document).ready(function () {
  $("#dataTable").DataTable();
});
new DataTable("#asset", {
  fixedHeader: { header: true },
  columnDefs: [
    {
      targets: [1, 2],
      columnControl: [
        "order",
        ["searchList", "spacer", "orderAsc", "orderDesc", "orderClear"],
      ],
    },
  ],
  ordering: {
    indicators: false,
    handler: false,
  },
});

//ColumnControl for Aseset Inventory
new DataTable("#asset_inventory", {
  fixedHeader: { header: true },
  columnDefs: [
    {
      targets: [1, 2, 3, 4, 5],
      columnControl: [
        "order",
        ["searchList", "spacer", "orderAsc", "orderDesc", "orderClear"],
      ],
    },
  ],
  ordering: {
    indicators: false,
    handler: false,
  },
});

new DataTable();
